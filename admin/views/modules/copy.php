<?
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = $model->title;
?>
<?= $this->render('_menu') ?>
<?= $this->render('_submenu', ['model' => $model]) ?>

<? $form = ActiveForm::begin(['enableAjaxValidation' => true]) ?>
<?= $form->field($formModel, 'title') ?>
<?= $form->field($formModel, 'name') ?>
<?= Html::submitButton(Yii::t('admin', 'Копировать'), ['class' => 'btn btn-primary']) ?>
<? ActiveForm::end(); ?>