<?

namespace admin\assets;

use Yii;

class AdminModuleAsset extends \admin\components\Asset {

    public $depends = [
        'admin\assets\AdminAsset',
    ];
    
    public function init()
    {
        $module = Yii::$app->controller->module->id;
        
        $this->sourcePath = '@admin/modules/' . $module . '/media';
        
        parent::init();
        
        $js = 'js/' .Yii::$app->controller->id . '/' . Yii::$app->controller->action->id . '/' . 'main.js';
        $path = Yii::getAlias($this->sourcePath) . '/' . $js;
        
        if (is_file($path)) {
            $this->js[] = $js;
        }
    }

    }
