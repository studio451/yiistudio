<?

namespace admin\modules\feedback\controllers\api;

use Yii;
use yii\web\Response;
use admin\modules\feedback\models\Feedback;

class CallbackController extends \yii\web\Controller {

    public function actionIndex() {
        $model = new Feedback();
        $model->scenario = Feedback::SCENARIO_CALLBACK;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
           
            $model->type = Feedback::TYPE_CALLBACK;
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->save()) {
                return ['success' => true, 'text' => 'Ваше собщение успешно отправлено! В ближайшее время мы свяжемся с Вами.'];
            } else {
                return ['success' => false];
            }
        } else {
            $model->name = Yii::$app->user->identity->name;
            $model->phone = Yii::$app->user->identity->phone;
            return $this->renderAjax('@admin/modules/feedback/views/api/callback/index', [
                        'model' => $model,
            ]);
        }
    }

}
