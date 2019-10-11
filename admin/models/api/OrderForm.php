<?

namespace admin\models\api;

use Yii;
use yii\base\Model;
use admin\validators\EscapeValidator;

class OrderForm extends Model {

    public $phone;
    public $name;     
    public $address;
    public $comment;

    public function formName() {
        return '';
    }

    public function rules() {
        return [
            [['phone', 'name'], 'required'],
            [['address'], 'required', 'on' => 'need_address'],
            [['name', 'address', 'phone', 'comment'], 'trim'],
            ['name', 'string', 'max' => 32],
            ['address', 'string', 'max' => 1024],
            ['phone', 'string', 'max' => 32],
            ['phone', 'match', 'pattern' => '/^[\d\s-\+\(\)]+$/'],
            ['comment', 'string', 'max' => 1024],
            [['name', 'address', 'phone', 'comment'], EscapeValidator::className()],
        ];
    }

    public function attributeLabels() {
        return [
            'phone' => Yii::t('admin', 'Телефон'),
            'name' => Yii::t('admin', 'Имя'),
            'address' => Yii::t('admin', 'Адрес'),
            'comment' => Yii::t('admin', 'Комментарий'),
        ];
    }

}
