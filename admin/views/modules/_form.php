<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<? $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'class') ?>
<?= $form->field($model, 'title') ?>
<?= $form->field($model, 'icon') ?>
<?= Html::submitButton(Yii::t('admin', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
<? ActiveForm::end(); ?>