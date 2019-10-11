<?

namespace admin\assets;

use yii\web\AssetBundle;

class SpritelyAsset extends AssetBundle {

    public $sourcePath = '@vendor/bower-asset/spritely';   

    public $js = [
        'src/jquery.spritely.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
