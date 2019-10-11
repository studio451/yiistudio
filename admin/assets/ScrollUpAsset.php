<?

namespace admin\assets;

use yii\web\AssetBundle;


class ScrollUpAsset extends AssetBundle
{
    public $sourcePath = '@vendor/bower-asset/scrollup';

    public $js = [
    	'dist/jquery.scrollUp.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];
}
