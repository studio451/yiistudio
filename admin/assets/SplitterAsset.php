<?

namespace admin\assets;

use yii\web\AssetBundle;

class SplitterAsset extends AssetBundle {

    public $sourcePath = '@vendor/bower-asset/jquery.splitter/js';   

    public $js = [
        'jquery.splitter-0.14.0.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
