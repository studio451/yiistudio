<?

use admin\modules\page\api\Page;
use admin\modules\shopcart\api\Shopcart;
use yii\helpers\Html;
use yii\helpers\Url;

$page = Page::get('page-shopcart');
$this->title = $page->seo('title');
$this->params['description'] = $page->seo('description');
$this->params['keywords'] = $page->seo('keywords');
$this->params['breadcrumbs'][] = $page->title;
?>
<? if (!Yii::$app->request->isAjax) { ?>
    <h1><?= $page->seo('h1') ?></h1>
<? } ?>
<? if (count($goods)) : ?>
    <div class="row mb-20">
        <div class="col-md-12">
            <?= Html::beginForm(Url::to(['/shopcart/update']), 'post', ['id' => 'shopcart_update']) ?>
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th><?= Yii::t('admin/shopcart', 'Наименование') ?></th>
                        <th width="120" class="text-center"><?= Yii::t('admin/shopcart', 'Кол-во') ?></th>
                        <th width="120" class="text-center"><?= Yii::t('admin/shopcart', 'Цена') ?></th>
                        <th width="120" class="text-center"><?= Yii::t('admin/shopcart', 'Сумма') ?></th>
                        <th width="30"></th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    $goods_total_count = 0;
                    foreach ($goods as $good) :
                        $goods_total_count += $good->count;
                        ?>
                        <tr>
                            <td width="50px">
                                <div style="min-height: 50px;">
                                    <?= Html::img($good->item->thumb(45, 45)) ?>
                                </div>
                            </td>
                            <td>
                                <?= Html::a($good->item->title, Url::to(['/catalog/item', 'category' => $good->item->category->slug, 'slug' => $good->item->slug])) ?>
                                <?= $good->options ? "($good->options)" : '' ?>
                            </td>
                            <td class="text-center">
                                <?= Html::hiddenInput("Good[$good->id]", $good->count, ['id' => 'shopcart_count_input_' . $good->item->id]) ?>
                                <a href="javascript:void(0);" class="shopcart_count_decrease" style="color:#555" data-good-id="<?= $good->item->id ?>"><i class="fa fa-minus"></i></a>
                                &nbsp;&nbsp;
                                <strong><span id="shopcart_count_text_<?= $good->item->id ?>"/><?= $good->count ?></span></strong>
                                &nbsp;&nbsp;
                                <a href="javascript:void(0);" class="shopcart_count_increase" style="color:#555" data-good-id="<?= $good->item->id ?>"><i class="fa fa-plus"></i></a>
                            </td>
                            <td class="text-center">
                                <? if ($good->discount) : ?>
                                    <del class="text-muted"><small><?= $good->oldPrice ?></small></del>
                                <? endif; ?>
                                        <?= $good->price ?> <i class="fas fa-ruble-sign"></i>
                            </td>
                            <td class="text-center"><?= $good->price * $good->count ?> <i class="fas fa-ruble-sign"></td>
                            <td><a href="javascript:void(0);" title="<?= Yii::t('admin', 'Удалить наименование') ?>" data-remove-id="<?= $good->id ?>" class="shopcart_remove_button"><i class="fa fa-times text-danger"></i></a></td>
                        </tr>
                    <? endforeach; ?>
                    <tr>
                        <td colspan="6" class="text-right">
                            <h4><?= Yii::t('admin/shopcart', 'Стоимость {goods_total_count} товара(ов):', ['goods_total_count' => $goods_total_count]) ?> <?= Shopcart::cost() ?> <i class="fas fa-ruble-sign"></i></h4>                            
                        </td>
                    </tr>
                </tbody>
            </table>
            <a href="javascript:void(0);" style="display:none" title="<?= Yii::t('admin', 'Пересчитать') ?>" data-url="<?= Url::to(['/user/login']) ?>" class="btn btn-success pull-right" id="shopcart_refresh_button"><i class="fa fa-redo"></i> <?= Yii::t('admin', 'Пересчитать') ?></a>
            <?= Html::endForm() ?>           
        </div>           
    </div>
    <?= $this->render('_create_order', ['orderForm' => $orderForm]) ?>
<? else : ?>
    <p><?= Yii::t('admin/shopcart', 'В корзине пока еще нет товаров') ?></p>
<? endif; ?>
