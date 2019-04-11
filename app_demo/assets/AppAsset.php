<?

namespace app_demo\assets;

class AppAsset extends \admin\base\Asset {

    public $sourcePath = '@app/media';
    public $css = [
        'css/main.css',
        'css/bunner.css',
    ];
    public $js = [
        'js/main.js'
    ];
    public $depends = [
        'admin\assets\FontAwesomeAsset',
        'admin\assets\HelpersAsset',
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
