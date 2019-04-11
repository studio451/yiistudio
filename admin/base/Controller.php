<?

namespace admin\base;

use Yii;

/**
 * Base controller for yiistudio use
 * @package admin\base
 */
class Controller extends \yii\web\Controller {

    public $setReturnUrl = true;
    public $acceptableRedirectTypes = ['text/html', 'application/xhtml+xml'];

    public function beforeAction($action) {
        if (parent::beforeAction($action)) {
            if (!INSTALLED) {
                if (\admin\AdminModule::checkInstalled()) {
                    $this->redirect(['/admin/api/install/finish'])->send();
                } else {
                    $this->redirect(['/admin/api/install'])->send();
                }
                return false;
            }

            return true;
        }
        return false;
    }

    protected function checkRedirectAcceptable() {
        $acceptableTypes = Yii::$app->getRequest()->getAcceptableContentTypes();
        if (empty($acceptableTypes) || count($acceptableTypes) === 1 && array_keys($acceptableTypes)[0] === '*/*') {
            return true;
        }

        foreach ($acceptableTypes as $type => $param) {
            if (in_array($type, $this->acceptableRedirectTypes, true)) {
                return true;
            }
        }

        return false;
    }

}
