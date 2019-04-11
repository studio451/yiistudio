<?

namespace admin\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * User model
 */
class User extends \admin\base\ActiveRecordData implements \yii\web\IdentityInterface {

    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    public $password = '';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'admin_users';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['email'], 'required'],
                ['email', 'email'],
                ['password', 'required', 'on' => 'create'],
                ['password', 'safe'],
                ['access_token', 'default', 'value' => null],
                ['data', 'safe'],
                ['status', 'default', 'value' => self::STATUS_ON],
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->generateAuthKey();
                $this->setPasswordHash($this->password);
            } else {
                if ($this->password != '') {
                    $this->setPasswordHash($this->password);
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function beforeDelete() {
        if (parent::beforeDelete()) {
            if ($this->id == Yii::$app->user->identity->id) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'email' => Yii::t('admin', 'E-mail'),
            'role' => Yii::t('admin', 'Роль'),
            'status' => Yii::t('admin', 'Статус'),
            'password' => Yii::t('admin', 'Пароль'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id]);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByUsername($email) {
        return static::findOne(['email' => $email]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                    'password_reset_token' => $token,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        return $timestamp + 3600 >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPasswordHash($password) {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        return static::findOne(['access_token' => $token]);
    }

    public function getAvatar() {
        return $this->data['avatar'];
    }

    public function getName() {
        return $this->data['name'];
    }

    public function getPhone() {
        return $this->data['phone'];
    }

    public function getAddress() {
        return $this->data['address'];
    }

    public function setAvatar($value) {
        $this->data['avatar'] = $value;
    }

    public function setName($value) {
        $this->data['name'] = $value;
    }

    public function setPhone($value) {
        $this->data['phone'] = $value;
    }

    public function setAddress($value) {
        $this->data['address'] = $value;
    }

    public function getDataSchema() {
        return [
            'avatar' => ['title' =>  Yii::t('admin', 'Аватар'), 'value' => ''],
            'name' => ['title' =>  Yii::t('admin', 'Имя'), 'value' => ''],
            'phone' => ['title' =>  Yii::t('admin', 'Телефон'), 'value' => ''],
            'address' => ['title' =>  Yii::t('admin', 'Адрес'), 'value' => ''],
        ];
    }

}
