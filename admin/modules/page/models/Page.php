<?

namespace admin\modules\page\models;

use Yii;
use admin\modules\seo\behaviors\SeoTextBehavior;
use admin\behaviors\CacheFlush;

class Page extends \admin\components\ActiveRecord {

    const CACHE_KEY = 'admin_module_page';

    public static function tableName() {
        return 'admin_module_pages';
    }

    public function rules() {
        return [
            ['title', 'required'],
            [['title', 'text'], 'trim'],
            ['title', 'string', 'max' => 256],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('admin', 'Код может содержать символы 0-9, a-z и "-" (не более: 128).')],
            ['slug', 'default', 'value' => null],
            ['slug', 'unique'],
        ];
    }

    public function attributeLabels() {
        return [
            'title' => Yii::t('admin', 'Название'),
            'text' => Yii::t('admin', 'Текст'),
            'slug' => Yii::t('admin', 'Код'),
        ];
    }

    public function behaviors() {
        return [
            'seoText' => SeoTextBehavior::className(),
            CacheFlush::className()
        ];
    }

}
