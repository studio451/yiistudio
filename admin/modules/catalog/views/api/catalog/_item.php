<?

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="col-ss-12 col-xs-6 col-sm-6 col-md-4 col-lg-3 <?= $last ? 'hidden-md' : '' ?>">
    <div class="border p-10 mb-20" style="height:286px;overflow: hidden;">
        <a class="text-center" href="<?= Url::to(['/catalog/item', 'category' => $item->category->slug, 'slug' => $item->slug]) ?>" title="<?= $item->title ?>">
            <div style="height:180px;overflow: hidden;">
                <?= Html::img($item->thumb(180, 180), ['style' => 'position: relative;
  top: 50%;
  -webkit-transform: translateY(-50%);
  -ms-transform: translateY(-50%);
  transform: translateY(-50%);']); ?>  
            </div>
            <p style="height:40px;overflow: hidden;" class="text-center mb-0"><?= $item->title ?></p>
        </a>            
        <div class="row mt-10">
            <div class="col-md-5 col-xs-5">
                <p class="form-control-static">
                    <strong><?= $item->price ?> <i class="fas fa-ruble-sign"></i>
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
                <a href="javascript:void(0);" rel="nofollow" title="<?= Yii::t('admin/catalog', 'Новинка!') ?>" class="no-text-decoration с-second">
                    <i class="fa fa-bookmark fs-20"></i> <?= Yii::t('admin/catalog', 'Новинка!') ?>
                </a>
            </div>
        <? } ?>
        <? if ($item->gift != 0) { ?>
            <div class="gift-sticker">
                <a href="javascript:void(0);" rel="nofollow" title="<?= Yii::t('admin/catalog', 'К этому товару полагается подарок!') ?>" data-url="<?= Url::to(['/sale', 'slug' => $item->gift]) ?>" class="ajaxModalPopup no-text-decoration с-second">
                    <i class="fa fa-gift fs-20"></i> <?= Yii::t('admin/catalog', 'Подарок!') ?>
                </a>
            </div>
        <? } ?>
    </div>
</div>
