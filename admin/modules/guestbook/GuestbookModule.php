<?
namespace admin\modules\guestbook;

class GuestbookModule extends \admin\components\Module
{
    public $settings = [
        'enableTitle' => false,
        'enableEmail' => true,
        'preModerate' => false,
        'enableCaptcha' => false,
        'mailAdminOnNewPost' => true,
        'subjectOnNewPost' => 'New message in the guestbook.',
        'templateOnNewPost' => '@admin/modules/guestbook/mail/en/new_post',
        'frontendGuestbookRoute' => '/guestbook',
        'subjectNotifyUser' => 'Your post in the guestbook answered',
        'templateNotifyUser' => '@admin/modules/guestbook/mail/en/notify_user'
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Guestbook',
            'ru' => 'Гостевая книга',
        ],
        'icon' => 'book',
        'order_num' => 207,
    ];
}