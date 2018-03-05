<?
namespace admin\assets;

class ToolbarAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@admin/media';
    public $css = [
        'css/toolbar.css',
    ];
    public $js = [
        'js/toolbar.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'admin\assets\SwitcherAsset',
        'admin\assets\FontAwesomeAsset'
    ];
}
