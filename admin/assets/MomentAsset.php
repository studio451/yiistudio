<?
namespace admin\assets;

class MomentAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@vendor/bower/moment';
    public $js = [        
        'moment.js',        
    ];   
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}