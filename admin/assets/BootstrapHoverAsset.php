<?

namespace admin\assets;

class BootstrapHoverAsset extends \yii\web\AssetBundle {

    public $sourcePath = '@vendor/cwspear/bootstrap-hover-dropdown';
    public $depends = ['yii\web\JqueryAsset'];

    public function init() {
        $this->js[] = 'bootstrap-hover-dropdown.min.js';
    }

}
