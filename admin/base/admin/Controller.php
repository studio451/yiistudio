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
    public $error = null;

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
