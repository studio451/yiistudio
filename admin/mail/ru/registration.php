<?

use yii\helpers\Html;
use admin\models\Setting;

/* @var $this yii\web\View */
?>
<p>Здравствуйте!</p>
<p>Вы успешно зарегистрированы на <?= Html::encode($contact_url) ?></p>
<p>
    Ваш логин: <b><?= Html::encode($email) ?></b>
    <? if (Setting::get('generatePasswordRegistration')) { ?>
        <br/>Ваш пароль: <b><?= Html::encode($password) ?></b>
    <? } ?>
</p>
