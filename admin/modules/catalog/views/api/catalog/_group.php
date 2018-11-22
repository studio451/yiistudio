<?

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?
if (count($group->items)) {
    $item = $group->items[0];
    ?>
    <div class="col-ss-12 col-xs-6 col-sm-6 col-md-4 col-lg-3">
        <div class="border p-10 mb-20" style="height:343px;overflow: hidden;">
            <a class="text-center" href="<?= Url::to(['/catalog/item', 'category' => $item->category->slug, 'slug' => $item->slug]) ?>" title="<?= $item->title ?>">
                <div style="height:180px;overflow: hidden;">
                    <?= Html::img($item->thumb(180, 180), ['class' => 'imageSwap','data-src' => $item->thumbAlt(180, 180), 'imageSwap', 'style' => 'position: relative;
  top: 50%;
  -webkit-transform: translateY(-50%);
  -ms-transform: translateY(-50%);
  transform: translateY(-50%);']); ?>  
                </div>
                <p style="height:40px;overflow: hidden;" class="text-center mb-0"><?= $item->title ?></p>
            </a>            
            <hr class="mt-5 mb-0">
            <div class="row" style="height:50px;overflow: hidden;">
                <div class="col-md-12">
                    <?
                    $count = 0;
                    foreach ($group->items as $_item) {
                        if ($count > 2) {
                            ?>
                            <a href="<?= Url::to(['/catalog/item', 'category' => $_item->category->slug, 'slug' => $_item->slug]) ?>" class="display-block p-5" style="width:50px;float:left;" title="<?= Yii::t('admin/catalog','Еще {count} наименований',['count' => count($group->items) - 3]) ?>"
                               data-type="item" data-price="<?= $_item->price ?>" data-url="<?= Url::to(['/catalog/item', 'category' => $_item->category->slug, 'slug' => $_item->slug]) ?>"
                               >
                                <div class="square border pull-left fs-16 text-bold" style="padding-left: 2px;padding-right: 2px;height: 40px;">
                                    + <?= count($group->items) - 3?>
                                </div>
                            </a>
                            <?
                            break;
                        }
                        if ($item->id == $_item->id) {
                            ?>
                            <a href="javascript:void(0);" rel="nofollow" class="display-block p-5" style="width:50px;float:left;" title="<?= $_item->title ?>"
                               data-type="item" data-price="<?= $_item->price ?>" data-url="<?= Url::to(['/catalog/item', 'category' => $_item->category->slug, 'slug' => $_item->slug]) ?>">
                                <div class="square bgn-center border active" style="background-image:url('<?= $_item->thumb(45, 45) ?>');">
                                </div>
                            </a>                                
                            <?
                        } else {
                            ?>
                            <a href="<?= Url::to(['/catalog/item', 'category' => $_item->category->slug, 'slug' => $_item->slug]) ?>" class="display-block p-5" style="width:50px;float:left;" title="<?= $_item->title ?>"
                               data-type="item" data-price="<?= $_item->price ?>" data-url="<?= Url::to(['/catalog/item', 'category' => $_item->category->slug, 'slug' => $_item->slug]) ?>"
                               >
                                <div class="square bgn-center border pull-left" style="background-image:url('<?= $_item->thumb(45, 45) ?>');">
                                </div>
                            </a>                                   
                            <?
                        }

                        $count++;
                    }
                    ?>                    
                </div>
            </div>
            <hr class="mt-0 mb-10">
            <div class="row">
                <div class="col-md-5 col-xs-5">
                    <p class="form-control-static">
                        <strong><?= $item->price ?> <i class="fa fa-rub"></i>
                            <? if ($item->discount) { ?>
                                <del class="small"><?= $item->oldPrice ?></del>
                            <? } ?>
                        </strong>                            
                    </p>
                </div>
                <? if ($addToCartForm) { ?>
                    <div class="col-md-7 col-xs-7">
                        <? if ($item->available) { ?>
                            <?
                            $form = ActiveForm::begin(['action' => Url::to(['/shopcart/add', 'id' => $item->id]), 'options' => [
                                            'class' => 'form_add_to_cart text-right'
                            ]]);
                            ?>
                            <?= Html::hiddenInput('AddToCartForm[count]', $addToCartForm->count) ?>                           
                            <?= Html::submitButton('<i class="fa fa-shopping-cart"></i> В корзину', ['class' => 'btn btn-primary btn-sm fs-13']) ?>
                            <? ActiveForm::end(); ?>
                        <? } else { ?>
                            <?= Yii::t('admin/catalog', 'Под заказ') ?>
                        <? } ?>
                    </div>
                <? } ?>
            </div>
            <? if ($item->new != 0) { ?>
                <div class="new-sticker">
                    <a href="javascript:void(0);" rel="nofollow" title="<?= Yii::t('admin/sale', 'Новинка!') ?>" class="no-text-decoration с-second">
                        <i class="fa fa-bookmark fs-20"></i> <?= Yii::t('admin/sale', 'Новинка!') ?>
                    </a>
                </div>
            <? } ?>
            <? if ($item->gift != 0) { ?>
                <div class="gift-sticker">
                    <a href="javascript:void(0);" rel="nofollow" title="<?= Yii::t('admin/sale', 'К этому товару полагается подарок!') ?>" data-url="<?= Url::to(['/sale', 'slug' => $item->gift]) ?>" class="ajaxModalPopup no-text-decoration с-second">
                        <i class="fa fa-gift fs-20"></i> <?= Yii::t('admin/sale', 'Подарок!') ?>
                    </a>
                </div>
            <? } ?>            
        </div>
    </div>
    <?
}
?>