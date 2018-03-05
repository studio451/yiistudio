<?

use admin\modules\page\api\Page;
use admin\modules\payment\api\Payment;
use yii\helpers\Url;
use yii\helpers\Html;

$module = $this->context->module->id;

$this->title = Yii::t('admin/shopcart', 'Заказ №') . $order->id;

$page = Page::get('page-shopcart-orders');
$this->params['description'] = $page->seo('description');
$this->params['keywords'] = $page->seo('keywords');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin/shopcart', 'Заказы'), 'url' => ['orders']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= $this->title ?></h1>
<br>
<div class="row">
    <div class="col-md-4"><span class="text-muted"><?= Yii::t('admin/shopcart', 'Дата:') ?></span> <span><?= $order->date ?></span></div>
    <div class="col-md-4"><span class="text-muted"><?= Yii::t('admin/shopcart', 'Статус заказа:') ?></span> <span class="label label-primary"><?= $order->status ?></span></div>
    <div class="col-md-4"><span class="text-muted"><?= Yii::t('admin/shopcart', 'Статус оплаты:') ?></span> <span class="label label-primary"><?= $order->paidStatus ?></span></div>
</div>             
<br>
<table class="table">
    <thead>
        <tr>
            <th><?= Yii::t('admin/shopcart', 'Название') ?></th>
            <th width="100"><?= Yii::t('admin/shopcart', 'Кол-во') ?></th>
            <th width="120"><?= Yii::t('admin/shopcart', 'Цена') ?></th>
            <th width="100"><?= Yii::t('admin/shopcart', 'Сумма') ?></th>
        </tr>
    </thead>
    <tbody>
        <?
        $goods_total_count = 0;
        foreach ($order->goods as $good) :
            $goods_total_count += $good->count;
            ?>
            <tr>
                <td>
                    <?= Html::a($good->item->title, Url::to(['/catalog/item', 'category' => $good->item->category->slug, 'slug' => $good->item->slug])) ?>
                    <?= $good->options ? "($good->options)" : '' ?>
                </td>
                <td><?= $good->count ?></td>
                <td>
                    <? if ($good->discount) : ?>
                        <del class="text-muted "><small><?= $good->item->oldPrice ?></small></del>
                    <? endif; ?>
                    <?= $good->price ?>
                </td>
                <td><?= $good->price * $good->count ?></td>
            </tr>
        <? endforeach; ?>
        <tr>
            <td colspan="5" class="text-right">
                <h4><?= Yii::t('admin/shopcart', 'Стоимость {goods_total_count} товара(ов):', ['goods_total_count' => $goods_total_count]) ?> <?= $order->cost ?> <i class="fa fa-rub"></i></h4>
            </td>
        </tr>
    </tbody>
</table>
<? if ($order->data) : ?>
    <div class="row">
        <div class="col-md-4">                                
        </div>
        <div class="col-md-8">
            <table class="table">
                <tbody>
                    <tr>
                        <?
                        foreach ($order->data as $array_key => $array) {
                            ?>

                            <td><span class="text-muted"><?= $orderForm->getAttributeLabel($array_key) ?></span><br>
                                <?
                                foreach ($array as $value_key => $value) {
                                    ?>
                                    <br><?
                                    if ($value != '') {
                                        echo $value;
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                    <?
                                }
                                ?>
                            </td>
                            <?
                        }
                        ?>
                    </tr>  
                </tbody>
            </table> 
        </div> 
    </div>
<? endif; ?>
<div class="row">
    <div class="col-md-4">
        <table class="table">
            <thead>
                <tr>
                    <th><?= Yii::t('admin/shopcart', 'Данные заказа') ?></th>                        
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <? if ($order->name) { ?>
                            <span class="text-muted"><?= $orderForm->getAttributeLabel('name') ?>:</span> <?= $order->name ?><br/>
                        <? } ?>
                        <? if ($order->phone) { ?>
                            <span class="text-muted"><?= $orderForm->getAttributeLabel('phone') ?>:</span> <?= $order->phone ?><br/>
                        <? } ?>
                        <? if ($order->address) { ?>
                            <span class="text-muted"><?= $orderForm->getAttributeLabel('address') ?>:</span> <?= $order->address ?><br/>
<? } ?>
                    </td>               
                </tr>                    
            </tbody>
        </table> 
    </div>
    <div class="col-md-4">
        <table class="table">
            <thead>
                <tr>
                    <th><?= Yii::t('admin/shopcart', 'Служба доставки') ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
<?= $order->deliveryDetails ?> - <?= $order->deliveryCost ?> <i class="fa fa-rub"></i>
                    </td>            
                </tr>                    
            </tbody>
        </table> 
    </div>
    <div class="col-md-4">
        <table class="table">
            <thead>
                <tr>
                    <th><?= Yii::t('admin/shopcart', 'Способ оплаты') ?></th>                        
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div>
<?= $order->paymentDetails ?>
                        </div>                                                                                            
                    </td>
                </tr>                                        
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-md-12 text-right">
        <h4><?= Yii::t('admin/shopcart', 'ИТОГО стоимость заказа (с учетом доставки): ') ?> <?= $order->totalCost ?> <i class="fa fa-rub"></i></h4>
        <br>
<?= Payment::renderCheckoutForm($order) ?>
    </div>
</div>