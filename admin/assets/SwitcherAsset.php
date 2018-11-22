<?
namespace admin\assets;

class SwitcherAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@admin/media/jquery.switcher';
    

    public $css = [
        'switcher.css',
    ];

    public $js = [
    	'switcher.js',
    ];
    
    public $depends = ['yii\web\JqueryAsset'];    
    
}