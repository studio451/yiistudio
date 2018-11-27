<?
namespace admin\assets;

class DateTimePickerAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@vendor/bower/eonasdan-bootstrap-datetimepicker';
    public $js = [        
        'build/js/bootstrap-datetimepicker.min.js',        
    ];
    public $css = [        
        'build/css/bootstrap-datetimepicker.css',        
    ];
    public $depends = [
        'admin\assets\MomentAsset',
        'yii\web\JqueryAsset',        
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}