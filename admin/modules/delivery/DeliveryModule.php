<?
namespace admin\modules\delivery;

class DeliveryModule extends \admin\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'Delivery',
            'ru' => 'Службы доставки',
        ],
        'icon' => 'truck',
        'order_num' => 102,
    ];
}
