<?

namespace admin\assets;

class AdminLteAsset extends \yii\web\AssetBundle
{

    public $sourcePath = '@admin/media/adminlte';
    public $css = [
        'css/AdminLTE.css',
    ];
    public $js = [
        'js/app.js',
    ];
    public $depends = [
        'admin\assets\FontAwesomeAsset',
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    
    public function init()
    {
        if (YII_DEBUG) {
            $this->css[] = 'css/skins/_all-skins.css';
        } else {
            $this->css[] = 'css/skins/_all-skins.min.css';
        }
    }
}
