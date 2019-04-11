<?

namespace admin\modules\catalog\models;

use Yii;
use yii\base\Exception;
use admin\models\Photo;
use admin\behaviors\Taggable;
use admin\behaviors\SluggableBehavior;
use admin\modules\seo\behaviors\SeoTextBehavior;
use admin\modules\sitemap\behaviors\SitemapBehavior;
use admin\behaviors\SortableModel;

class Item extends \admin\base\ActiveRecord {

    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    public static function tableName() {
        return 'admin_module_catalog_item';
    }

    public function behaviors() {
        return [
            SortableModel::className(),
            'seoText' => SeoTextBehavior::className(),
            'taggabble' => Taggable::className(),
            'sluggable' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true
            ],
            'sitemap' => [
                'class' => SitemapBehavior::className(),
                'type' => SitemapBehavior::ITEM,
            ],
        ];
    }

    public function rules() {
        return [
                ['price', 'number'],
                ['base_price', 'number'],
                [['brand_id', 'name', 'category_id'], 'required'],
                [['article', 'type'], 'safe'],
                [['article', 'type'], 'trim'],
                ['article', 'string', 'max' => 128],
                ['discount', 'integer', 'max' => 99],
                [['gift', 'new'], 'integer'],
                [['status', 'brand_id', 'category_id', 'available', 'time'], 'integer'],
                ['time', 'default', 'value' => time()],
                ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('admin', 'Код может содержать символы 0-9, a-z и "-" (не более: 128).')],
                ['slug', 'default', 'value' => null],
                ['status', 'default', 'value' => self::STATUS_ON],
                ['description', 'trim'],
                ['tagNames', 'safe'],
                ['external_manual', 'number'],
        ];
    }

    public function attributeLabels() {
        return [
            'title' => Yii::t('admin', 'Название'),
            'category_id' => Yii::t('admin', 'Категория'),
            'brand_id' => Yii::t('admin', 'Бренд'),
            'type' => Yii::t('admin', 'Тип элемента'),
            'article' => Yii::t('admin', 'Артикул'),
            'name' => Yii::t('admin', 'Модель'),
            'image' => Yii::t('admin', 'Изобр.'),
            'image_alt' => Yii::t('admin', 'Алтернативное изобр.'),
            'status' => Yii::t('admin', 'Статус'),
            'description' => Yii::t('admin', 'Описание'),
            'available' => Yii::t('admin/catalog', 'Доступно'),
            'price' => Yii::t('admin/catalog', 'Цена'),
            'base_price' => Yii::t('admin/catalog', 'Закупочная цена'),
            'discount' => Yii::t('admin/catalog', 'Скидка %'),
            'gift' => Yii::t('admin/catalog', 'Подарок'),
            'new' => Yii::t('admin/catalog', 'Новинка'),
            'time' => Yii::t('admin', 'Дата'),
            'slug' => Yii::t('admin', 'Код'),
            'tagNames' => Yii::t('admin', 'Теги'),
            'external_manual' => Yii::t('admin', 'Позиция управляется вручную'),
        ];
    }

    public function beforeValidate() {

        if (!($category = Category::findOne($this->category_id))) {
            throw new Exception(Yii::t('admin/catalog', "Не найдена категория элемента каталога!"));
        }

        $this->type = $category->getFieldOptions('type');

        if (Yii::$app->getModule('admin')->activeModules['catalog']->settings['generateComplexTitle']) {
            $this->title = $this->type . ' ' . $this->brand->title . ' ' . $this->name . ' ' . $this->article;
        } else {
            $this->title = $this->name;
        }
        return parent::beforeValidate();
    }

    public function beforeSave($insert) {

        if (parent::beforeSave($insert)) {
            if (!$this->data || (!is_object($this->data) && !is_array($this->data))) {
                $this->data = new \stdClass();
            }

            $this->data = json_encode($this->data);

            if (!$insert &&
                    ($this->category_id != $this->oldAttributes['category_id'] || $this->brand_id != $this->oldAttributes['brand_id'] || $this->name != $this->oldAttributes['name']) || $this->status == self::STATUS_OFF || $this->available == 0
            ) {
                //Ищем другие элементы у которых может быть эта же группа
                $query = Item::find()->where(
                        [
                            'category_id' => $this->oldAttributes['category_id'],
                            'brand_id' => $this->oldAttributes['brand_id'],
                            'name' => $this->oldAttributes['name'],
                            'status' => self::STATUS_ON,
                ]);

                $query->andWhere(['>', 'available', 0]);

                $anotherItem = $query->one();

                if (!$anotherItem) {
                    //Если других элементов нет, то удаляем пустые группы 
                    Group::deleteAll(['category_id' => $this->oldAttributes['category_id'], 'brand_id' => $this->oldAttributes['brand_id'], 'name' => $this->oldAttributes['name']]);
                }
            }

            if ($this->status == self::STATUS_ON && $this->available > 0) {
                //Ищем есть ли уже такая группа
                $group = Group::find()->where(['category_id' => $this->category_id, 'brand_id' => $this->brand_id, 'name' => $this->name])->one();
                if (!$group) {
                    //Если нет, то создаем новую группу
                    $group = new Group();
                    $group->category_id = $this->category_id;
                    $group->brand_id = $this->brand_id;
                    $group->name = $this->name;
                    $group->external_name = $this->external_name;
                    $group->save();
                }
                $this->group_id = $group->primaryKey;
            }

            $photos = Photo::find()->where(['class' => Item::className(), 'item_id' => $this->id])->sort()->all();
            if (count($photos) > 0) {
                if ($photos[0]) {
                    $this->image = $photos[0]->image;
                }
                if ($photos[1]) {
                    $this->image_alt = $photos[1]->image;
                }
            } else {
                $this->image = null;
                $this->image_alt = null;
            }

            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $attributes) {
        parent::afterSave($insert, $attributes);

        $this->parseData();

        ItemData::deleteAll(['item_id' => $this->primaryKey]);

        foreach ($this->data as $name => $value) {
            if (!is_array($value)) {
                $this->insertDataValue($name, $value);
            } else {
                foreach ($value as $arrayItem) {
                    $this->insertDataValue($name, $arrayItem);
                }
            }
        }
    }

    private function insertDataValue($name, $value) {
        Yii::$app->db->createCommand()->insert(ItemData::tableName(), [
            'item_id' => $this->id,
            'name' => $name,
            'value' => $value
        ])->execute();
    }

    public function afterFind() {
        parent::afterFind();
        $this->parseData();
    }

    public function getKey() {
        return '0000000' . $this->id;
    }

    public function getPhotos() {
        return $this->hasMany(Photo::className(), ['item_id' => 'id'])->where(['class' => self::className()])->sort();
    }

    public function getGroup() {
        return $this->hasOne(Group::className(), ['id' => 'group_id']);
    }

    public function afterDelete() {
        parent::afterDelete();

        foreach ($this->getPhotos()->all() as $photo) {
            $photo->delete();
        }

        ItemData::deleteAll(['item_id' => $this->primaryKey]);

        //Ищем другие элементы у которых может быть эта же группа
        $query = Item::find()->where(
                [
                    'category_id' => $this->oldAttributes['category_id'],
                    'brand_id' => $this->oldAttributes['brand_id'],
                    'name' => $this->oldAttributes['name'],
                    'status' => self::STATUS_ON,
        ]);

        $query->andWhere(['>', 'available', 0]);

        $anotherItem = $query->one();



        if (!$anotherItem) {
            //Если других элементов нет, то удаляем пустые группы 
            Group::deleteAll(['category_id' => $this->category_id, 'brand_id' => $this->brand_id, 'name' => $this->name]);
        }
    }

    private function parseData() {
        $this->data = $this->data !== '' ? json_decode($this->data) : [];
    }

    public function getCategory() {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getCategoryTitle() {
        return $this->category->title;
    }

    public function getBrand() {
        return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
    }

    public function getBrandTitle() {
        return $this->brand->title;
    }

    public function setNewFlag() {
        if ($this->new) {
            $this->new = 0;
        } else {
            $this->new = 1;
        }
        $this->save();
    }
    
    public function setGiftFlag() {
        if ($this->gift) {
            $this->gift = 0;
        } else {
            $this->gift = 1;
        }
        $this->save();
    }
    
    public function setManualFlag() {
        if ($this->external_manual) {
            $this->external_manual = 0;
        } else {
            $this->external_manual = 1;
        }
        $this->save();
    }

}
