<?

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\View;
?>

<?

$settings = Yii::$app->getModule('admin')->activeModules['feedback']->settings;
$form = ActiveForm::begin([
            'id' => 'feedback-form',
            'enableClientValidation' => true,
            'action' => Url::to(['/feedback'])
        ]);
?>
<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'email') ?>
<?

if ($settings['enablePhone'])
    echo $form->field($model, 'phone');
if ($settings['enableTitle'])
    echo $form->field($model, 'title');
?>

<?= $form->field($model, 'text')->textarea() ?>
<?

if ($settings['enableCaptcha'])
    echo $form->field($model, 'reCaptcha')->widget(ReCaptcha::className());
?>
<?= Html::submitButton(Yii::t('admin', 'Отправить'), ['class' => 'btn btn-primary']) ?>
<? ActiveForm::end(); ?>
<?

$js = <<<SCRIPT
_g_ajax_form_submit("#feedback-form", {func: function (data) { $("#modal").find("#modalContent").html(data.text); }});
SCRIPT;
$this->registerJs($js, View::POS_READY);
?>