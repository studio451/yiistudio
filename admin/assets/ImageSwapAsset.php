<?

namespace admin\assets;

use yii\web\AssetBundle;


class ImageSwapAsset extends AssetBundle
{
    public $sourcePath = '@admin/media/imageSwap';

    public $css = [
    ];

    public $js = [
    	'imageSwap.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];
}
