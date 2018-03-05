<?

namespace admin\assets;

use yii\web\AssetBundle;

/**
 * Calendar asset bundle.
 */
class AnimateNumberAsset extends AssetBundle {

    public $sourcePath = '@vendor/bower/jquery.animatenumber';   

    public $js = [
        'jquery.animateNumber.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
