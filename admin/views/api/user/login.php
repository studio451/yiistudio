<?
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('admin', 'Вход в систему');
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <? if (!Yii::$app->request->isAjax) { ?>
        <h1><?= Html::encode($this->title) ?></h1>
    <? } ?>
    <p><?= Yii::t('admin', 'Пожалуйста, заполните для входа') ?>:</p>
    <div class="row">
        <div class="col-lg-5">
            <? $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => true]); ?>

            <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <div class="text-muted mb-10">
                <?= Yii::t('admin', 'Если Вы забыли пароль, то Вы можете ') ?> <?= Html::a('сбросить его', ['user/request-password-reset']) ?>.
            </div>
            <div  class="text-muted mb-20">
                <?= Yii::t('admin', 'Если Вы не зарегистрированны, Вы можете ') ?> <?= Html::a('пройти регистрацию', ['user/registration']) ?>.
            </div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('admin','Вход'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

            <? ActiveForm::end(); ?>
        </div>
    </div>
</div>
