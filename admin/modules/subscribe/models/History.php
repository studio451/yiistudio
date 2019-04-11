<?

namespace admin\modules\subscribe\models;

use Yii;

class History extends \admin\base\ActiveRecord {

    const STATUS_OFF = 0;
    const STATUS_ON = 1;
    
    public static function tableName() {
        return 'admin_module_subscribe_history';
    }

    public function rules() {
        return [
            [['subject', 'body'], 'required'],
            [['subject','mailing_list'], 'trim'],
            ['sent', 'number', 'integerOnly' => true],
            ['time', 'default', 'value' => time()],
            ['status', 'default', 'value' => self::STATUS_OFF],
        ];
    }

    public function attributeLabels() {
        return [
            'subject' => Yii::t('admin/subscribe', 'Тема'),
            'body' => Yii::t('admin/subscribe', 'Текст письма'),
            'mailing_list' => Yii::t('admin/subscribe', 'Список рассылки'),
        ];
    }

}
