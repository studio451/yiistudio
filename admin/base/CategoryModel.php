<?

namespace admin\base;

use Yii;
use yii\helpers\Url;
use admin\behaviors\SluggableBehavior;
use admin\behaviors\CacheFlush;
use admin\modules\seo\behaviors\SeoTextBehavior;
use admin\modules\seo\behaviors\SeoTemplateBehavior;
use admin\behaviors\SortableModel;
use creocoder\nestedsets\NestedSetsBehavior;

/**
 * Base CategoryModel. Shared by categories
* @package admin\base
 */
class CategoryModel extends \admin\base\ActiveRecord {

    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    public function rules() {
        return [
                ['title', 'required'],
                ['title', 'trim'],
                ['title', 'string', 'max' => 128],
                ['image', 'image'],
                ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('admin', 'Код может содержать символы 0-9, a-z и "-" (не более: 256).')],
                ['slug', 'default', 'value' => null],
                ['status', 'integer'],
                ['status', 'default', 'value' => self::STATUS_ON],
        ];
    }

    public function attributeLabels() {
        return [
            'title' => Yii::t('admin', 'Описание'),
            'image' => Yii::t('admin', 'Изображение'),
            'slug' => Yii::t('admin', 'Код'),
            'description' => Yii::t('admin', 'Описание'),
            'status' => Yii::t('admin', 'Активность'),
            'time' => Yii::t('admin', 'Дата'),
        ];
    }

    public function behaviors() {
        return [
            'cacheflush' => [
                'class' => CacheFlush::className(),
                'key' => [static::tableName() . '_tree', static::tableName() . '_flat']
            ],
            'seoText' => SeoTextBehavior::className(),
            'seoTemplate' => SeoTemplateBehavior::className(),
            'sluggable' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true
            ],
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree'
            ],
            SortableModel::className()
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if (!$insert && $this->image != $this->oldAttributes['image'] && $this->oldAttributes['image']) {
                @unlink(Yii::getAlias('@webroot') . $this->oldAttributes['image']);
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterDelete() {
        parent::afterDelete();

        if ($this->image) {
            @unlink(Yii::getAlias('@webroot') . $this->image);
        }
    }

    /**
     * @return ActiveQueryNS
     */
    public static function find() {
        return new ActiveQueryNS(get_called_class());
    }

    /**
     * Get cached tree structure of category objects
     * @return array
     */
    public static function tree($slug, $admin = false) {
        if ($admin) {
            $tree = static::generateTree(true);
        } else {
            $cache = Yii::$app->cache;

            $key = static::tableName() . '_tree';

            $tree = $cache->get($key);
            if ($tree === false) {
                $tree = static::generateTree();
                $cache->set($key, $tree, 3600);
            }
        }
        return self::findBranch($slug, $tree);
    }

    private static function findBranch($slug, $tree) {
        foreach ($tree as $brunch) {
            if ($brunch->slug == $slug) {
                return $brunch;
            } else {
                if (count($brunch->children) > 0) {
                    $result = self::findBranch($slug, $brunch->children);
                    if ($result) {
                        return $result;
                    }
                }
            }
        }
        return [];
    }

    /**
     * Get cached menu structure of category objects
     * @return array
     */
    public static function menu($slug, $icon = '') {
        $tree = self::tree($slug);
        return self::generateMenu($tree->children, $icon);
    }

    private static function generateMenu($tree, $icon = '') {
        $menu = [];
        foreach ($tree as $brunch) {
            if ($brunch->status == static::STATUS_ON) {
                $item = [];
                $item['label'] = $brunch->title;
                $item['url'][] = Url::to(['/catalog', 'slug' => $brunch->slug]);
                if ($icon) {
                    $item['icon'] = 'fa fa-circle';
                }
                if (count($brunch->children) > 0) {
                    $item['children'] = self::generateMenu($brunch->children, $url_root, $icon = '');
                }
                $menu[] = $item;
            }
        }
        return $menu;
    }

    /**
     * Get cached flat array of category objects
     * @return array
     */
    public static function flat($slug = '', $admin = false) {
        if ($admin) {
            $flat = static::generateFlat(true);
        } else {
            $cache = Yii::$app->cache;
            $key = static::tableName() . '_flat';


            $flat = $cache->get($key);
            if ($flat === false) {
                $flat = static::generateFlat();
                $cache->set($key, $flat, 3600);
            }
        }

        if ($slug) {
            $categories = [];
            $depth = -1;
            foreach ($flat as $category) {
                if ($category->slug == $slug) {
                    $categories[] = $category;
                    $depth = $category->depth;
                    continue;
                }
                if ($depth != -1) {
                    if ($category->depth == $depth) {
                        break;
                    } else {
                        $categories[] = $category;
                    }
                }
            }
            return $categories;
        } else {
            return $flat;
        }
    }

    /**
     * Generates tree from categories
     * @return array
     */
    public static function generateTree($admin = false) {
        if ($admin) {
            $collection = static::find()->with('seoText')->sort()->asArray()->all();
        } else {
            $collection = static::find()->status(static::STATUS_ON)->with('seoText')->sort()->asArray()->all();
        }

        $trees = array();
        $l = 0;

        if (count($collection) > 0) {
            // Node Stack. Used to help building the hierarchy
            $stack = array();

            foreach ($collection as $node) {
                $item = $node;
                unset($item['lft'], $item['rgt'], $item['order_num']);
                $item['children'] = array();

                // Number of stack items
                $l = count($stack);

                // Check if we're dealing with different levels
                while ($l > 0 && $stack[$l - 1]->depth >= $item['depth']) {
                    array_pop($stack);
                    $l--;
                }

                // Stack is empty (we are inspecting the root)
                if ($l == 0) {
                    // Assigning the root node
                    $i = count($trees);
                    $trees[$i] = (object) $item;
                    $stack[] = & $trees[$i];
                } else {
                    // Add node to parent
                    $item['parent'] = $stack[$l - 1]->category_id;
                    $i = count($stack[$l - 1]->children);
                    $stack[$l - 1]->children[$i] = (object) $item;
                    $stack[] = & $stack[$l - 1]->children[$i];
                }
            }
        }

        return $trees;
    }

    /**
     * Generates flat array of categories
     * @return array
     */
    public static function generateFlat($admin = false) {
        if ($admin) {
            $collection = static::find()->with('seoText')->sort()->asArray()->all();
        } else {
            $collection = static::find()->status(static::STATUS_ON)->with('seoText')->sort()->asArray()->all();
        }
        $flat = [];

        if (count($collection) > 0) {
            
            
            $depth = 0;
            $lastId = 0;
            foreach ($collection as $node) {
               
                $node = (object) $node;
                $id = $node->id;
                $node->parent = '';

                if ($node->depth > $depth) {
                    $node->parent = $flat[$lastId]->id;
                    $depth = $node->depth;
                } elseif ($node->depth == 0) {
                    $depth = 0;
                } else {
                    if ($node->depth == $depth) {
                        $node->parent = $flat[$lastId]->parent;
                    } else {
                        foreach ($flat as $temp) {
                            if ($temp->depth == $node->depth) {
                                $node->parent = $temp->parent;
                                $depth = $temp->depth;
                                break;
                            }
                        }
                    }
                }
                $lastId = $id;
                unset($node->lft, $node->rgt);
                $flat[$id] = $node;
            }
        }

        foreach ($flat as &$node) {
            $node->children = [];
            foreach ($flat as $temp) {
                if ($temp->parent == $node->id) {
                    $node->children[] = $temp->id;
                }
            }
        }

        return $flat;
    }

}
