<?
namespace admin\modules\faq\models;

use Yii;
use admin\behaviors\CacheFlush;
use admin\behaviors\SortableModel;

class Faq extends \admin\base\ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    const CACHE_KEY = 'admin_module_faq';

    public static function tableName()
    {
        return 'admin_module_faq';
    }

    public function rules()
    {
        return [
            [['question','answer'], 'required'],
            [['question', 'answer'], 'trim'],
            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_ON],
        ];
    }

    public function attributeLabels()
    {
        return [
            'question' => Yii::t('admin/faq', 'Вопрос'),
            'answer' => Yii::t('admin/faq', 'Ответ'),
        ];
    }

    public function behaviors()
    {
        return [
            CacheFlush::className(),
            SortableModel::className()
        ];
    }
}