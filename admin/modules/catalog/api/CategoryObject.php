<?

namespace admin\modules\catalog\api;

use Yii;
use yii\data\ActiveDataProvider;
use admin\base\Api;
use admin\modules\catalog\models\Category;
use admin\modules\catalog\models\Group;
use admin\modules\catalog\models\Brand;
use admin\modules\catalog\models\Item;
use yii\helpers\Url;
use yii\widgets\LinkPager;

class CategoryObject extends \admin\base\ApiObject {

    public $slug;
    public $image;
    public $fields;
    public $description;
    private $_groups;
    private $_groups_adp;
    private $_items;
    private $_items_adp;
    private $_brands;
    private $_categories;
    private $_tree;
    private $_flat;

    public function getTitle() {
        return $this->model->title;
    }

    public function getDescription() {
        return LIVE_EDIT ? Api::liveEdit($this->model->description, $this->editLink, 'div') : $this->model->description;
    }

    public function getEditLink() {
        return Url::to(['/admin/catalog/a/edit', 'id' => $this->id]);
    }

    public function fieldOptions($name, $firstOption = '') {
        $options = [];
        if ($firstOption) {
            $options[''] = $firstOption;
        }

        foreach ($this->fields as $field) {
            if ($field->name == $name) {
                foreach ($field->options as $option) {
                    $options[$option] = $option;
                }
                break;
            }
        }
        return $options;
    }

    public function tree() {
        if (!$this->_tree) {
            $this->_tree = [];
            $this->_tree = Category::tree($this->slug);
        }
        return $this->_tree;
    }

    public function menu($icon = '') {
        return Category::menu($this->slug, $icon);
    }

    public function categories($options = []) {
        if (!$this->_categories) {
            $this->_categories = [];

            $flat = $this->flat();
            $ids[] = $this->id;
            foreach ($flat as $category) {
                $ids[] = $category->id;
            }
            if (empty($options['all'])) {
                $subQuery = Item::find()->select('category_id')->status(Item::STATUS_ON);
                if (!empty($options['brand_id'])) {
                    $subQuery->andFilterWhere(['=', 'brand_id', (int) $options['brand_id']]);
                }
                $query = Category::find()->where(['in', 'category_id', $ids])->status(Category::STATUS_ON)->join('INNER JOIN', ['i' => $subQuery], 'i.category_id = ' . Category::tableName() . '.id');
            }  else {
                $query = Category::find()->where(['in', 'id', $ids])->status(Category::STATUS_ON);            
            }

            if (!empty($options['where'])) {
                $query->andFilterWhere($options['where']);
            }
            if (!empty($options['orderBy'])) {
                $query->orderBy($options['orderBy']);
            } else {
                $query->sort();
            }

            $models = $query->all();
            foreach ($models as $model) {
                $this->_categories[] = new CategoryObject($model, $options);
            }
        }
        return $this->_categories;
    }

    public function categoriesOptions($firstOption = '', $withSlug = false) {
        $options = [];
        if ($firstOption) {
            $options[''] = $firstOption;
        }

        if ($withSlug) {
            foreach ($this->categories() as $category) {
                $options[$category->slug] = $category->model->title;
            }
        } else {
            foreach ($this->categories() as $category) {
                $options[$category->id] = $category->model->title;
            }
        }
        return $options;
    }

    public function getFieldOptions($name) {
        foreach ($this->fields as $field) {
            if ($field->name == $name) {
                return $field->options;
            }
        }
        return null;
    }

    public function flat() {
        if (!$this->_flat) {
            $this->_flat = [];
            $this->_flat = Category::flat($this->slug);
        }
        return $this->_flat;
    }

