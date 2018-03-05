<?
namespace admin\assets;

class HelpersAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@admin/media';
    public $css = [ 
        'css/helpers.css',      
    ];
    public $js = [        
        'js/helpers.js',        
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = array(
        'position' => \yii\web\View::POS_END
    );
}
