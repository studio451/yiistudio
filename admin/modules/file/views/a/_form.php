<?

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use admin\modules\seo\widgets\SeoTextForm;
?>
<?
$form = ActiveForm::begin([
            'enableAjaxValidation' => true,
            'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
        ]);
?>
<?= $form->field($model, 'title') ?>
<?= $form->field($model, 'file')->fileInput() ?>
<? if (!$model->isNewRecord) : ?>
    <div><a href="<?= $model->file ?>" target="_blank"><?= basename($model->file) ?></a> (<?= Yii::$app->formatter->asShortSize($model->size, 2) ?>)</div>
    <br>
<? endif; ?>

<?= $form->field($model, 'slug') ?>
<?= SeoTextForm::widget(['model' => $model]) ?>

<?= Html::submitButton(Yii::t('admin', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
<? ActiveForm::end(); ?>