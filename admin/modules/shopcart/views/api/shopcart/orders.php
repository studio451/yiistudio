<?

use admin\modules\page\api\Page;
use yii\helpers\Html;

$module = $this->context->module->id;
$page = Page::get('page-shopcart-orders');

$this->title = Yii::t('admin/shopcart', 'Заказы');
$this->params['description'] = $page->seo('description');
$this->params['keywords'] = $page->seo('keywords');
$this->params['breadcrumbs'][] = Yii::t('admin/shopcart', 'Заказы');
?>
<h1><?= $this->title ?></h1>

<br>

<? if (count($orders) > 0) : ?>
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <th width="150"><?= Yii::t('admin/shopcart', 'Дата') ?></th>
                        <th width="100"><?= Yii::t('admin/shopcart', 'Заказ') ?></th>    
                        <th width="100"><?= Yii::t('admin/shopcart', 'Статус заказа') ?></th>
                        <th></th>   
                        <th width="100"><?= Yii::t('admin/shopcart', 'Стоимость') ?></th>                        
                        <th width="100"><?= Yii::t('admin/shopcart', 'Статус оплаты') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($orders as $order) : ?>
                        <tr>
                            <td><?= $order->date ?></td>
                            <td>
                                <?= Html::a(Yii::t('admin/shopcart', 'Заказ №') . $order->id, ['/shopcart/order', 'id' => $order->id, 'token' => $order->access_token]) ?>
                            </td>
                            <td><span class="label label-primary"><?= $order->status ?></span></td>
                            <td>
                            </td>
                            <td><?= $order->cost ?> <i class="fa fa-rub"></td>                            
                            <td><span class="label label-primary"><?= $order->paidStatus ?></span></td>
                        </tr>
                    <? endforeach; ?>
                    <tr>
                        <td colspan="6">                            
                            <?= $orders_pages ?>
                        </td>
                    </tr>
                </tbody>
            </table>            
        </div>
    </div>
<? else : ?>
    <p><?= Yii::t('admin/shopcart', 'У Вас еще нет заказов') ?></p>
<? endif; ?>
