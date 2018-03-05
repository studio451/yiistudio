<?

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$asset = \admin\assets\PublicAsset::register($this);
$this->title = Yii::t('admin', 'Вход');
?>

<h4><?= Yii::t('admin', 'Вход') ?></h4>
<br>
<?
$form = ActiveForm::begin([
            'fieldConfig' => [
                'template' => "{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}"
            ]
        ])
?>
<?= $form->field($model, 'email')->textInput(['class' => 'form-control', 'placeholder' => Yii::t('admin', 'E-mail')]) ?>
<?= $form->field($model, 'password')->passwordInput(['class' => 'form-control', 'placeholder' => Yii::t('admin', 'Пароль')]) ?>
<?= Html::submitButton(Yii::t('admin', 'Логин'), ['class' => 'btn btn-lg btn-primary btn-block']) ?>
<? ActiveForm::end(); ?>
               