<?

use admin\modules\shopcart\models\Order;
use yii\helpers\Html;
use yii\helpers\Url;
use admin\helpers\Image;
use admin\modules\payment\models\Checkout;

$this->title = Yii::t('admin/shopcart', 'Заказ') . ' №' . $order->primaryKey;

$states = Order::states();
unset($states[Order::STATUS_BLANK]);

$paidStates = Order::paidStates();

$module = $this->context->module->id;

$this->registerJs('
var oldStatus = ' . $order->status . ';
$("#order-status").change(function(){
    if($(this).val() != oldStatus){
        $("#notify-user").slideDown();
    } else {
        $("#notify-user").slideUp();
    }
});
');
?>
<?= $this->render('_menu') ?>
<div class="row">
    <div class="col-md-6">
        <h3><?= Yii::t('admin/shopcart', 'Данные заказа') ?> <i class="fa fa-file-text-o"></i></h3>
        <hr>
        <?= Html::beginForm(Url::to(['/admin/shopcart/a/edit', 'id' => $order->id]), 'post', ['class' => 'form-horizontal']) ?>
        <? if ($order->status != Order::STATUS_BLANK) : ?>
            <div class="form-group">
                <label class="control-label col-md-2"><?= Yii::t('admin', 'Статус заказа') ?></label>
                <div class="col-md-10"><?= Html::dropDownList('status', $order->status, $states, ['id' => 'order-status', 'class' => 'form-control']) ?></div>
            </div>
        <? endif; ?>
        <? if ($order->email) : ?>
            <div class="form-group">
                <label class="control-label col-md-2"></label>
                <div class="col-md-8">
                    <div class="checkbox" id="notify-user" style="display: none;">
                        <label>
                            <?= Html::checkbox('notify', true, ['uncheck' => 0]) ?> <?= Yii::t('admin/shopcart', 'Отправить письмо с уведомлением пользователю') ?>
                        </label>
                    </div>
                </div>
            </div>
        <? endif; ?>
        <div class="form-group">
            <label class="control-label col-md-2"><?= Yii::t('admin', 'E-mail') ?></label>
            <div class="col-md-10"><p class="form-control-static"><?= $order->email ?></p></div>
        </div>
        <? if ($this->context->module->settings['enablePhone']) : ?>
            <div class="form-group">
                <label class="control-label col-md-2"><?= Yii::t('admin/shopcart', 'Телефон') ?></label>
                <div class="col-md-10"><p class="form-control-static"><?= $order->phone ?></p></div>
            </div>
        <? endif; ?>
        <? if ($this->context->module->settings['enableName']) : ?>
            <div class="form-group">
                <label class="control-label col-md-2"><?= Yii::t('admin', 'Имя') ?></label>
                <div class="col-md-10"><p class="form-control-static"><?= $order->name ?></p></div>
            </div>
        <? endif; ?> 
        <div class="form-group">
            <label class="control-label col-md-2"><?= Yii::t('admin', 'Адрес') ?></label>
            <div class="col-md-10"><p class="form-control-static"><?= $order->address ?></p></div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-2"><?= Yii::t('admin', 'Дата заказа') ?></label>
            <div class="col-md-10"><p class="form-control-static"><?= Yii::$app->formatter->asDatetime($order->time, 'medium') ?></p></div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-2">IP</label>
            <div class="col-md-10"><p class="form-control-static"><?= $order->ip ?> <a href="//freegeoip.net/?q=<?= $order->ip ?>" class="label label-info" target="_blank">info</a></p></div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-2"><?= Yii::t('admin/shopcart', 'Комментарий клиента') ?></label>
            <div class="col-md-10"><p class="form-control-static"><?= nl2br($order->comment) ?></p></div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-2"><?= Yii::t('admin/shopcart', 'Комментарий сотрудника') ?></label>
            <div class="col-md-10"><?= Html::textarea('remark', $order->remark, ['class' => 'form-control']) ?></div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-2"></label>
            <div class="col-md-10"></div>
        </div>

        <?= Html::submitButton(Yii::t('admin', 'Сохранить'), ['class' => 'btn btn-primary']) ?>

        <?= Html::endForm() ?>
    </div>
    <div class="col-md-6">
        <h3><?= Yii::t('admin/shopcart', 'Состояние оплаты') ?> <i class="fa fa-money"></i></h3>
        <hr>

        <?= Html::beginForm(Url::to(['/admin/payment/a/process', 'id' => $order->id]), 'post', ['class' => 'form-horizontal']) ?>
        <div class="form-group">
            <label class="control-label col-md-2"><?= Yii::t('admin', 'Статус оплаты') ?></label>
            <div class="col-md-8"><?= Html::dropDownList('paid_status', $order->paid_status, $paidStates, ['class' => 'form-control']) ?></div>
            <div class="col-md-2"><?= Html::submitButton(Yii::t('admin', 'Сохранить'), ['class' => 'btn btn-primary']) ?></div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-2"><?= Yii::t('admin', 'Способ оплаты') ?></label>
            <div class="col-md-10"><p class="form-control-static"><i class="fa fa-fw fa-money"></i> <?= $order->payment_details ?></p></div>
        </div>
        <? if ($order->paid_time) { ?>
            <div class="form-group">
                <label class="control-label col-md-2"><?= Yii::t('admin', 'Время оплаты') ?></label>
                <div class="col-md-10"><p class="form-control-static"><?= Yii::$app->formatter->asDatetime($order->paid_time, 'short') ?></p></div>
            </div>
        <? } ?>
        <? if ($order->paid_details) { ?>
            <div class="form-group">
                <label class="control-label col-md-2"><?= Yii::t('admin', 'Детали оплаты') ?></label>
                <div class="col-md-10"><p class="form-control-static"><?= $order->paid_details ?></p></div>
            </div> 
        <? } ?>


        <div class="row">
            <label class="control-label col-md-2"><?= Yii::t('admin', 'Платежи') ?></label>
            <div class="col-md-10">
                <div class="form-control-static">
                    <? foreach (Checkout::getByOrderId($order->id) as $checkout) { ?>
                        <i class="fa fa-caret-right"></i>
                        <?= Yii::$app->formatter->asDatetime($checkout->time, 'short') ?>
                        <?= $checkout->renderStatus() ?> <?= $checkout->description ?>
                        <a href="javascript:void(0);" data-toggle="modal" data-target="#requestModal_<?= $checkout->id ?>"><i class="fa fa-info-circle"></i></a>       
                        <?= $checkout->renderModal('requestModal_' . $checkout->id) ?>
                        <br>
                    <? } ?> 
                </div>
            </div>                
        </div>

        <?= Html::endForm() ?>
    </div>
</div>
<hr>
<h3><?= Yii::t('admin/shopcart', 'Список товаров') ?> <i class="fa fa-shopping-cart"></i></h3>
<hr>
<table class="table table-bordered">
    <thead>
    <th></th>
    <th><?= Yii::t('admin', 'Описание') ?></th>
    <th><?= Yii::t('admin/shopcart', 'Опции') ?></th>
    <th width="80"><?= Yii::t('admin/shopcart', 'Кол-во') ?></th>
    <th width="80"><?= Yii::t('admin/shopcart', 'Скидка') ?></th>
    <th width="150"><?= Yii::t('admin/shopcart', 'Цена') ?></th>
    <th width="30"></th>
</thead>
<tbody>
    <?
    $goods_total_count = 0;
    foreach ($goods as $good) {
        $goods_total_count += $good->count;
        ?>
        <tr>
            <td width="50px"><img src="<?= Image::thumb($good->item->image, 45); ?>"></td>
            <td><?= Html::a($good->item->title, ['/admin/catalog/item/edit', 'id' => $good->item->primaryKey]) ?></td>
            <td><?= $good->options ?></td>
            <td><?= $good->count ?></td>
            <td><?= $good->discount ?></td>
            <td>
                <? if ($good->discount) { ?>
                    <b><?= round($good->price * (1 - $good->discount / 100)) ?> <i class="fa fa-rub"></i></b>
        <strike><small class="text-muted"><?= $good->price ?></small></strike>
    <? } else { ?>
        <b><?= $good->price ?> <i class="fa fa-rub"></i></b>
    <? } ?>
    </td>
    <td><a href="<?= Url::to(['/admin/' . $module . '/goods/delete', 'id' => $good->primaryKey]) ?>" class="fa fa-times text-red" title="<?= Yii::t('admin', 'Удалить запись') ?>"></a></td>
    </tr>
<? } ?>
</tbody>
</table>
<h4 class="text-right"><?= Yii::t('admin/shopcart', 'Стоимость {goods_total_count} товара(ов):', ['goods_total_count' => $goods_total_count]) ?> <?= $order->cost ?> <i class="fa fa-rub"></i></h4>

<?
//Дополнительные данные заказа
$str = '';
foreach ($orderForm->attributes as $key => $value) {

    if ($order->data) {
        foreach ($order->data as $data_key => $data_value) {
            if ($data_key == $key) {
                if (is_array($data_value)) {
                    $str .= '<td><span class="text-muted">' . $orderForm->getAttributeLabel($key) . '</span><br>';
                    foreach ($data_value as $data_value_key => $data_value_value) {
                        $str .= Html::input('text', 'data[' . $key . '][' . $data_value_key . ']', $data_value_value, ['class' => 'form-control input-sm', 'maxlength' => 512]);
                    }
                    $str .= '</td>';
                } else {
                    $str .= '<td><span class="text-muted">' . $orderForm->getAttributeLabel($key) . '</span><br>' .
                            Html::input('text', 'data[' . $key . ']', $data_value, ['class' => 'form-control input-sm', 'maxlength' => 512]) .
                            '</td>';
                }
            }
        }
    }
    ?>

    <?
}

if ($str) {
    ?>
    <div class="row">
        <div class="col-md-12">        
            <h3><?= Yii::t('admin/shopcart', 'Дополнительные данные заказа') ?> <i class="fa fa-cart-plus"></i></h3>
            <hr>            
            <?= Html::beginForm(Url::to(['/admin/shopcart/a/data', 'id' => $order->id]), 'post') ?>
            <table class="table">
                <tbody>
                    <tr>
                        <?= $str ?>
                    </tr>  
                </tbody>
            </table>
            <?= Html::submitButton(Yii::t('admin', 'Сохранить дополнительные данные'), ['class' => 'btn btn-primary']) ?>
            <?= Html::endForm() ?>            
        </div>
    </div>
<? } ?>
<div class="row">
    <div class="col-md-12">
        <h3><?= Yii::t('admin/shopcart', 'Служба доставки') ?> <i class="fa fa-truck"></i></h3>
        <hr>
        <?= $order->delivery_details ?> - <?= $order->delivery_cost ?> <i class="fa fa-rub"></i>        
    </div>    
</div>
<div class="row">
    <div class="col-md-12 text-right">
        <h3><?= Yii::t('admin/shopcart', 'Итого стоимость заказа: ') ?> <?= $order->totalCost ?> <i class="fa fa-rub"></i></h3>
    </div>
</div>

<?= Html::beginForm(Url::to(['/admin/shopcart/a/export-to-excel', 'id' => $order->id]), 'post') ?>
<div class="row">
    <div class="col-md-12">
        <?= Html::submitButton('<span class="fa fa-file-excel-o"></span> ' . Yii::t('admin', 'Экспорт заказа в excel файл'), ['class' => 'btn btn-primary btn-success']) ?>       
    </div>
</div>
<?= Html::endForm() ?>      
