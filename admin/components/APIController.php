<?

namespace admin\components;

use Yii;

/**
 * Base API controller component
 * @package admin\components
 */
class APIController extends \yii\web\Controller {

    public function beforeAction($action) {
        if (!INSTALLED) {
            if (\admin\AdminModule::checkInstalled()) {                
                $this->redirect(['/admin/api/install/finish'])->send();
            } else {
                
                                
                $this->redirect(['/admin/api/install'])->send();
            }
            return false;
        }
                
        if (parent::beforeAction($action)) {
            if(Yii::$app->request->isGet)
            {
                Yii::$app->user->setReturnUrl(Yii::$app->request->url);
            }
            return true;
        }     
        
    
    }    

}
