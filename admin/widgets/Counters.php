<?

namespace admin\widgets;

use Yii;
use yii\base\Widget;
use admin\models\Setting;

class Counters extends Widget {

    public function init() {
        parent::init();
    }

    public function run() {
        if (Setting::get('counter_yandexMetrikaId')) {
            echo $this->render('counter_yandex_metrika', [
                'id' => Setting::get('counter_yandexMetrikaId')
            ]);
        }
        if (Setting::get('counter_googleAnalyticsId')) {
            echo $this->render('counter_google_analytics', [
                'id' => Setting::get('counter_googleAnalyticsId')
            ]);
        }
    }

}
