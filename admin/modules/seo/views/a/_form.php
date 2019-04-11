<?

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?
$form = ActiveForm::begin([
            'enableAjaxValidation' => true,
            'options' => ['class' => 'model-form']
        ]);
?>
<?= $form->field($model, 'slug') ?>
<br>
<br>
##title## - <?= Yii::t('admin/seo', 'Заголовок Api объекта') ?><br>
##strtolower()## - <?= Yii::t('admin/seo', 'Привести к нижнему регистру') ?>
<br>
<br>
<br>
<?= $form->field($model, 'title')->textarea() ?>
<?= $form->field($model, 'h1')->textarea() ?>
<?= $form->field($model, 'description')->textarea() ?>
<?= $form->field($model, 'keywords')->textarea() ?>
<?= Html::submitButton(Yii::t('admin', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
<? ActiveForm::end(); ?>