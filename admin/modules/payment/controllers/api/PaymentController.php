<?

namespace admin\modules\payment\controllers\api;

use Yii;
use yii\web\NotFoundHttpException;
use admin\modules\payment\models\Payment;
use admin\modules\shopcart\models\Order;

class PaymentController extends \yii\web\Controller {

    public $enableCsrfValidation = false;

    public function actionProcess($slug) {
        $model = Payment::find()->where(['slug' => $slug])->status(Payment::STATUS_ON)->one();

        if ($model === null) {
            throw new NotFoundHttpException('Pay system not found');
        }

        $class = $model->class;
        $payment = $class::findOne($model->id);

        if ($payment === null) {
            throw new NotFoundHttpException('Pay system not found');
        }
        $result = $payment->processCheckout(Yii::$app->request);

        if ($result instanceof Order) {
            Yii::$app->session->setFlash('success', Yii::t('admin/payment', 'Ваш заказ успешно оплачен'));
            return $this->redirect(['/shopcart/order', 'id' => $result['id'], 'token' => $result['access_token']]);
        } else {
            Yii::$app->session->setFlash('error', Yii::t('admin/payment', 'Ошибка при оплате заказа'));
            return $this->redirect(['/']);
        }
    }

}
