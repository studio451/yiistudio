<?

namespace admin\modules\feedback\controllers\api;

use Yii;
use admin\modules\feedback\models\Feedback as FeedbackModel;

class FeedbackController extends \yii\web\Controller {

    public function actionIndex() {
        $model = new FeedbackModel;
        $model->scenario = FeedbackModel::SCENARIO_FEEDBACK;

        $request = Yii::$app->request;

        if ($model->load($request->post())) {
            if ($model->save()) {
                $returnUrl = $request->post('successUrl');
                Yii::$app->session->setFlash('success', Yii::t('admin/feedback', 'Ваше сообщение успешно отправлено!'));
            } else {
                $returnUrl = $request->post('errorUrl');
                Yii::$app->session->setFlash('error', Yii::t('admin/feedback', 'Произошла ошибка при отправке письма'));
            }
            return $this->redirect($returnUrl);
        } else {
            return $this->redirect(['/']);
        }
    }

}
