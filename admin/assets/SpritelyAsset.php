<?

namespace admin\assets;

use yii\web\AssetBundle;

class SpritelyAsset extends AssetBundle {

    public $sourcePath = '@vendor/bower/spritely';   

    public $js = [
        'src/jquery.spritely.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
