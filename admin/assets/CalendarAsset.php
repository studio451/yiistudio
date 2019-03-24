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
        'dist/fullcalendar.min.css',
    ];
    public $js = [        
        'dist/fullcalendar.min.js',  
        'dist/gcal.min.js',
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
            $this->js[] = 'dist/locale/ru.js';
        }else
        {
            $this->js[] = 'dist/locale/en-gb.js';
        }
        
    }

}
