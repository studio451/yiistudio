<?
namespace admin\assets;

class MomentAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@vendor/bower/moment';
    public $js = [        
        'min/moment-with-locales.min.js',        
    ];   
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}