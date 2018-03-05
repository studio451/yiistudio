<?

namespace admin\assets;

use yii\web\AssetBundle;

class SpritelyAsset extends AssetBundle {

    public $sourcePath = '@vendor/bower/jquery.spritely';   

    public $js = [
        'jquery.spritely-0.6.8.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
