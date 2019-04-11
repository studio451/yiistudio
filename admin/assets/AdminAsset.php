<?

namespace admin\assets;

class AdminAsset extends \admin\base\Asset {

    public $sourcePath = '@admin/media';
    public $css = [
        'css/admin.css',
    ];
    public $js = [
        'js/admin.js'
    ];
    public $depends = [
        'admin\assets\AdminLteAsset',
        'admin\assets\HelpersAsset',
        'admin\assets\SwitcherAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

    }
