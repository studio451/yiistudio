<?
namespace admin\base;

use Yii;
/**
 * Base asset class. 
 * Include main.js file by route path
 * @package admin\base
 */
class Asset extends \yii\web\AssetBundle
{
    public $jsOptions = array(
        'position' => \yii\web\View::POS_END
    );
    
    public function init()
    {
        parent::init();
        
        $js = 'js/' .Yii::$app->controller->id . '/' . Yii::$app->controller->action->id . '/' . 'main.js';
        $path = Yii::getAlias($this->sourcePath) . '/' . $js;
        
        if (is_file($path)) {
            $this->js[] = $js;
        }
    }
}
