<?

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="text-left"> 
<? $form = ActiveForm::begin(['action' => Url::to('/admin/api/install')]); ?>

<?= $form->field($model, 'admin_email') ?>
<?= $form->field($model, 'admin_password') ?>
<br>
<?= $form->field($model, 'contact_url') ?>
<?= $form->field($model, 'contact_name') ?>
<?= $form->field($model, 'contact_email') ?>
<?= $form->field($model, 'contact_addressLocality') ?>
<?= $form->field($model, 'contact_streetAddress') ?>
<?= $form->field($model, 'contact_openingHours') ?>
<?= $form->field($model, 'contact_openingHoursISO86') ?>
<?= $form->field($model, 'contact_telephone') ?>
<br>
<?= $form->field($model, 'recaptcha_key') ?>
<?= $form->field($model, 'recaptcha_secret') ?>
<p class="recaptcha-tip"><?= Yii::t('admin', 'Подробнее о ReCapcha Google') ?> <a href="https://www.google.com/recaptcha/intro/index.html" target="_blank"> <?= Yii::t('admin','здесь') ?></a></p>
</div>
<br>
<?= Html::submitButton(Yii::t('admin','Установить'), ['class' => 'btn btn-lg btn-primary btn-block']) ?>
<? ActiveForm::end(); ?>