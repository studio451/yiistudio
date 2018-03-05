<?

namespace admin\modules\carousel\api;

use yii\base\Widget;
use yii\helpers\Json;
use yii\web\View;
use admin\modules\carousel\assets\SlickAsset;
use admin\modules\carousel\assets\SlickLightboxAsset;

class Slick extends Widget {

    public $clientOptions = [];
    public $jsPosition = View::POS_READY;
    public $containerId;
    public $lightbox = false;

    public function init() {
        parent::init();
        ob_start();
    }

    protected function registerClientScript() {
        $view = $this->getView();

        SlickAsset::register($view);
        if ($this->lightbox) {
            SlickLightboxAsset::register($view);
        }

        $options = Json::encode($this->clientOptions);

        if (!isset($this->containerId) || empty($this->containerId)) {
            $this->containerId = 'slick_carousel_' . uniqid();
        }
        $id = $this->containerId;


        $js[] = 'jQuery("#' . $id . '").slick(' . $options . ');';

        if ($this->lightbox) {
            $this->clientOptions['slidesToShow'] = 1;
            $this->clientOptions['slidesToScroll'] = 1;
            $layouts['closeButton'] = '<button type="button" class="slick-lightbox-close"><i class="fa fa-close"></i></button>';
            $options = Json::encode($this->clientOptions);
            $layouts = Json::encode($layouts);
            $js[] = 'jQuery("#' . $id . '").slickLightbox({itemSelector: "> div > div > a",mainitem:"#mainitem",layouts:' . $layouts . ', slick:' . $options . '});';
        }
        $js[] = 'jQuery("#' . $id . '").parent().css({"height":"auto"});';

        $view->registerJs(implode(PHP_EOL, $js), $this->jsPosition);
    }

    public function run() {
        $content = ob_get_clean();
        $this->registerClientScript();
        return '<div id="' . $this->containerId . '">' . $content . '</div>';
    }

}
