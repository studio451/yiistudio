<?

namespace admin\models\api;

use Yii;
use yii\base\Model;
use admin\models\User;
use admin\helpers\Mail;
use admin\models\Setting;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model {

    public $email;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                ['email', 'trim'],
                ['email', 'required'],
                ['email', 'email'],
                ['email', 'exist',
                'targetClass' => '\admin\models\User',
                'filter' => ['status' => User::STATUS_ON],
                'message' => 'There is no user with such email.'
            ],
        ];
    }

    public function attributeLabels() {
        return [
            'email' => Yii::t('admin', 'E-mail'),
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function notifyUser() {
        /* @var $user User */
        $user = User::findOne([
                    'status' => User::STATUS_ON,
                    'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return Mail::send(
                    $this->email, Setting::get('subjectNotifyUserPasswordResetToken'), Setting::get('templateNotifyUserPasswordResetToken'), [
                    'user' => $user,
        ]);
    }

}
