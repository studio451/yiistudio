<?

namespace admin\modules\block\models;

use Yii;
use admin\behaviors\CacheFlush;

class Block extends \admin\components\ActiveRecord {

    const CACHE_KEY = 'admin_module_block';

    public static function tableName() {
        return 'admin_module_block';
    }

    public function rules() {
        return [
            [['text', 'slug'], 'required'],
            [['text', 'assets_css', 'assets_js'], 'trim'],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('admin', 'Код может содержать символы 0-9, a-z и "-" (не более: 128).')],
            ['slug', 'default', 'value' => null],
            ['slug', 'unique']
        ];
    }

    public function attributeLabels() {
        return [
            'text' => Yii::t('admin', ' Текст'),
            'assets_css' => Yii::t('admin', 'Подключить *.css'),
            'assets_js' => Yii::t('admin', 'Подключить *.js'),
            'slug' => Yii::t('admin', 'Код'),
        ];
    }

    public function behaviors() {
        return [
            CacheFlush::className()
        ];
    }

}
