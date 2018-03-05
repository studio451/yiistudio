<?

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->title = Yii::t('admin', 'Восстановить');
?>
<?= $this->render('_menu') ?>

<b><?= Yii::t('admin', 'Восстановить') . ': ' . $file ?></b>
<br>
<br>
<?
$form = ActiveForm::begin([
            'action' => ['restore', 'id' => $id],
            'method' => 'post',
        ])
?>

<?= $form->errorSummary($model) ?>

<?= $form->field($model, 'initData')->checkbox() ?>

<?= $form->field($model, 'demoData')->checkbox() ?>

<? if ($model->hasPresets()): ?>
    <?= $form->field($model, 'preset')->dropDownList($model->getCustomOptions(), ['prompt' => '']) ?>
<? endif ?>

<?= Html::submitButton(Yii::t('admin', 'Восстановить'), ['class' => 'btn btn-success']) ?>

<? ActiveForm::end() ?>


