<?

namespace admin\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * Calendar asset bundle.
 */
class CalendarAsset extends AssetBundle {

    public $sourcePath = '@vendor/bower/fullcalendar';
    public $css = [
        'fullcalendar.min.css',
    ];
    public $js = [        
        'distr/fullcalendar.min.js',  
        'distr/gcal.min.js',
    ];
    public $depends = [
        'admin\assets\MomentAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

    public function init() {
        parent::init();
                
        if(\Yii::$app->language == 'ru-RU')
        {
            $this->js[] = 'distr/locale/ru.js';
        }else
        {
            $this->js[] = 'distr/locale/en-gb.js';
        }
        
    }

}
