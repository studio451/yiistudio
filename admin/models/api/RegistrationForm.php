<?

namespace admin\models\api;

use Yii;
use yii\base\Model;
use admin\models\User;
use admin\helpers\Mail;
use admin\models\Setting;

/**
 * Registration form
 */
class RegistrationForm extends Model {

    public $email;
    public $name;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                ['email', 'trim'],
                ['email', 'required'],
                ['email', 'email'],
                ['email', 'string', 'max' => 255],
                ['email', 'unique', 'targetClass' => '\admin\models\User', 'message' => Yii::t('admin', 'Пользователь с таким email уже существует')],
                ['name', 'string', 'max' => 255],
                ['name', 'trim'],
                ['name', 'required'],
                ['password', 'required', 'on' => !Setting::get('generatePasswordRegistration')],
                ['password', 'string', 'min' => 8],
        ];
    }

    public function attributeLabels() {
        return [
            'email' => Yii::t('admin', 'E-mail'),
            'name' => Yii::t('admin', 'Имя'),
            'password' => Yii::t('admin', 'Пароль'),
        ];
    }

    /**
     * Registration user.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function registration() {
        if (!$this->validate()) {
            return null;
        }
        if (empty($this->password)) {
            $this->password = substr(uniqid(md5(rand()), true), 0, 8);
        }

        $user = new User();
        $user->email = $this->email;
        $user->password = $this->password;

        $user->data = [
            'name' => $this->name,
        ];

        if ($user->save()) {
            $this->notifyUser();
            return $user;
        } else {
            return null;
        }
    }

    /**
     * Sends an email about user registration.
     *
     * @return boolean whether the email was send
     */
    public function notifyUser() {
        return Mail::send(
                        $this->email, Setting::get('subjectNotifyUserRegistration'), Setting::get('templateNotifyUserRegistration'), [
                    'email' => $this->email,
                    'password' => $this->password,
                    'contact_url' => Setting::get('contact_url'),
                        ], ['replyToAdminEmail' => true]
        );
    }

}
