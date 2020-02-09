<?

//Yii Studio 
//https://yiistudio.ru

namespace admin;

use Yii;
use yii\web\View;
use yii\base\Application;
use yii\base\BootstrapInterface;
use admin\models\Module;
use admin\behaviors\AccessBehavior;

class AdminModule extends \yii\base\Module implements BootstrapInterface {

    const VERSION = '1.0.1';
    const NAME = 'Yii Studio';

    public $settings;
    public $activeModules;
    public $defaultRoute = 'a';

    public function behaviors() {

        if (INSTALLED) {
            return [
                'AccessBehavior' => [
                    'class' => AccessBehavior::className(),
                    'login_url' => '/user/login',
                    'rules' =>
                        [
                        'admin' => [['allow' => true, 'roles' => ['SuperAdmin'],],],
                        'admin/logs' => [['allow' => true, 'roles' => ['SuperAdmin'],],],
                        'admin/modules' => [['allow' => true, 'roles' => ['SuperAdmin'],],],
                        'admin/photos' => [['allow' => true, 'roles' => ['SuperAdmin'],],],
                        'admin/redactor' => [['allow' => true, 'roles' => ['SuperAdmin'],],],
                        'admin/session' => [['allow' => true, 'roles' => ['SuperAdmin'],],],
                        'admin/settings' => [['allow' => true, 'roles' => ['SuperAdmin'],],],
                        'admin/system' => [['allow' => true, 'roles' => ['SuperAdmin'],],],
                        'admin/tags' => [['allow' => true, 'roles' => ['SuperAdmin'],],],
                        'admin/translate' => [['allow' => true, 'roles' => ['SuperAdmin'],],],
                        'admin/user-permissions' => [['allow' => true, 'roles' => ['SuperAdmin'],],],
                        'admin/users' => [['allow' => true, 'roles' => ['SuperAdmin'],],],
                        'admin/rbac' => [['allow' => true, 'roles' => ['SuperAdmin'],],],
                        'admin/dump' => [['allow' => true, 'roles' => ['SuperAdmin'],],],
                        'admin/user' => [['actions' => ['logout'], 'allow' => true, 'roles' => ['@'],],],
                    ],
                ],
            ];
        } else {
            return [];
        }
    }

    public function init() {
        parent::init();

        if (Yii::$app->cache === null) {
            throw new \yii\web\ServerErrorHttpException('Необходимо настроить компонент кэширования');
        }
        if (INSTALLED) {
            $this->activeModules = Module::findAllActive();

            $modules = [];
            foreach ($this->activeModules as $name => $module) {
                $modules[$name]['class'] = $module->class;
                if (is_array($module->settings)) {
                    $modules[$name]['settings'] = $module->settings;
                }

                //Регистрация переводов для модулей
                Yii::$app->i18n->translations['admin/' . $name . '*'] = [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'ru-RU',
                    'basePath' => dirname($modules[$name]['class']) . DIRECTORY_SEPARATOR . 'messages',
                ];
            }
            $this->setModules($modules);

            if (APP_CONSOLE == 'true') {
                //Секция для инициализации консольного приложения
            } else {
                define('LIVE_EDIT', !Yii::$app->user->isGuest && Yii::$app->session->get('admin_live_edit'));
            }
        }
    }

    public function bootstrap($app) {
        if (INSTALLED) {
            if (!$app->user->isGuest && strpos($app->request->pathInfo, 'admin') === false) {
                if (strpos($app->request->pathInfo, 'debug') === false) {
                    if (Yii::$app->user->can('admin')) {
                        $app->on(Application::EVENT_BEFORE_REQUEST, function () use ($app) {
                            $request = Yii::$app->request;
                            if (!$request->isAjax) {
                                $app->getView()->on(View::EVENT_BEGIN_BODY, [$this, 'renderToolbar']);
                            }
                        });
                    }
                }
            }
        }
    }

    public function renderToolbar() {
        $view = Yii::$app->getView();
        echo $view->render('@admin/views/layouts/toolbar.php');
    }

    public static function renderPromo() {
        echo '<span class="yiistudio-promo">'.Yii::t('admin', 'Мы используем') . ' <a href="https://yiistudio.ru" target="_blank" title="https://yiistudio.ru">' . self::NAME . '</a><span>';
    }

    public static function getDsnAttribute($name, $dsn) {
        if (preg_match('/' . $name . '=([^;]*)/', $dsn, $match)) {
            return $match[1];
        } else {
            return null;
        }
    }

    public static function checkDbConnection() {
        try {
            Yii::$app->db->open();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function checkInstalled() {
        try {
            return Yii::$app->db->createCommand("SHOW TABLES LIKE 'admin_%'")->query()->count() > 0 ? true : false;
        } catch (\Exception $e) {
            return false;
        }
    }

}
