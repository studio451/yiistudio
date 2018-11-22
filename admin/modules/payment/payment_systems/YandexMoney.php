<?
namespace admin\modules\payment\payment_systems;

use Yii;
use yii\helpers\Url;
use admin\helpers\Mail;
use admin\models\Setting;
use admin\modules\shopcart\models\Order;
use admin\modules\payment\payment_systems\helpers\YandexNotification;
use admin\modules\payment\models\Checkout;

/**
 * Class YandexMoneyPaymentSystem
 * @link https://money.yandex.ru/doc.xml?id=526991
 */
class YandexMoney extends \admin\modules\payment\models\Payment {

    public function getDataSchema() {
        return [
            'receiver' => ['title' => Yii::t('admin/payment', 'Номер кошелька в Яндекс.Деньгах, на который нужно зачислять деньги отправителей'), 'value' => '41001xxxxxxxxxxxx'],
            'notification_secret' => ['title' => Yii::t('admin/payment', 'Секретный код'), 'value' => ''],
            'successURL' => ['title' => Yii::t('admin/payment','URL-адрес для редиректа после совершения перевода'), 'value' => ''],
            'paymentTypeAC' => ['title' => Yii::t('admin/payment','Включить оплату с банковской карты'), 'value' => true],
            'paymentTypePC' => ['title' => Yii::t('admin/payment','Включить оплату из кошелька в Яндекс.Деньгах'), 'value' => true],
            'paymentTypeMC' => ['title' => Yii::t('admin/payment','Включить оплату с баланса мобильного'), 'value' => false],
            'quickpay-form' => [
                'title' => 'Определяет тип транзакции',
                'value' => 'shop',
                'options' => [
                    ['value' => 'shop', 'title' => Yii::t('admin/payment','Универсальная форма')],
                    ['value' => 'donate', 'title' => Yii::t('admin/payment','«Благотворительная» ("Донат") форма')],
                    ['value' => 'small', 'title' => Yii::t('admin/payment','Кнопка Яндекс.Деньги')],
                ],
            ],
            'need-fio' => ['title' => Yii::t('admin/payment','Запрос ФИО отправителя'), 'value' => false],
            'need-email' => ['title' => Yii::t('admin/payment','Запрос электронной почты отправителя'), 'value' => false],
            'need-phone' => ['title' => Yii::t('admin/payment','Запрос телефона отправителя'), 'value' => false],
            'need-address' => ['title' => Yii::t('admin/payment','Запрос адреса отправителя'), 'value' => false],
            'button-text' => ['title' => Yii::t('admin/payment','Текст на кнопке оплаты'), 'value' => 'Оплатить заказ'],
        ];
    }

    public function renderCheckoutForm($order, $return = false) {
        return Yii::$app->controller->renderPartial(
                        '@admin/modules/payment/views/yandexmoney/form.php', [
                    'settings' => $this->data,
                    'order' => $order,
                        ], $return
        );
    }

    public function processCheckout($request) {
        $settings = $this->data;

        $params = [
            'notification_type' => $request->post('notification_type'),
            'operation_id' => $request->post('operation_id'),
            'amount' => $request->post('amount'),
            'currency' => $request->post('currency'),
            'datetime' => $request->post('datetime'),
            'sender' => $request->post('sender'),
            'codepro' => $request->post('codepro'),
            'notification_secret' => $settings['notification_secret'],
            'label' => $request->post('label'),
        ];

        $label = $request->post('label');
        $label = explode(":", $label);
        $contact_name = $label[0];

        if ($contact_name != Setting::get('contact_name')) {
            $this->errorProcessCheckout(Yii::t('admin/payment','Не совпадает название магазина'));
            return false;
        }

        $order = Order::findOne((int) $label[1]);
        if ($order === null) {
            $this->errorProcessCheckout(Yii::t('admin/payment','Заказ с указанным номером не найден'));
            return false;
        }


        $sha1_hash = $this->getSha1_hash($params);
        if ($sha1_hash !== $request->post('sha1_hash')) {
            $this->errorProcessCheckout(Yii::t('admin/payment','Не совпадает контрольная сумма'), $order);
            return false;
        }

        if ((int) $order->totalCost !== (int) $request->post('withdraw_amount')) {
            $this->errorProcessCheckout(Yii::t('admin/payment','Сумма оплаты не совпадает с суммой заказа'), $order);
            return false;
        }

        if ($order->isPaid()) {
            $checkouts = Checkout::find()->where(['order_id' => $order->id])->orderBy(['time' => SORT_ASC])->all();
            if (count($checkouts)) {
                foreach ($checkouts as $checkout) {
                    $checkout_request = (json_decode($checkout->request));
                    if ($checkout_request->sha1_hash == $request->post('sha1_hash')) {
                        $this->successProcessCheckout(Yii::t('admin/payment','Повторное уведомление о платеже Yandex.Money'), $order, false);
                        return false;
                    }
                }
            }

            $this->errorProcessCheckout(Yii::t('admin/payment','Заказ уже был оплачен'), $order);
            return false;
        }
        if ($request->post('unaccepted') == 'true') {
            if ($request->post('codepro') == 'true') {
                $this->errorProcessCheckout(Yii::t('admin/payment','Перевод еще не зачислен. Получателю нужно освободить место в кошельке или использовать код протекции'), $order);
                return false;
            } else {
                $this->errorProcessCheckout(Yii::t('admin/payment','Перевод еще не зачислен. Получателю нужно освободить место в кошельке'), $order);
                 return false;
            }
        }

        $this->payOrder($order);

        return $order;
    }

    private function getSha1_hash($params) {
        return sha1(
                implode('&', $params)
        );
    }

    public function renderCheckoutTestForm() {

        return Yii::$app->controller->renderPartial(
                        '@admin/modules/payment/views/yandexmoney/form-test.php', [
                    'url' => Url::to(['/payment/process', 'slug' => $this->slug], true),
        ]);
    }

    public function test($order_id, $amount) {

        $notification = new YandexNotification();

        //$notification->datetime = date("Y-m-d\TH:i:sP", 0);
        $notification->codepro = false;
        $notification->label = Setting::get('contact_name') . ':' . $order_id;
        $notification->amount = $amount;
        $notification->withdraw_amount = $amount;

        $notification->dispatch(Url::to(['/payment/process', 'slug' => $this->slug], true), $this->data['notification_secret']);
    }

}
