<?

namespace admin\controllers\api;

use Yii;

/**
 * Session controller
 */
class SessionController extends \admin\components\APIController {

    public function actionUpdateJson() {
        $key = Yii::$app->request->post('key');
        $value = Yii::$app->request->post('value');
        if (!$value) {
            Yii::$app->getSession()->remove($key);
        } else {
            Yii::$app->getSession()->set($key, $value);
        }
    }

}
