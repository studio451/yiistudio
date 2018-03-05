<?

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$module = $this->context->module->id;
?>
<?
$form = ActiveForm::begin([
            'enableAjaxValidation' => true,
            'options' => ['class' => 'model-form']
        ]);
?>
<div class='row'>
    <div class="col-sm-12">
        <?= $form->field($model, 'class') ?>
    </div>
</div>
<div class='row'>
    <div class="col-sm-12">
        <?= $form->field($model, 'priority') ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= Html::submitButton(Yii::t('admin', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<? ActiveForm::end(); ?>