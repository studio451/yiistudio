<?

/**
 * Class YandexMoneyPaymentSystem
 * @link https://money.yandex.ru/doc.xml?id=526537
 */

namespace admin\modules\payment\payment_systems;

use Yii;

class Manual extends \admin\modules\payment\models\Payment {

    public function renderCheckoutForm($order, $return = false) {
        return Yii::$app->controller->renderPartial(
                        '@admin/modules/payment/views/manual/form.php', [
                    'payment' => $this,
                    'order' => $order,
                        ], $return
        );
    }

    public function processCheckout($request) {
        return null;
    }

}
