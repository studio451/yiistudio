<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<? $form = ActiveForm::begin([
    'enableClientValidation' => true,
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
]); ?>
<? if($model->image) : ?>
    <img src="<?= $model->image ?>" style="width: 848px">
<? endif; ?>
<?= $form->field($model, 'image')->fileInput() ?>
<?= $form->field($model, 'link') ?>
<? if($this->context->module->settings['enableTitle']) : ?>
    <?= $form->field($model, 'title')->textarea() ?>
<? endif; ?>
<? if($this->context->module->settings['enableText']) : ?>
    <?= $form->field($model, 'text')->textarea() ?>
<? endif; ?>
<?= Html::submitButton(Yii::t('admin', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
<? ActiveForm::end(); ?>