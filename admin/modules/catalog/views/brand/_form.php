<?

use yii\helpers\Html;
use yii\helpers\Url;
use admin\helpers\Image;
use yii\widgets\ActiveForm;
use admin\modules\seo\widgets\SeoTextForm;
use admin\widgets\Redactor;
?>
<?
$form = ActiveForm::begin([
            'enableAjaxValidation' => true,
            'options' => ['class' => 'model-form']
        ]);
?>
<?= $form->field($model, 'title') ?>

<? if ($model->image) : ?>
    <img src="<?= Image::thumb($model->image, 180) ?>">
    <a href="<?= Url::to(['/admin/' . $this->context->module->id . '/brand/clear-image', 'id' => $model->primaryKey]) ?>" class="text-danger text-red" title="<?= Yii::t('admin', 'Сбросить изображение') ?>"><?= Yii::t('admin', 'Сбросить изображение') ?></a>
<? endif; ?>
<?= $form->field($model, 'image')->fileInput() ?>

<?= $form->field($model, 'slug') ?>
<?= SeoTextForm::widget(['model' => $model]) ?>

<?= $form->field($model, 'short')->textarea() ?>
<?=
$form->field($model, 'description')->widget(Redactor::className(), [
    'options' => [
        'imageUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'brand'], true),
        'fileUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'brand'], true),
    ]
])
?>

<?= Html::submitButton(Yii::t('admin', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
<? ActiveForm::end(); ?>