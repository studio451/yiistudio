<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use admin\widgets\Redactor;
?>
<? $form = ActiveForm::begin([
    'options' => ['class' => 'model-form']
]); ?>
<?= $form->field($model, 'question')->widget(Redactor::className(),[
    'options' => [
        'minHeight' => 300,
        'buttons' => ['bold', 'italic', 'unorderedlist', 'link'],
        'linebreaks' => true
    ]
]) ?>
<?= $form->field($model, 'answer')->widget(Redactor::className(),[
    'options' => [
        'minHeight' => 300,
        'buttons' => ['bold', 'italic', 'unorderedlist', 'link'],
        'linebreaks' => true
    ]
]) ?>

<?= Html::submitButton(Yii::t('admin', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
<? ActiveForm::end(); ?>