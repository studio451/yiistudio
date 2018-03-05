<?

namespace admin\components;

use Yii;
use yii\helpers\Url;

/**
 * Base admin controller component
 * @package admin\components
 */
class Controller extends \yii\web\Controller {

    public $enableCsrfValidation = false;
    public $layout = '@admin/views/layouts/main';
    public $error = null;

    /**
     * Check authentication, and root rights for actions
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function beforeAction($action) {
        if (!parent::beforeAction($action))
            return false;

        if (Yii::$app->user->isGuest) {
            Yii::$app->user->setReturnUrl(Yii::$app->request->url);
            Yii::$app->getResponse()->redirect(['/admin/user/login'])->send();
            return false;
        } else {
            if ($action->id === 'index') {
                $this->setReturnUrl();
            }

            return true;
        }
    }

    /**
     * Write in sessions alert messages
     * @param string $type error or success
     * @param string $message alert body
     */
    public function flash($type, $message) {
        Yii::$app->getSession()->setFlash($type == 'error' ? 'danger' : $type, $message);
    }

    public function back() {
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Set return url for module in sessions
     * @param mixed $url if not set, returnUrl will be current page
     */
    public function setReturnUrl($url = null) {
        Yii::$app->getSession()->set($this->module->id . '_return', $url ? Url::to($url) : Url::current());
    }

    /**
     * Get return url for module from session
     * @param mixed $defaultUrl if return url not found in sessions
     * @return mixed
     */
    public function getReturnUrl($defaultUrl = null) {
        return Yii::$app->getSession()->get($this->module->id . '_return', $defaultUrl ? Url::to($defaultUrl) : Url::to('/admin/' . $this->module->id));
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
            return $back ? $this->back() : $this->refresh();
        }
    }

    

}
