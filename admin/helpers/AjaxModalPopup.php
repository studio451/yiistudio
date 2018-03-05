<?

namespace admin\helpers;

use Yii;
use yii\bootstrap\Modal;
use yii\helpers\Html;

class AjaxModalPopup {

    public static function renderModal() {
        Modal::begin([
            'headerOptions' => ['id' => 'modalHeader'],
            'id' => 'modal',
            'size' => Modal::SIZE_LARGE,
        ]);
        echo "<div id='modalContent'></div>";
        Modal::end();
        echo '<script>$("#modal").on("hidden.bs.modal", function (e) {document.getElementById("modalContent").innerHTML = "";})</script>';
    }

    public static function a($value, $title, $url, $class = "", $options = []) {
        return Html::a($value, 'javascript:void(0);', array_merge(['title' => $title, 'data-url' => $url, 'class' => 'ajaxModalPopup ' . $class], $options));
    }

    public static function button($value, $title, $url, $class = "", $options = []) {
        return Html::button($value, array_merge(['title' => $title, 'data-url' => $url, 'class' => 'ajaxModalPopup ' . $class], $options));
    }

}
