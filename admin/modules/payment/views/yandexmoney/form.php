<?

use yii\helpers\Html;
use yii\helpers\Url;
use admin\models\Setting;
?>
<form method="post" action="https://money.yandex.ru/quickpay/confirm.xml">
    <input type="hidden" name="quickpay-form" value="<?= $settings['quickpay-form'] ?>">
    <input type="hidden" name="receiver" value="<?= $settings['receiver'] ?>">
    <input type="hidden" name="formcomment" value="<?= Setting::get('contact_name') ?>">
    <input type="hidden" name="short-dest" value="<?= Setting::get('contact_name') ?>: <?= Yii::t('admin/payment', 'заказ') ?> №<?= $order->id ?>">
    <input type="hidden" name="label" value="<?= Setting::get('contact_name') ?>:<?= $order->id ?>">
    <input type="hidden" name="quickpay-form" value="<?= $settings['quickpay-form'] ?>">
    <input type="hidden" name="targets" value="<?= Yii::t('admin/payment', 'Оплата заказа №{order_id} на сайте {contact_name}', ['order_id' => $order->id, 'contact_name' => Setting::get('contact_name')]) ?>">
    <input type="hidden" name="sum" value="<?= $order->getTotalCost() ?>" data-type="number">
    <input type="hidden" name="comment" value="">
    <input type="hidden" name="need-fio" value="<?= $settings['need-fio'] ? 'true' : 'false' ?>">
    <input type="hidden" name="need-email" value="<?= $settings['need-email'] ? 'true' : 'false' ?>">
    <input type="hidden" name="need-phone" value="<?= $settings['need-phone'] ? 'true' : 'false' ?>">
    <input type="hidden" name="need-address" value="<?= $settings['need-address'] ? 'true' : 'false' ?>">
    <input type="hidden" name="successURL" value="<?= Url::to(['/shopcart/success-payment', 'id' => $order->id],true)  ?>">
    <div class="btn-group" data-toggle="buttons">
        <?
        $checked = 'checked';
        $active = 'active';
        if ($settings['paymentTypeAC']) {
            ?>
            <label class="btn btn-default <?= $active ?>"><input type="radio" name="paymentType" <?= $checked ?> value="AC"><i class="fa fa-credit-card"></i> Банковской картой</label> 
            <?
            $checked = '';
            $active = '';
        }
        ?>
        <? if ($settings['paymentTypePC']) { ?>
            <label class="btn btn-default <?= $active ?>"><input type="radio" name="paymentType" <?= $checked ?> value="PC"><i class="fa fa-money"></i> Яндекс.Деньгами</label>
            <?
            $checked = '';
            $active = '';
        }
        ?>

        <? if ($settings['paymentTypeMC']) { ?>
            <label class="btn btn-default <?= $active ?>"><input type="radio" name="paymentType" <?= $checked ?> value="MC"><i class="fa fa-phone"></i> Со счета мобильного</label> 
                <?
                $checked = '';
                $active = '';
            }
            ?>
    </div>
    <?= Html::submitButton($settings['button-text'], ['class' => 'btn btn-yandex']) ?>
</form>
