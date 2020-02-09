<?

namespace admin\base\admin;

use Yii;

/**
 * Base admin controller for use yiistudio for admin panel
 * @package admin\base\admin
 */
class Controller extends \admin\base\Controller {

    public $enableCsrfValidation = false;
    public $layout = '@admin/views/layouts/main';


    public function beforeAction($action) {
        if (parent::beforeAction($action)) {
            if ($this->setReturnUrl && $action->id == 'index') {
                if (Yii::$app->request->isGet && $this->checkRedirectAcceptable()) {
                    Yii::$app->user->setReturnUrl(Yii::$app->request->url);
                }
            }
            return true;
        }
        return false;
    }

   

}
