<?
namespace admin\assets;

class PhotosAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@admin/media/photos';
    public $css = [
        'photos.css',
    ];
    public $js = [
        'photos.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
