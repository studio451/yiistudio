<?

namespace admin\base\api;

use Yii;

/**
 * Base controller for yiistudio use for public pages
 * @package admin\base\api
 */
class Controller extends \admin\base\Controller {

    public function beforeAction($action) {
        if (parent::beforeAction($action)) {
            if ($this->setReturnUrl) {
                if (Yii::$app->request->isGet && $this->checkRedirectAcceptable()) {
                    Yii::$app->user->setReturnUrl(Yii::$app->request->url);
                }
            }
            return true;
        }
        return false;
    }

}
