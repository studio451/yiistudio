<?

namespace admin\assets;

use yii\web\AssetBundle;

/**
 * Chart asset bundle.
 */
class ChartAsset extends AssetBundle {

    public $sourcePath = '@vendor/nnnick/chartjs/dist';    
    public $js = [
        'Chart.min.js',        
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

}
