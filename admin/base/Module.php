<?

namespace admin\base;

use Yii;
use admin\models\Module as ModuleModel;

/**
 * Base module class. Inherit from this if you are creating your own modules manually
 * @package admin\base
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
        'type' => 'ADMIN',
        'icon' => 'asterisk',
        'order_num' => 0,
    ];

    public function init() {
        parent::init();        
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
