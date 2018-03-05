<?

namespace admin\components;

use Yii;

/**
 * Base API controller component
 * @package admin\components
 */
class APIController extends \yii\web\Controller {

    public function beforeAction($action) {
        if (parent::beforeAction($action)) {
            Yii::$app->user->setReturnUrl(Yii::$app->request->url);
            return true;
        }        
    }    

}
