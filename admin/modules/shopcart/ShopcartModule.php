<?
namespace admin\modules\shopcart;

class ShopcartModule extends \admin\base\Module
{
    public $settings = [
        'notifyAdmin' => true,
        'subjectNotifyAdmin' => 'Новый заказ №##order_id##',
        'templateNotifyAdmin' => '@admin/modules/shopcart/mail/ru/notify_admin',
        'subjectNotifyUser' => 'Заказ №##order_id##',
        'templateNotifyUser' => '@admin/modules/shopcart/mail/ru/notify_user',
        'frontendShopcartOrderRoute' => '/shopcart/order',
        'frontendShopcartRoute' => '/shopcart',
        'enableName' => true,
        'enablePhone' => true,
        'templateShopcartIndex' => '@admin/modules/shopcart/views/api/shopcart/index',
        'templateShopcartOrder' => '@admin/modules/shopcart/views/api/shopcart/order',
        'templateShopcartOrders' => '@admin/modules/shopcart/views/api/shopcart/orders',
        'templateShopcartSuccess' => '@admin/modules/shopcart/views/api/shopcart/success',
        'templateShopcartSuccessGuest' => '@admin/modules/shopcart/views/api/shopcart/success_guest',
        'templateShopcartSuccessPayment' => '@admin/modules/shopcart/views/api/shopcart/success_payment',
        'templateShopcartFast' => '@admin/modules/shopcart/views/api/shopcart/fast',
        'modelExportOrderToExcel' => 'admin\modules\shopcart\export\OrderToExcel',
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Orders',
            'ru' => 'Заказы',
        ],
        'icon' => 'shopping-cart',
        'order_num' => 101,
    ];
}