    public function brands($options = []) {
        if (!$this->_brands) {
            $this->_brands = [];

            $flat = $this->flat();
            $ids[] = $this->id;
            foreach ($flat as $category) {
                $ids[] = $category->id;
            }

            $subQuery = Item::find()->select('brand_id')->where(['in', 'category_id', $ids])->status(Item::STATUS_ON);
            $query = Brand::find()->status(Brand::STATUS_ON)->join('INNER JOIN', ['i' => $subQuery], 'i.brand_id = ' . Brand::tableName() . '.id');

            if (!empty($options['where'])) {
                $query->andWhere(['is not', 'image', null]);
            }
            if (!empty($options['orderBy'])) {
                $query->orderBy($options['orderBy']);
            } else {
                $query->orderBy(['title' => SORT_ASC]);
            }

            $cache = Yii::$app->cache;
            $key = $cache->buildKey($query);
            $brands = $cache->get($key);
            if ($brands == false) {
                $brands = $query->all();
                $cache->set($key, $brands, 3600);
            }
            foreach ($brands as $brand) {
                $this->_brands[] = new BrandObject($brand, $options);
            }
        }
        return $this->_brands;
    }

    public function brandsOptions($firstOption = '', $withSlug = false) {
        $options = [];
        if ($firstOption) {
            $options[''] = $firstOption;
        }

        if ($withSlug) {
            foreach ($this->brands() as $brand) {
                $options[$brand->slug] = $brand->model->title;
            }
        } else {
            foreach ($this->brands() as $brand) {
                $options[$brand->id] = $brand->model->title;
            }
        }
        return $options;
    }

    public function groups($options = []) {
        if (!$this->_groups) {
            $this->_groups = [];

            $flat = $this->flat();
            $ids[] = $this->id;
            foreach ($flat as $category) {
                $ids[] = (int) $category->id;
            }

            $query = Group::find()->where(['in', 'category_id', $ids])->status(Group::STATUS_ON);

            if (!empty($options['where'])) {
                $query->andFilterWhere($options['where']);
            }
            if (!empty($options['filters']) || !empty($options['sort'])) {
                $query = Catalog::applyFiltersAndSortForGroups($options['filters'], $options['sort'], $query);
            }

            $this->_groups_adp = new ActiveDataProvider([
                'query' => $query,
                'pagination' => !empty($options['pagination']) ? $options['pagination'] : [],
            ]);

            $this->_groups = [];
            foreach ($this->_groups_adp->models as $model) {
                $this->_groups[] = new GroupObject($model, $options);
            }
        }
        return $this->_groups;
    }

    public function groups_pages($options = []) {
        return $this->_groups_adp ? LinkPager::widget(array_merge($options, ['pagination' => $this->_groups_adp->pagination])) : '';
    }

    public function groups_pagination() {
        return $this->_groups_adp ? $this->_groups_adp->pagination : null;
    }

    public function items($options = []) {
        if (!$this->_items) {
            $this->_items = [];

            $flat = $this->flat();
            $ids[] = $this->id;
            foreach ($flat as $category) {
                $ids[] = $category->id;
            }

            $query = Item::find()->where(['in', 'category_id', $ids])->status(Item::STATUS_ON);

            if (!empty($options['where'])) {
                $query->andFilterWhere($options['where']);
            }
            if (!empty($options['orderBy'])) {
                $query->orderBy($options['orderBy']);
            } else {
                $query->sort();
            }
            if (!empty($options['filters'])) {
                $query = Catalog::applyFiltersForItems($options['filters'], $query);
            }

            $this->_items_adp = new ActiveDataProvider([
                'query' => $query,
                'pagination' => !empty($options['pagination']) ? $options['pagination'] : []
            ]);

            foreach ($this->_items_adp->models as $model) {
                $this->_items[] = new ItemObject($model, $options);
            }
        }
        return $this->_items;
    }

    public function items_pagination() {
        return $this->_items_adp ? $this->_items_adp->pagination : null;
    }

    public function items_pages($options = []) {
        return $this->_items_adp ? LinkPager::widget(array_merge($options, ['pagination' => $this->_items_adp->pagination])) : '';
    }

    public function getParents() {

        return $this->model->parents()->all();
    }

}
