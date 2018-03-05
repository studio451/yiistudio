<?
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('admin', 'Сброс пароля');
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <? if (!Yii::$app->request->isAjax) { ?>
        <h1><?= Html::encode($this->title) ?></h1>
    <? } ?>
    <p><?= Yii::t('admin', 'Пожалуйста, укажите новый пароль') ?>:</p>
    <div class="row">
        <div class="col-lg-5">
            <? $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

            <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('admin', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
            </div>

            <? ActiveForm::end(); ?>
        </div>
    </div>
</div>
