<?

namespace admin\models\users;

use Yii;
use yii\base\Model;

class FilterForm extends Model {

    public $email;

    public function formName() {
        return '';
    }

    public function rules() {
        return [
            [['email'], 'safe'],            
        ];
    }

    public function attributeLabels() {
        return [
            'email' => Yii::t('admin', 'E-mail'),
        ];
    }

    public function parse($filters = []) {

        if ($this->email) {
            $filters['email'] = $this->email;
        } 
        
        return $filters;
    }

}
