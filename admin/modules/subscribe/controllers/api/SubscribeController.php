<?

namespace admin\modules\subscribe\controllers\api;

use Yii;
use yii\widgets\ActiveForm;
use admin\modules\subscribe\models\Subscriber;

class SubscribeController extends \yii\web\Controller {

    public function actionSend() {
        $model = new Subscriber;
        $request = Yii::$app->request;

        if ($model->load($request->post())) {
            if ($request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                if ($model->save()) {
                    $returnUrl = $request->post('successUrl');
                    Yii::$app->session->setFlash('success', Yii::t('admin/subscribe', 'Вы успешно подписаны на рассылку!'));
                } else {
                    $returnUrl = $request->post('errorUrl');
                    Yii::$app->session->setFlash('error', Yii::t('admin/subscribe', 'Ошибка при оформлении подписки!'));
                }
                $returnUrl = $model->save() ? $request->post('successUrl') : $request->post('errorUrl');
                return $this->redirect($returnUrl);
            }
        } else {
            return $this->redirect(['/']);
        }
    }

    public function actionUnsubscribe($email) {
        if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Subscriber::deleteAll(['email' => $email]);
            echo '<h1>' . Yii::t('admin/subscribe', 'Вы успешно отписаны от рассылки!') . '</h1>';
        } else {
            throw new \yii\web\BadRequestHttpException(Yii::t('admin/subscribe', 'E-mail не найден!'));
        }
    }

}
