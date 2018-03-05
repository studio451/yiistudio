<?

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user admin\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/reset-password', 'token' => $user->password_reset_token]);
?>
<p>Здравствуйте!</p>
<p>Для сброса пароля необходимо перейти по ссылке:<br>
<?= Html::a(Html::encode($resetLink), $resetLink) ?></p>

