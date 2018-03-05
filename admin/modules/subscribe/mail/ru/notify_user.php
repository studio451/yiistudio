<?

use yii\helpers\Html;

$this->title = $subject;
?>
<p><?= $body ?></p>
<p>Отписаться от рассылки <?= Html::a('здесь', $link, ['target' => '_blank']) ?>.</p>
