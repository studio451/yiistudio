<?

namespace admin\modules\feedback\controllers\api;

use Yii;
use admin\modules\feedback\models\Feedback;

class FeedbackController extends \yii\web\Controller {

    public function actionIndex() {
        $model = new Feedback;
        $model->scenario = Feedback::SCENARIO_FEEDBACK;

        $request = Yii::$app->request;

        if ($model->load($request->post()) && $model->validate()) {
            $flag = $model->save();

            if ($flag) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success' => true, 'text' => 'Ваше собщение успешно отправлено!'];
                }
                $returnUrl = $request->post('successUrl');
                Yii::$app->session->setFlash('success', Yii::t('admin/feedback', 'Ваше сообщение успешно отправлено!'));
            } else {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success' => false];
                }
                $returnUrl = $request->post('errorUrl');
                Yii::$app->session->setFlash('error', Yii::t('admin/feedback', 'Произошла ошибка при отправке письма'));
            }
            return $this->redirect($returnUrl);
        } else {
            if (Yii::$app->request->isAjax) {
                $model->name = Yii::$app->user->identity->name;
                $model->phone = Yii::$app->user->identity->phone;
                return $this->renderAjax('@admin/modules/feedback/views/api/feedback/index', [
                            'model' => $model,
                ]);
            } else {
                return $this->redirect(['/']);
            }
        }
    }

}
