<?

namespace admin\assets;

use yii\web\AssetBundle;


class ScrollUpAsset extends AssetBundle
{
    public $sourcePath = '@admin/assets/scrollUp';

    public $css = [
    ];

    public $js = [
    	'js/jquery.scrollUp.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];
}
