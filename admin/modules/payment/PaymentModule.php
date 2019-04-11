<?

namespace admin\modules\payment;

class PaymentModule extends \admin\base\Module {

    public $settings = [
        'notifyAdmin' => true,
        'subjectNotifyAdmin' => 'Поступила оплата по заказу №##order_id##',
        'templateNotifyAdmin' => '@admin/modules/payment/mail/ru/notify_admin',
        'subjectNotifyUser' => 'Поступила оплата по заказу №##order_id##',
        'templateNotifyUser' => '@admin/modules/payment/mail/ru/notify_user',
        'frontendShopcartOrderRoute' => '/shopcart/order',
        '__submenu_module' => [
            ['id' => 'a', 'url' => '/admin/payment/a', 'label' => 'Способы оплаты'],
            ['id' => 'checkout', 'url' => '/admin/payment/checkout', 'label' => 'Операции оплаты'],
        ]
    ];
    public static $installConfig = [
        'title' => [
            'en' => 'Payment',
            'ru' => 'Способы оплаты',
        ],
        'icon' => 'money-bill-alt',
        'order_num' => 103,
    ];

}
