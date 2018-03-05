<?

namespace admin\assets;

use yii\web\AssetBundle;


class ImageSwapAsset extends AssetBundle
{
    public $sourcePath = '@admin/assets/imageSwap';

    public $css = [
    ];

    public $js = [
    	'js/imageSwap.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];
}
