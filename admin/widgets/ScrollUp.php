<?

namespace admin\widgets;

use admin\assets\ScrollUpAsset;

class ScrollUp extends \yii\base\Widget {

    /**
     *
     * @var array options array to pass jquery plugin 
     */
    public $options = [];

    public function init() {
        parent::init();

        $this->registerAssets();
    }

    public function run() {
        $view = $this->getView();

        $options = (isset($this->options)) ? json_encode($this->options) : json_encode([]);

        $js = <<<SCRIPT
jQuery.scrollUp($options);
SCRIPT;

        $view->registerJs($js);
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets() {
        $view = $this->getView();

        ScrollUpAsset::register($view);

        $this->options['scrollText'] = '<i class="fa fa-angle-up"></i>';
    }

}
