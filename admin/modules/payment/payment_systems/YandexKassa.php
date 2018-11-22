<?
namespace admin\modules\payment\payment_systems;

use Yii;

/**
 * Class YandexMoneyPaymentSystem
 * @link https://tech.yandex.ru/money/doc/payment-solution/About-docpage/
 */
class YandexKassa extends \admin\modules\payment\models\Payment {

   public function getDataSchema() {
        return [
            'shopid' => ['title' => 'Идентификатор магазина', 'value' => 'asdasdadada'],
            'scid' => ['title' => 'Номер витрины', 'value' => '123456'],
            'password' => ['title' => 'Пароль магазина', 'value' => '123456'],
            'type' => ['title' => 'Способ оплаты',
                'value' => 'PC',
                'options' => [
                    ['value' => 'PC', 'title' => 'Со счета в Яндекс.Деньгах'],
                    ['value' => 'AC', 'title' => 'С банковской карты'],
                    ['value' => 'GP', 'title' => 'По коду через терминал'],
                    ['value' => 'MC', 'title' => 'Со счета мобильного телефона'],
                    ['value' => 'WM', 'title' => 'С кошелька Webmoney'],
                ],
            ],
            'mode' => [
                'title' => 'Режим оплаты',
                'options' => [
                    ['value' => 'demomoney', 'title' => 'Тестовый режим'],
                    ['value' => 'money', 'title' => 'Рабочий режим'],
                ],
            ]
        ];
    }

    public function renderCheckoutForm($order, $return = false) {
        return Yii::$app->controller->renderPartial(
                        '@admin/modules/payment/views/yandexmoney/form.php', [
                    'payment' => $this,
                    'order' => $order,
                        ], $return
        );
    }

    public function processCheckout($request) {
        $settings = $this->getPaymentSystemSettings();

        $params = [
            'action' => $request->getParam('action'),
            'orderSumAmount' => $request->getParam('orderSumAmount'),
            'orderSumCurrencyPaycash' => $request->getParam('orderSumCurrencyPaycash'),
            'orderSumBankPaycash' => $request->getParam('orderSumBankPaycash'),
            'shopId' => $settings['shopid'],
            'invoiceId' => $request->getParam('invoiceId'),
            'customerNumber' => $request->getParam('customerNumber'),
            'password' => $settings['password'],
        ];

        /* @var $order Order */
        $order = Order::model()->findByPk($request->getParam('orderNumber'));

        if ($order === null) {
            $message = Yii::t('admin/payment', 'Заказ не существует (не найден)');
            Yii::log($message, CLogger::LEVEL_ERROR);

            $this->showResponse($params, $message, 200);
        }

        if ($params['action'] === 'PaymentSuccess') {
            return $order;
        }

        if ($order->isPaid()) {
            $message = Yii::t('admin/payment', 'Заказ #{n} уже был оплачен', $order->getPrimaryKey());
            Yii::log($message, CLogger::LEVEL_ERROR);

            $this->showResponse($params, $message, 200);
        }

        if ($this->getOrderCheckSum($params) !== $request->getParam('md5')) {
            $message = Yii::t('admin/payment', 'Не совпадает контрольная сумма');
            Yii::log($message, CLogger::LEVEL_ERROR);

            $this->showResponse($params, $message, 200);
        }

        if ((float) $order->getTotalPriceWithDelivery() !== (float) $params['orderSumAmount']) {
            $message = Yii::t('admin/payment', 'Не совпадает сумма оплаты');
            Yii::log($message, CLogger::LEVEL_ERROR);

            $this->showResponse($params, $message, 200);
        }

        if ($params['action'] === 'checkOrder') {
            $this->showResponse($params);
        }

        if ($params['action'] === 'paymentAviso' && $order->pay($payment)) {
            Yii::log(
                    Yii::t('admin/payment', 'Заказ #{n} был успешно оплачен', $order->getPrimaryKey()
                    ), CLogger::LEVEL_INFO
            );

            $this->showResponse($params);
        }
    }

    /**
     * @param array $params
     * @param string $message
     * @param int $code
     */
    private function showResponse(array $params, $message = '', $code = 0) {
        header("Content-type: text/xml; charset=utf-8");

        $writer = new XMLWriter;
        $writer->openURI('php://output');
        $writer->startDocument('1.0', 'UTF-8');

        $writer->startElement($params['action'] . 'Response');

        $writer->startAttribute('performedDatetime');
        $writer->text(date('c'));
        $writer->endAttribute();

        $writer->startAttribute('code');
        $writer->text($code);
        $writer->endAttribute();

        $writer->startAttribute('invoiceId');
        $writer->text($params['invoiceId']);
        $writer->endAttribute();

        $writer->startAttribute('message');
        $writer->text($message);
        $writer->endAttribute();

        $writer->startAttribute('shopId');
        $writer->text($params['shopId']);
        $writer->endAttribute();

        $writer->endElement();

        $writer->endDocument();

        Yii::$app->end();
    }

    /**
     * Generate order checksum
     *
     * @param array $params
     * @return string
     */
    private function getOrderCheckSum($params) {
        return strtoupper(
                md5(
                        implode(';', $params)
                )
        );
    }

}
