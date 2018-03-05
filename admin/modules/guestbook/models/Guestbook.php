<?

namespace admin\modules\guestbook\models;

use Yii;
use admin\behaviors\CalculateNotice;
use admin\helpers\Mail;
use admin\models\Setting;
use admin\validators\ReCaptchaValidator;
use admin\validators\EscapeValidator;
use yii\helpers\Url;

class Guestbook extends \admin\components\ActiveRecord {

    const STATUS_OFF = 0;
    const STATUS_ON = 1;
    const FLASH_KEY = 'guestbook_send_result';

    public $reCaptcha;

    public static function tableName() {
        return 'admin_module_guestbook';
    }

    public function rules() {
        return [
            [['name', 'text'], 'required'],
            [['name', 'title', 'text'], 'trim'],
            [['name', 'title', 'text'], EscapeValidator::className()],
            ['email', 'email'],
            ['title', 'string', 'max' => 128],
            ['reCaptcha', ReCaptchaValidator::className(), 'on' => 'send', 'when' => function() {
                    return Yii::$app->getModule('admin')->activeModules['guestbook']->settings['enableCaptcha'];
                }],
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->ip = Yii::$app->request->userIP;
                $this->time = time();
                $this->new = 1;
                $this->status = Yii::$app->getModule('admin')->activeModules['guestbook']->settings['preModerate'] ? self::STATUS_OFF : self::STATUS_ON;
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $this->notifyAdmin();
        }
    }

    public function attributeLabels() {
        return [
            'name' => Yii::t('admin', 'Имя'),
            'title' => Yii::t('admin', 'Заголовок сообщения'),
            'email' => 'E-mail',
            'text' => Yii::t('admin', 'Текст сообщения'),
            'answer' => Yii::t('admin/guestbook', 'Ответ'),
            'reCaptcha' => Yii::t('admin', 'ReCapcha')
        ];
    }

    public function behaviors() {
        return [
            'cn' => [
                'class' => CalculateNotice::className(),
                'callback' => function() {
                    return self::find()->where(['new' => 1])->count();
                }
                    ]
                ];
            }

            public function notifyAdmin() {
                $settings = Yii::$app->getModule('admin')->activeModules['guestbook']->settings;

                if (!$settings['mailAdminOnNewPost']) {
                    return false;
                }
                return Mail::send(
                                Setting::get('contact_email'), $settings['subjectOnNewPost'], $settings['templateOnNewPost'], [
                            'post' => $this,
                            'link' => Url::to(['/admin/guestbook/a/view', 'id' => $this->primaryKey], true)
                                ], ['replyToAdminEmail' => true]
                );
            }

            public function notifyUser() {
                $settings = Yii::$app->getModule('admin')->activeModules['guestbook']->settings;

                return Mail::send(
                                $this->email, $settings['subjectNotifyUser'], $settings['templateNotifyUser'], [
                            'post' => $this,
                            'link' => Url::to([$settings['frontendGuestbookRoute']], true)
                                ], ['replyToAdminEmail' => true]
                );
            }

        }
        