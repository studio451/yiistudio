<?

namespace admin\modules\catalog\models;

use Yii;
use admin\behaviors\SluggableBehavior;
use admin\modules\seo\behaviors\SeoTextBehavior;

class Brand extends \admin\components\ActiveRecord {

    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    public static function tableName() {
        return 'admin_module_catalog_brand';
    }

    public function rules() {
        return [
            [['title'], 'required'],
            ['title', 'trim'],
            ['title', 'string', 'max' => 256],
            ['image', 'image'],
            [['description', 'short'], 'trim'],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('admin', 'Код может содержать символы 0-9, a-z и "-" (не более: 256).')],
            ['slug', 'default', 'value' => null],
            [['status',], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ON]
        ];
    }

    public function attributeLabels() {
        return [
            'title' => Yii::t('admin', 'Название'),
            'slug' => Yii::t('admin', 'Код'),
            'image' => Yii::t('admin', 'Изображение'),
            'description' => Yii::t('admin', 'Описание'),
            'short' => Yii::t('admin', 'Краткий текст'),
        ];
    }

    public function getItems() {
        return $this->hasMany(Item::className(), ['brand_id' => 'id']);
    }

    public function getGroups() {
        return $this->hasMany(Group::className(), ['brand_id' => 'id']);
    }

    public function behaviors() {
        return [
            'seoText' => SeoTextBehavior::className(),
            'sluggable' => [
                'class' => SluggableBehavior::className(),
                'replacement' => '',
                'attribute' => 'title',
                'ensureUnique' => true
            ]
        ];
    }

}
