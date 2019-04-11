<?

namespace admin\models\api;

use Yii;
use admin\models\User;
use admin\models\Setting;
use admin\base\ActiveRecord;

use admin\validators\EscapeValidator;

class LoginForm extends ActiveRecord
{
    const CACHE_KEY = 'SIGNIN_TRIES';

    public $password;
    
    private $_user = false;

    public static function tableName()
    {
        return 'admin_loginform';
    }

    public function rules()
    {
        return [
            // email and password are both required
            [['email', 'password'], 'required'],
            ['email', 'email'],
            [['email', 'password'], EscapeValidator::className()],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('admin', 'E-mail'),
            'password' => Yii::t('admin', 'Пароль'),
            'remember' => Yii::t('admin', 'Запомнить')
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('admin', 'Неверный логин или пароль'));
            }
        }
    }

    public function login()
    {
        $cache = Yii::$app->cache;

        if(($tries = (int)$cache->get(self::CACHE_KEY)) > 5){
            $this->addError('email', Yii::t('admin', 'Повторите вход через 5 минут'));
            return false;
        }

        $this->ip = $_SERVER['REMOTE_ADDR'];
        $this->user_agent = $_SERVER['HTTP_USER_AGENT'];
        $this->time = time();

        if ($this->validate()) {           
            $this->success = 1;
        } else {
            $this->success = 0;
            $cache->set(self::CACHE_KEY, ++$tries, 300);
        }
        $this->insert(false);

        return $this->success ? Yii::$app->user->login($this->getUser(), Setting::get('auth_time') ?: null ) : false;

    }

    /**
     * Finds user by [[email]]
     *
     * @return User|null
     */
    protected function getUser() {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->email);
        }

        return $this->_user;
    }
}
