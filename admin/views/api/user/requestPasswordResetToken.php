<?

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('admin', 'Сброс пароля');
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <? if (!Yii::$app->request->isAjax) { ?>
        <h1><?= Html::encode($this->title) ?></h1>
    <? } ?>
    <p><?= Yii::t('admin', 'Пожалуйста укажите Ваш email. Cсылка для сброса пароля будет отправлена на указанный email') ?></p>
    <div class="row">
        <div class="col-lg-5">
            <? $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

            <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('admin','Отправить'), ['class' => 'btn btn-primary']) ?>
            </div>

            <? ActiveForm::end(); ?>
        </div>
    </div>
</div>
