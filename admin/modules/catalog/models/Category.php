<?

namespace admin\modules\catalog\models;

use Yii;
use admin\modules\sitemap\behaviors\SitemapBehavior;

class Category extends \admin\components\CategoryModel {

    static $fieldTypes = [
        'string' => 'String',
        'text' => 'Text',
        'boolean' => 'Boolean',
        'select' => 'Select',
        'checkbox' => 'Checkbox',
        'ymlColor' => 'YML Color',
        'data' => 'Data'
    ];

    public static function tableName() {
        return 'admin_module_catalog_category';
    }

    public function behaviors() {

        return array_merge(parent::behaviors(), [
            'sitemap' => [
                'class' => SitemapBehavior::className(),
                'type' => SitemapBehavior::CATEGORY,
            ],
        ]);
    }

    public function rules() {
        return [
                [['title'], 'required'],
                ['title', 'trim'],
                ['title', 'string', 'max' => 256],
                ['image', 'image'],
                ['description', 'trim'],
                ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('admin', 'Код может содержать символы 0-9, a-z и "-" (не более: 256).')],
                ['slug', 'default', 'value' => null],
                ['status', 'default', 'value' => self::STATUS_ON],
                ['time', 'default', 'value' => time()],
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

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($insert && ($parent = $this->parents(1)->one())) {
                $this->fields = $parent->fields;
            }

            if (!$this->fields || !is_array($this->fields)) {
                $this->fields = [];
            }
            $this->fields = json_encode($this->fields);

            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $attributes) {
        parent::afterSave($insert, $attributes);
        $this->parseFields();
    }

    public function afterFind() {
        parent::afterFind();
        $this->parseFields();
    }

    public function getItems() {
        return $this->hasMany(Item::className(), ['category_id' => 'id'])->orderBy(['brand_id' => SORT_ASC, 'name' => SORT_ASC]);
    }

    public function getGroups() {
        return $this->hasMany(Group::className(), ['category_id' => 'id'])->sortDate();
    }

    public function afterDelete() {
        parent::afterDelete();

        foreach ($this->getItems()->all() as $item) {
            $item->delete();
        }
    }

    public function parseFields() {
        $this->fields = $this->fields !== '' ? json_decode($this->fields) : [];
    }

    public function getField($name) {
        foreach ($this->fields as $field) {
            if ($field->name == $name) {
                return $field;
            }
        }
        return null;
    }

    public function getFieldOptions($name) {
        foreach ($this->fields as $field) {
            if ($field->name == $name) {
                return $field->options;
            }
        }
        return null;
    }

    public function setFieldOptions($name, $value) {
        foreach ($this->fields as $field) {
            if ($field->name == $name) {
                $field->options = $value;
                return true;
            }
        }
        return null;
    }

}
