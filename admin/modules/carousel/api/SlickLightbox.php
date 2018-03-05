<?

namespace admin\modules\carousel\api;

use Yii;
use yii\base\Widget;
use yii\helpers\Json;
use yii\web\View;
use admin\modules\carousel\assets\SlickLightboxAsset;
use admin\modules\carousel\assets\SlickAsset;

class SlickLightbox extends Widget {

    public $clientOptions = [];
    public $clientOptionsSlick = [];
    public $jsPosition = View::POS_READY;
    public $containerId;

    public function init() {
        parent::init();
        ob_start();
    }

    protected function registerClientScript() {
        $view = $this->getView();
        SlickAsset::register($view);
        SlickLightboxAsset::register($view);

        if (!isset($this->containerId) || empty($this->containerId)) {
            $this->containerId = 'slick_lightbox_' . uniqid();
        }
        $id = $this->containerId;

        $this->clientOptionsSlick['slidesToShow'] = 1;
        $this->clientOptionsSlick['slidesToScroll'] = 1;
        $this->clientOptionsSlick['prevArrow'] = '<button type="button" data-role="none" class="slick-prev slick-arrow" aria-label="' . Yii::t('admin/catalog', 'Предыдущий') . '" role="button" style="display: block;"><i class="fa fa-chevron-left"></i></button>';
        $this->clientOptionsSlick['nextArrow'] = '<button type="button" data-role="none" class="slick-next slick-arrow" aria-label="' . Yii::t('admin/catalog', 'Следующий') . '" role="button" style="display: block;"><i class="fa fa-chevron-right"></i></button>';

        $this->clientOptions['layouts'] = ['closeButton' => '<button type="button" class="slick-lightbox-close"><i class="fa fa-close"></i></button>'];
        $this->clientOptions['caption'] = 'caption';
        $this->clientOptions['slick'] = $this->clientOptionsSlick;
        
        $clientOptions = Json::encode($this->clientOptions);

        $js[] = 'jQuery("#' . $id . '").slickLightbox(' . $clientOptions . ');';

        $view->registerJs(implode(PHP_EOL, $js), $this->jsPosition);
    }

    public function run() {
        $content = ob_get_clean();
        $this->registerClientScript();
        return '<div id="' . $this->containerId . '">' . $content . '</div>';
    }

}
