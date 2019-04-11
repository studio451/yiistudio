<?
namespace admin\modules\feedback;

class FeedbackModule extends \admin\base\Module
{ 
    public $settings = [
        'mailAdminOnNewFeedback' => true,        
        'subjectOnNewFeedback' => 'Новое сообщение из формы обратной связи',
        'templateOnNewFeedback' => '@admin/modules/feedback/mail/ru/new_feedback',
        'mailAdminOnNewCallback' => true,
        'subjectOnNewCallback' => 'Клиент просит перезвонить',
        'templateOnNewCallback' => '@admin/modules/feedback/mail/ru/new_callback',

        'answerTemplate' => '@admin/modules/feedback/mail/ru/answer',
        'answerSubject' => 'Ответ на сообщение из формы обратной связи',
        'answerHeader' => 'Здравствуйте,',
        'answerFooter' => 'С уважением.',

        'enableTitle' => false,
        'enablePhone' => false,
        'enableCaptcha' => false,
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Feedback',
            'ru' => 'Обратная связь',
        ],
        'icon' => 'comment',
        'order_num' => 204,
    ];
}