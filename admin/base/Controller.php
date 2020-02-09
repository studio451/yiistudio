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
    public $error = null;
    
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

    /**
     * Write in sessions alert messages
     * @param string $type error or success
     * @param string $message alert body
     */
    public function flash($type, $message) {
        Yii::$app->getSession()->setFlash($type == 'error' ? 'danger' : $type, $message);
    }

    /**
     * Formats response depending on request type (ajax or not)
     * @param string $success
     * @param bool $back go back or refresh
     * @return mixed $result array if request is ajax.
     */
    public function formatResponse($success = '', $back = true, $console = false) {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if ($this->error) {
                return ['result' => 'error', 'error' => $this->error];
            } else {
                $response = ['result' => 'success'];
                if ($success) {
                    if (is_array($success)) {
                        $response = array_merge(['result' => 'success'], $success);
                    } else {
                        $response = array_merge(['result' => 'success'], ['message' => $success]);
                    }
                }
                return $response;
            }
        } else {
            if ($this->error) {
                $this->flash('error', $this->error);
            } else {
                if (is_array($success) && isset($success['message'])) {
                    if ($console) {
                        $this->flash('success', '<pre>' . $success['message'] . '</pre>');
                    } else {
                        $this->flash('success', $success['message']);
                    }
                } elseif (is_string($success)) {
                    if ($console) {
                        $this->flash('console', '<pre>' . $success . '</pre>');
                    } else {
                        $this->flash('success', $success);
                    }
                }
            }
            return $back ? $this->goBack() : $this->refresh();
        }
    }

}
