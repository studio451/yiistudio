<?
namespace admin\assets;

class AnimateAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@bower/animate';
    public $depends = ['yii\web\JqueryAsset'];

    public $css = [
        'animate.css',
    ];
}