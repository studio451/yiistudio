<?
namespace admin\modules\guestbook\controllers;

use Yii;
use admin\modules\guestbook\models\Guestbook as GuestbookModel;

class SendController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new GuestbookModel;
        $model->scenario = 'send';
        $request = Yii::$app->request;

        if ($model->load($request->post())) {
            $returnUrl = $model->save() ? $request->post('successUrl') : $request->post('errorUrl');
            return $this->redirect($returnUrl);
        } else {
            return $this->redirect(['/']);
        }
    }
}