<?

use yii\helpers\Html;

$this->title = $subject;
?>
<? if ($order) { ?>
    <p>Платежная операция по заказу <b>№<?= $order->id ?></b>.</p>
<? } else { ?>
    <p>Платежная операция:</p>
<? } ?>
<p><?= $description ?></p>
<? if ($order) { ?>
    <p>Просмотреть заказ в панели управления вы можете <?= Html::a('здесь', $link) ?>.</p>
<? } ?>
