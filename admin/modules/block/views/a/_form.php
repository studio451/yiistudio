<?

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use admin\widgets\Redactor;

?>
<?

$form = ActiveForm::begin([
            'enableAjaxValidation' => true,
            'options' => ['class' => 'model-form']
        ]);
?>
<?=
$form->field($model, 'text')->widget(Redactor::className(), [
    'options' => [
        'imageUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'block']),
        'fileUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'block']),
    ]
])
?>
<?= $form->field($model, 'assets_css') ?>
<?= $form->field($model, 'assets_js') ?>

<?= $form->field($model, 'slug') ?>
<?= Html::submitButton(Yii::t('admin', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
<? ActiveForm::end(); ?>