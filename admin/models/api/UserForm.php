<?

namespace admin\models\api;

use Yii;
use yii\base\Model;
use admin\validators\EscapeValidator;

class UserForm extends Model {

    public $name;
    public $phone;
    public $address;

    public function formName() {
        return '';
    }

    public function rules() {
        return [
            [['phone', 'name'], 'required'],
            [['address', 'phone'], 'trim'],
            ['address', 'string', 'max' => 1024],
            ['phone', 'string', 'max' => 32],
            ['phone', 'match', 'pattern' => '/^[\d\s-\+\(\)]+$/'],
            [['address', 'phone'], EscapeValidator::className()],
        ];
    }

    public function attributeLabels() {
        return [
            'phone' => Yii::t('admin', 'Телефон'),
            'name' => Yii::t('admin', 'Имя'),
            'address' => Yii::t('admin', 'Адрес'),
        ];
    }

}
