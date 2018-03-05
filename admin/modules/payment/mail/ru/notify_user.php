<?

use yii\helpers\Html;

$this->title = $subject;
?>
<p>Здравствуйте!</p>
<p>Поступила оплата по Вашему заказу <b>№<?= $order->primaryKey ?></b>.</p>
<p>Посмотреть свой заказ на сайте Вы можете <?= Html::a('здесь', $link) ?>.</p>
