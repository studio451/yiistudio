<?

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use admin\widgets\Redactor;
use admin\modules\payment\models\Payment;

$module = $this->context->module->id;
?>
<?
$form = ActiveForm::begin([
            'enableAjaxValidation' => true,
            'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
        ]);
?>
<?= $form->field($model, 'title') ?>
<?= $form->field($model, 'slug') ?>
<?= $form->field($model, 'price') ?>
<?= $form->field($model, 'free_from') ?>
<?= $form->field($model, 'available_from') ?>

<?= $form->field($model, 'need_address')->checkbox() ?>

<?=
$form->field($model, 'description')->widget(Redactor::className(), [
    'options' => [
        'imageUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'catalog'], true),
        'fileUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'catalog'], true),
    ]
])
?>

<?= $form->field($model, 'paymentsCheckboxList')->checkboxList(Payment::listAll('id', 'title'), ['separator' => '<br>'])->label(Yii::t('admin', 'Способы оплаты для данного типа доставки')); ?>

<?= Html::submitButton(Yii::t('admin', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
<? ActiveForm::end(); ?>
