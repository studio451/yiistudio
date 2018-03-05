<?

use yii\helpers\Html;
use admin\helpers\Image;

$this->title = $subject;
?>

<p>Создан новый заказ <b>№<?= $order->id ?></b>.</p>
<br>
<table>
    <tr>
        <th></th>
        <th>Товар</th>
        <th>Кол-во</th>
        <th>Цена</th>
        <th>Всего</th>
    </tr>
    <?
    $goods_total_count = 0;
    foreach ($order->goods as $good) :
        $goods_total_count += $good->count;
        $price = $good->discount ? round($good->price * (1 - $good->discount / 100)) : $good->price;
        ?>
        <tr>
            <td><? if ($good->item->image) {?><img src="<?= $message->embed(Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . Image::thumb($good->item->image, 45)); ?>"><? } ?></td>
            <td><?= $good->item->title ?> <?= $good->options ? "($good->options)" : '' ?></td>
            <td><?= $good->count ?></td>
            <td><?= $price ?> руб.</td>
            <td><?= $good->count * $price ?> руб.</td>
        </tr>
    <? endforeach ?>
    <tr>
        <td colspan="5" align="right">
            Стоимость <?= $goods_total_count ?> товара(ов): <?= $order->cost ?> руб.
        </td>
    </tr>
</table>
<hr>
<p>
    Данные для доставки:<br>
    Имя: <?= $order->name; ?><br>
    Телефон: <?= $order->phone; ?><br>
    <? if ($order->address) { ?>Адрес: <?= $order->address; ?><? } ?><br>
    <? if ($order->comment) { ?>Комментарий: <?= $order->comment; ?><? } ?><br>
    Служба доставки: <?= $order->delivery_details ?> - <?= $order->delivery_cost ?> руб.<br>
    Способ оплаты: <?= $order->payment_details ?><br>
</p>
<p>
    <b>Итого стоимость заказа: <?= $order->totalCost ?> руб.</b>
</p>
<p>Просмотреть заказ в панели управления вы можете <?= Html::a('здесь', $link) ?>.</p>
