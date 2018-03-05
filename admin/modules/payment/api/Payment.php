<?

namespace admin\modules\payment\api;

use Yii;
use yii\base\Exception;
use admin\modules\payment\models\Payment as PaymentModel;

/**
 * Payment module API
 * @package admin\modules\payment\api
 *
 */
class Payment extends \admin\components\API {

    public function api_renderCheckoutForm($orderObject) {
        if ($orderObject->model->isNotPaid()) {
            $paymentModel = PaymentModel::find()->where(['id' => $orderObject->model->payment_id])->status(PaymentModel::STATUS_ON)->one();
            $class = $paymentModel->class;
            if($class) {
                $implementation = $class::findOne($paymentModel->id);
                if ($implementation) {
                    return $implementation->renderCheckoutForm($orderObject->model);
                }
            }
            throw new Exception("Не найдена платежная система");
        } 
    }

}
