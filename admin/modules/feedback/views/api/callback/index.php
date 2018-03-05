<?

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\View;
?>

<?
$form = ActiveForm::begin([
            'id' => 'callback-form',
            'enableClientValidation' => true,
            'action' => Url::to(['/callback'])
        ]);
?>
<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'phone') ?>
<?= Html::submitButton(Yii::t('admin', 'Отправить'), ['class' => 'btn btn-primary']) ?>
<? ActiveForm::end(); ?>
<?

$js = <<<SCRIPT
_g_ajax_form_submit("#callback-form", {func: function (data) { $("#modal").find("#modalContent").html(data.text); }});
SCRIPT;
$this->registerJs($js, View::POS_READY);
?>