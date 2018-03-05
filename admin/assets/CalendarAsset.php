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
        'lib/moment.min.js',
        'fullcalendar.min.js',        
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

    public function init() {
        parent::init();
                
        if(\Yii::$app->language == 'ru-RU')
        {
            $this->js[] = 'locale/ru.js';
        }else
        {
            $this->js[] = 'locale/en-gb.js';
        }
        
    }

}
