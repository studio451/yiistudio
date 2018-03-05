<?
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $registrationForm \frontend\models\RegistrationForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use admin\models\Setting;
use yii\helpers\Url;

$this->title = Yii::t('admin', 'Регистрация');
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-10">
                <? if (!Yii::$app->request->isAjax) { ?>
                    <h1><?= Html::encode($this->title) ?></h1>
                <? } ?>
                <p><b><?= Yii::t('admin', 'Регистрация') ?>:</b></p>
                <? $form = ActiveForm::begin(['method' => 'post', 'action' => Url::to(['/user/registration']), 'id' => 'registration-form', 'enableClientValidation' => true]); ?>

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
    <div class="col-md-6" style="border-left: 1px solid #e5e5e5;">
        <div class="row">
            <div class="col-md-10">
                <? if (!Yii::$app->request->isAjax) { ?>
                    <h1><?= Html::encode($this->title) ?></h1>
                <? } ?>
                <p><b><?= Yii::t('admin', 'Вход в систему') ?>:</b></p>
                <? $form = ActiveForm::begin(['method' => 'post', 'action' => Url::to(['/user/login']), 'id' => 'login-form', 'enableClientValidation' => true]); ?>

                <?= $form->field($loginForm, 'email')->textInput(['autofocus' => true]) ?>

                <?= $form->field($loginForm, 'password')->passwordInput() ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('admin', 'Войти'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
                <? ActiveForm::end(); ?>
            </div>
        </div>
        <div class="text-muted mb-10">
            <?= Yii::t('admin', 'Если Вы забыли пароль, то Вы можете ') ?> <?= Html::a('сбросить его', ['user/request-password-reset']) ?>.
        </div>
    </div>
</div>


