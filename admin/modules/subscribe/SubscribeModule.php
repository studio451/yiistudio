<?
namespace admin\modules\subscribe;

class SubscribeModule extends \admin\base\Module
{
    public $settings = [
        'templateNotifyUser' => '@admin/modules/subscribe/mail/ru/notify_user',
    ];
    
    public static $installConfig = [
        'title' => [
            'en' => 'E-mail subscribe',
            'ru' => 'E-mail рассылка',
        ],
        'icon' => 'envelope',
        'order_num' => 210,
    ];
}