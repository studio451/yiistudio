<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use admin\models\Setting;
?>
<? $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
<? if(Yii::$app->user->can("SuperAdmin")) { ?>
    <?= $form->field($model, 'name')->textInput(!$model->isNewRecord ? ['disabled' => 'disabled'] : []) ?>
    <?= $form->field($model, 'visibility')->checkbox(['uncheck' => Setting::VISIBLE_ALL]) ?>
<? } ?>
<?= $form->field($model, 'title')->textarea(['disabled' => !Yii::$app->user->can("SuperAdmin")]) ?>
<?= $form->field($model, 'value')->textarea() ?>

<?= Html::submitButton(Yii::t('admin', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
<? ActiveForm::end(); ?>