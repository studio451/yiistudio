<?

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use admin\widgets\Redactor;
use admin\modules\seo\widgets\SeoTextForm;
?>
<?

$form = ActiveForm::begin([
            'enableAjaxValidation' => true,
            'options' => ['class' => 'model-form']
        ]);
?>
<?= $form->field($model, 'title') ?>
<?=

$form->field($model, 'text')->widget(Redactor::className(), [
    'options' => [
        'imageUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'pages']),
        'fileUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'pages']),
    ]
])
?>

<?= $form->field($model, 'slug') ?>
<?= SeoTextForm::widget(['model' => $model]) ?>

<?= Html::submitButton(Yii::t('admin', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
<? ActiveForm::end(); ?>