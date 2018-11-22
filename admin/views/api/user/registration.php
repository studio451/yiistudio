<?
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $registrationForm \frontend\models\RegistrationForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use admin\models\Setting;
use admin\widgets\ReCaptcha;

$this->title = Yii::t('admin', 'Регистрация');
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <? if (!Yii::$app->request->isAjax) { ?>
        <h1><?= Html::encode($this->title) ?></h1>
    <? } ?>
    <p><?= Yii::t('admin', 'Пожалуйста, заполните для регистрации') ?>:</p>
    <div class="row">
        <div class="col-lg-5">
            <? $form = ActiveForm::begin(['id' => 'form-registration']); ?>

            <?
            $first = true;
            foreach ($registrationForm->attributes as $key => $value) {
                ?>
                <? if ($key == 'email') { ?>
                    <?= $form->field($registrationForm, 'email')->textInput(['autofocus' => $first]) ?>
                    <?
                } elseif ($key == 'password') {
                    if (!Setting::get('generatePasswordRegistration')) {
                        ?>
                        <?= $form->field($registrationForm, 'password')->passwordInput(['autofocus' => $first]) ?>
                        <?
                    }
                } elseif ($key == 'reCaptcha') {
                    if (Setting::get('enableCaptchaRegistration')) {
                        ?>       
                        <?= $form->field($registrationForm, 'reCaptcha')->widget(ReCaptcha::className()); ?>
                        <?
                    }
                } else {
                    ?>    
                    <?= $form->field($registrationForm, $key)->textInput(['autofocus' => $first]) ?>
                    <?
                }
                $first = false;
            }
            ?>
            <div class="form-group">
            <?= Html::submitButton(Yii::t('admin', 'Зарегистрироваться'), ['class' => 'btn btn-primary', 'name' => 'registration-button']) ?>
            </div>
                <? ActiveForm::end(); ?>
        </div>
    </div>
</div>
