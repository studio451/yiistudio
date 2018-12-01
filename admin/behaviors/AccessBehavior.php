<?

namespace admin\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\di\Instance;
use yii\base\Module;
use yii\web\User;
use yii\web\ForbiddenHttpException;

class AccessBehavior extends AttributeBehavior {

    public $rules = [];
    public $redirect_url = false;
    public $login_url = false;
    private $_rules = [];

    public function events() {
        return [
            Module::EVENT_BEFORE_ACTION => 'beforeAction',
        ];
    }

    public function beforeAction($event) {
        
        $route = Yii::$app->getRequest()->resolve();
        $action = $event->action;
        $module = $event->action->controller->module;

        //Проверяем права по конфигу
        $this->createRule();
        $user = Instance::ensure(Yii::$app->user, User::className());
        $request = Yii::$app->getRequest();
        $action = $event->action;

        if (!$this->cheсkByRule($action, $user, $request)) {
            //И по AuthManager
            if (!$this->checkPermission($route)) {
                //Если задан $login_url и пользователь не авторизован
                if (Yii::$app->user->isGuest && $this->login_url) {
                    Yii::$app->response->redirect($this->login_url)->send();
                    exit();
                }
                //Если задан $redirect_url
                if ($this->redirect_url) {
                    Yii::$app->response->redirect($this->redirect_url)->send();
                    exit();
                } else {
                    throw new ForbiddenHttpException(Yii::t('admin', 'У вас недостаточно прав для выполнения данного действия!'));
                }
            }
        }
    }

    protected function createRule() {
        foreach ($this->rules as $controller => $rule) {
            foreach ($rule as $singleRule) {
                if (is_array($singleRule)) {
                    $option = [
                        'controllers' => [$controller],
                        'class' => 'yii\filters\AccessRule'
                    ];
                    $this->_rules[] = Yii::createObject(array_merge($option, $singleRule));
                }
            }
        }
    }

    protected function cheсkByRule($action, $user, $request) {
        foreach ($this->_rules as $rule) {
            if ($rule->allows($action, $user, $request)) {
                return true;
            }
        }
        return false;
    }

    protected function checkPermission($route) {
        //$route[0] - is the route, $route[1] - is the associated parameters
        $routes = $this->createPartRoutes($route);
        foreach ($routes as $routeVariant) {
            if (Yii::$app->user->can($routeVariant)) {
                if ((count($routes) > 1 && $routeVariant == 'admin')) {
                    continue;
                }
                return true;
            }
        }
        return false;
    }

    protected function createPartRoutes($route) {
        //$route[0] - is the route, $route[1] - is the associated parameters

        $routePathTmp = explode('/', $route[0]);
        $result = [];
        $routeVariant = array_shift($routePathTmp);
        $result[] = $routeVariant;

        foreach ($routePathTmp as $routePart) {
            if ($routePart != '') {
                $routeVariant .= '/' . $routePart;
                $result[] = $routeVariant;
            }
        }
        return $result;
    }

}
