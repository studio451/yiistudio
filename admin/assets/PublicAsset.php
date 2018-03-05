<?
namespace admin\assets;

class PublicAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@admin/media';
    public $css = [
        'css/public.css',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}
