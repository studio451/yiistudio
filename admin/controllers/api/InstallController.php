<?

namespace admin\controllers\api;

use Yii;
use admin\helpers\Data;
use yii\web\ServerErrorHttpException;
use admin\helpers\WebConsole;
use admin\models\api\InstallForm;
use admin\behaviors\SortableModel;
use admin\models\Module;
use admin\models\Setting;
use admin\models\User;
use admin\modules\catalog\models\Category;

class InstallController extends \yii\web\Controller {

    public $layout = 'public';

    public function actionIndex() {
        if (!\admin\AdminModule::checkDbConnection()) {
            if (YII_ENV_PROD) {
                $configFile = str_replace(Yii::getAlias('@webroot'), '', Yii::getAlias('@app')) . '/config/db.php';
            } else {
                $configFile = str_replace(Yii::getAlias('@webroot'), '', Yii::getAlias('@app')) . '/config/db_dev.php';
            }

            $dbName = \admin\AdminModule::getDsnAttribute('dbname', Yii::$app->db->dsn);

            return $this->showError(Yii::t('admin', 'Нет соединения с базой данных <b>' . $dbName . '</b>!<br> Если база данных <b>' . $dbName . '</b> не создана, создайте ее.<br> Также проверьте правильность настроек подключения к базе данных: <b>' . $configFile . '</b>'));
        }
        if (INSTALLED) {
            return $this->showError(Yii::t('admin', \admin\AdminModule::NAME . ' уже установлена.<br> Если вы хотите переустановить ' . \admin\AdminModule::NAME . ' установите значение константы INSTALLED в ' . Yii::getAlias('@webroot/index.php') . ' равным false!'));
        }
        $installForm = new InstallForm();

        if ($installForm->load(Yii::$app->request->post())) {
            $this->createUploadsDir();

            WebConsole::migrate('ADMIN');

            $this->insertSettings($installForm);

            WebConsole::migrate('APP');

            $this->installModules();

            //Создаем корневую категорию catalog
            $catalog = new Category();
            $catalog->slug = 'catalog';
            $catalog->title = Yii::t('admin', 'Каталог');
            $catalog->status = Category::STATUS_ON;
            $catalog->attachBehavior('sortable', SortableModel::className());
            $catalog->makeRoot();

            $user = new User();
            $user->email = $installForm->admin_email;
            $user->password = $installForm->admin_password;
            $user->save();

            Yii::$app->user->login($user, 0);

            WebConsole::rbacMigrate();
            WebConsole::rbacInit(Yii::$app->user->identity->id);

            Yii::$app->cache->flush();

            return $this->redirect(['/admin/api/install/finish']);
        } else {

            $installForm->admin_email = '';
            $installForm->admin_password = '';

            $installForm->contact_email = 'info@' . Yii::$app->request->serverName;
            $installForm->contact_url = Yii::$app->request->serverName;
            $installForm->contact_name = Yii::$app->request->serverName;

            $installForm->contact_addressLocality = Yii::t('admin', 'Москва');
            $installForm->contact_openingHours = Yii::t('admin', '9.00-18.00 (без выходных)');
            $installForm->contact_openingHoursISO86 = 'Mo-Su 9:00-18:00';

            return $this->render('index', [
                        'model' => $installForm
            ]);
        }
    }

    public function actionFinish() {
        return $this->render('finish');
    }

    private function showError($text) {
        return $this->render('error', ['error' => $text]);
    }

    private function createUploadsDir() {
        $uploadsDir = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . 'uploads';
        $uploadsDirExists = file_exists($uploadsDir);
        if (($uploadsDirExists && !is_writable($uploadsDir)) || (!$uploadsDirExists && !mkdir($uploadsDir, 0777))) {
            throw new ServerErrorHttpException(Yii::t('admin', 'Папка ' . $uploadsDir . ' не создана. Проверьте разрешения'));
        }
    }

    private function insertSettings($installForm) {

        $installSettings = [
            'admin_email' => $installForm->admin_email,
            'recaptcha_key' => $installForm->recaptcha_key,
            'recaptcha_secret' => $installForm->recaptcha_secret,
            'contact_email' => $installForm->contact_email,
            'contact_url' => $installForm->contact_url,
            'contact_name' => $installForm->contact_name,
            'contact_addressLocality' => $installForm->contact_addressLocality,
            'contact_streetAddress' => $installForm->contact_streetAddress,
            'contact_openingHours' => $installForm->contact_openingHours,
            'contact_openingHoursISO86' => $installForm->contact_openingHoursISO86,
            'contact_telephone' => $installForm->contact_telephone,
            'subjectNotifyUserPasswordResetToken' => Yii::t('admin', 'Сброс пароля для ') . $installForm->contact_url,
            'subjectNotifyUserRegistration' => Yii::t('admin', 'Регистрация на сайте ') . $installForm->contact_url,
        ];

        Setting::updateSettings($installSettings);
    }

    private function installModules() {
        //Установка системных модулей
        foreach (glob(ADMIN_PATH . DIRECTORY_SEPARATOR . 'modules/*') as $module) {
            $moduleName = basename($module);
            $this->installModule('ADMIN',$moduleName);
        }
        //Установка модулей приложения
        foreach (glob(APP_PATH . DIRECTORY_SEPARATOR . 'modules/*') as $module) {
            $moduleName = basename($module);

            $this->installModule('APP',$moduleName);
        }
    }

    private function installModule($type, $moduleName) {

        $language = Data::getLocale();

        WebConsole::migrate($type, $moduleName);
      
        if ($type == 'ADMIN') {
            $moduleClass = 'admin\modules\\' . $moduleName . '\\' . ucfirst($moduleName) . 'Module';
        }
        if ($type == 'APP') {
            $moduleClass = APP_NAME . '\modules\\' . $moduleName . '\\' . ucfirst($moduleName) . 'Module';
        }
        
        $moduleConfig = $moduleClass::$installConfig;
        
        $module = new Module([
            'name' => $moduleName,
            'class' => $moduleClass,
            'title' => !empty($moduleConfig['title'][$language]) ? $moduleConfig['title'][$language] : $moduleConfig['title']['en'],
            'type' => $type,
            'icon' => $moduleConfig['icon'],
            'settings' => Yii::createObject($moduleClass, [$moduleName])->settings,
            'order_num' => $moduleConfig['order_num'],
            'status' => Module::STATUS_ON,
        ]);
        $module->save();
    }

}
