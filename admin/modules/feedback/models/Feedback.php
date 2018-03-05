<?

namespace admin\modules\feedback\models;

use Yii;
use admin\behaviors\CalculateNotice;
use admin\helpers\Mail;
use admin\models\Setting;
use admin\validators\ReCaptchaValidator;
use admin\validators\EscapeValidator;
use yii\helpers\Url;

class Feedback extends \admin\components\ActiveRecord {

    const STATUS_NEW = 0;
    const STATUS_VIEW = 1;
    const STATUS_ANSWERED = 2;
    const FLASH_KEY = 'feedback_send_result';
    const SCENARIO_FEEDBACK = 'feedback';
    const SCENARIO_CALLBACK = 'callback';
    const TYPE_FEEDBACK = 0;
    const TYPE_CALLBACK = 1;

    public $reCaptcha;

    public static function tableName() {
        return 'admin_module_feedback';
    }

    public function rules() {
        return [
                [['name', 'email', 'text'], 'required', 'on' => self::SCENARIO_FEEDBACK],
                [['name', 'phone'], 'required', 'on' => self::SCENARIO_CALLBACK],
                [['name', 'email', 'phone', 'title', 'text'], 'trim'],
                [['name', 'title', 'text'], EscapeValidator::className()],
                ['title', 'string', 'max' => 128],
                ['email', 'email'],
                ['phone', 'match', 'pattern' => '/^[\d\s-\+\(\)]+$/'],
                ['reCaptcha', ReCaptchaValidator::className(), 'when' => function($model) {
                    return $model->isNewRecord && Yii::$app->getModule('admin')->activeModules['feedback']->settings['enableCaptcha'];
                }],
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->ip = Yii::$app->request->userIP;
                $this->time = time();
                $this->status = self::STATUS_NEW;
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $this->notifyAdmin($this->scenario);
        }
    }

    public function attributeLabels() {
        return [
            'email' => 'E-mail',
            'name' => Yii::t('admin', 'Имя'),
            'title' => Yii::t('admin', 'Заголовок'),
            'text' => Yii::t('admin', 'Текст'),
            'answer_subject' => Yii::t('admin/feedback', 'Тема сообщения'),
            'answer_text' => Yii::t('admin', 'Текст'),
            'phone' => Yii::t('admin/feedback', 'Телефон'),
            'reCaptcha' => Yii::t('admin', 'Проверка')
        ];
    }

    public function behaviors() {
        return [
            'cn' => [
                'class' => CalculateNotice::className(),
                'callback' => function() {
                    return self::find()->status(self::STATUS_NEW)->count();
                }
            ]
        ];
    }

    public function notifyAdmin($scenario) {
        $settings = Yii::$app->getModule('admin')->activeModules['feedback']->settings;
        if ($scenario == self::SCENARIO_FEEDBACK) {
            if (!$settings['mailAdminOnNewFeedback']) {
                return false;
            }
            return Mail::send(
                            Setting::get('contact_email'), $settings['subjectOnNewFeedback'], $settings['templateOnNewFeedback'], ['feedback' => $this, 'link' => Url::to(['/admin/feedback/a/view', 'id' => $this->primaryKey], true)]
            );
        }
        if ($scenario == self::SCENARIO_CALLBACK) {
            if (!$settings['mailAdminOnNewCallback']) {
                return false;
            }
            return Mail::send(
                            Setting::get('contact_email'), $settings['subjectOnNewCallback'], $settings['templateOnNewCallback'], ['callback' => $this, 'link' => Url::to(['/admin/feedback/a/view', 'id' => $this->primaryKey], true)]
            );
        }
    }

    public function sendAnswer() {
        $settings = Yii::$app->getModule('admin')->activeModules['feedback']->settings;

        if ($this->email) {
            return Mail::send(
                            $this->email, $this->answer_subject, $settings['answerTemplate'], ['feedback' => $this], ['replyToAdminEmail' => true]
            );
        }
    }

}
