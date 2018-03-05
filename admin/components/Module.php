<?

namespace admin\components;

use Yii;
use admin\models\Module as ModuleModel;
use admin\behaviors\AccessBehavior;
/**
 * Base module class. Inherit from this if you are creating your own modules manually
 * @package admin\components
 */
class Module extends \yii\base\Module {

    /** @var string  */
    public $defaultRoute = 'a';

    /** @var array 
     * '__submenu_module' - key for submenu module settings
     */    
    public $settings = [];
    
    /** @var  @todo */
    public $i18n;     
    
    /**
     * Configuration for installation
     * @var array
     */
    public static $installConfig = [
        'title' => [
            'en' => 'Custom Module',
        ],
        'icon' => 'asterisk',
        'order_num' => 0,
    ];

    public function init() {
        parent::init();

        $moduleName = self::getModuleName(self::className());

        //self::registerTranslations($moduleName);
    }

    /**
     * Registers translations connected to the module
     * @param $moduleName string
     */
    public static function registerTranslations($moduleName) {
        $moduleClassFile = '';
        foreach (ModuleModel::findAllActive() as $name => $module) {
            if ($name == $moduleName) {
                $moduleClassFile = (new \ReflectionClass($module->class))->getFileName();
                break;
            }
        }

        if ($moduleClassFile) {
            Yii::$app->i18n->translations['admin/' . $moduleName . '*'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'ru-RU',
                'basePath' => dirname($moduleClassFile) . DIRECTORY_SEPARATOR . 'messages',
                'fileMap' => [
                    'admin/' . $moduleName => 'admin.php',
                ]
            ];
        }
    }

    /**
     * Module name getter
     *
     * @param $namespace
     * @return string|bool
     */
    public static function getModuleName($namespace) {
        foreach (ModuleModel::findAllActive() as $module) {
            $moduleClassPath = preg_replace('/[\w]+$/', '', $module->class);
            if (strpos($namespace, $moduleClassPath) !== false) {
                return $module->name;
            }
        }
        return false;
    }
    
    public function getSubmenu() {
        return [];
    }
}
