<?

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use admin\modules\carousel\api\Slick;
use admin\models\Setting;
use admin\modules\block\api\Block;

$this->title = $item->seo('title');
$this->params['description'] = $item->seo('description');
$this->params['keywords'] = $item->seo('keywords');

$this->params['breadcrumbs'][] = ['label' => Yii::t('admin/catalog', 'Каталог'), 'url' => Url::to(['/catalog'])];
$this->params['breadcrumbs'][] = ['label' => $item->category->title, 'url' => Url::to(['/catalog', 'slug' => $item->category->slug])];
$this->params['breadcrumbs'][] = $item->title;

$settings = Yii::$app->getModule('admin')->activeModules['catalog']->settings;
?>
<h1 class="page-header"><?= $item->seo('h1') ?></h1>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-5" style="padding:0 30px 30px 30px;">
                <a class="display-block p-5" id="mainitem" href="<?= $item->image ?>">
                    <div data-item="0" class="square bgn-center border" style="background-image:url('<?= $item->thumb(360, 360) ?>');">
                    </div>
                </a>

                <? if (count($item->photos)) { ?>
                    <div class="row">                
                        <?
                        Slick::begin([
                            'lightbox' => true,
                            'clientOptions' => [
                                'autoplay' => true,
                                'dots' => false,
                                'autoplay' => false,
                                'adaptiveHeight' => false,
                                'infinite' => true,
                                'slidesToShow' => 3,
                                'slidesToScroll' => 3,
                                'prevArrow' =>
                                '<button type="button" data-role="none" class="slick-prev slick-arrow" aria-label="' . Yii::t('admin/catalog', 'Предыдущий') . '" role="button" style="display: block;"><i class="fa fa-chevron-left"></i></button>',
                                'nextArrow' =>
                                '<button type="button" data-role="none" class="slick-next slick-arrow" aria-label="' . Yii::t('admin/catalog', 'Следующий') . '" role="button" style="display: block;"><i class="fa fa-chevron-right"></i></button>'
                            ],
                        ]);
                        ?>
                        <? foreach ($item->photos as $photo) { ?>
                            <a class="square display-block bgn-center border col-ss-4 col-xs-4 col-sm-4 col-md-4 m-5" href="<?= $photo->image ?>" data-image="<?= $photo->thumb(360, 360) ?>" style="background-image:url('<?= $photo->thumb(90, 90) ?>');">
                            </a>
                        <? } ?>
                        <? Slick::end(); ?>
                    </div>
                <? } ?>              
                <? if ($item->new != 0) { ?>
                    <div class="new-sticker" style="top:14px;left:45px;">
                        <a href="javascript:void(0);" rel="nofollow" title="<?= Yii::t('admin/catalog', 'Новинка!') ?>" class="no-text-decoration с-second">
                            <i class="fa fa-bookmark fs-20"></i> <?= Yii::t('admin/catalog', 'Новинка!') ?>
                        </a>
                    </div>
                <? } ?>
            </div>
            <div class="col-md-7">
                <div class="row mb-10">
                    <div class="col-md-5 col-sm-5 col-xs-5">
                        <small class="text-muted"><?= Yii::t('admin/catalog', 'Код товара: ') . $item->key ?></small>
                    </div> 
                    <div class="col-md-7 col-sm-7 col-xs-7 text-right">
                        <?
                        if ($settings['enableRating']) {
                            ?>
                            <div class="row">		
                                <div class="col-md-12">
                                    <?
                                    echo admin\modules\comment\widgets\Rating::widget([
                                        'model' => $item,
                                    ]);
                                    ?>
                                </div>
                            </div> 
                            <?
                        }
                        ?>
                    </div>
                </div>
                <div class="row mb-10">
                    <div class="col-md-7 col-sm-7 col-xs-5 fs-22">
                        <strong><?= $item->price ?> <i class="fas fa-ruble-sign"></i>
                            <? if ($item->discount) : ?>
                                <del class="small"><?= $item->oldPrice ?></del>
                            <? endif; ?>
                        </strong>
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-7 text-right">
                        Нашли дешевле? <a href="javascript:void(0)" class="dotted" role="button" data-placement="bottom" data-toggle="popover" data-trigger="focus" data-html="true" data-content="Нашли эту модель дешевле?<br/> Позвоните нам <span class='text-nowrap'><strong><?= Setting::get('contact_telephone') ?></strong></span> и мы дадим вам <strong>скидку</strong>! Или оформите заказ, указав в комментариях меньшую цену и ссылку на источник." data-original-title="" title="">Дадим скидку!</a>
                    </div>
                </div>
                <div class="row mb-20">
                    <? if ($item->available) { ?>
                        <?
                        $form = ActiveForm::begin(['action' => Url::to(['/shopcart/add', 'id' => $item->id]), 'options' => [
                                        'class' => 'form_add_to_cart'
                        ]]);
                        ?>
                        <div class="col-md-3 col-sm-3 mb-10 text-nowrap">
                            <span class="text-muted"><?= Yii::t('admin/catalog', 'Кол-во:') ?></span>
                            &nbsp;&nbsp;
                            &nbsp;&nbsp;
                            <a href="javascript:void(0);" id="count_decrease" style="color:#555"><i class="fa fa-minus"></i></a>
                            &nbsp;&nbsp;
                            <strong><span id="count_text"/>1</span></strong>
                            &nbsp;&nbsp;
                            <a href="javascript:void(0);" id="count_increase" style="color:#555"><i class="fa fa-plus"></i></a>
                            &nbsp;&nbsp;
                            &nbsp;&nbsp;
                            <div class="text-muted" style="color:#aaa">
                                <span id="help_price"><?= $item->price ?></span> x
                                <span id="help_count">1</span> =
                                <span id="help_total_price"><?= $item->price ?></span>
                                <i class="fas fa-ruble-sign"></i>
                            </div>
                            <?= $form->field($addToCartForm, 'count')->label(false)->hiddenInput(['id' => 'count_input']) ?>
                        </div>
                        <? if ($settings['enableFast']) { ?>
                            <div class="col-md-6 col-sm-6 mb-10">
                                <?= Html::submitButton('<i class="fa fa-shopping-cart"></i> Добавить в корзину', ['class' => 'btn btn-primary btn-block']) ?>    
                            </div>
                            <div class="col-md-3 col-sm-3 mb-10 text-right">
                                <a href="javascript:void(0);" rel="nofollow" title="<?= Yii::t('admin/catalog', 'Купить в один клик') ?>" data-url="<?= Url::to(['/shopcart/fast', 'id' => $item->id]) ?>" class="dotted ajaxModalPopup"><?= Yii::t('admin/catalog', 'Купить в один клик') ?></a>
                            </div>   
                        <? } else { ?>
                            <div class="col-md-3 col-sm-3 mb-10">

                            </div> 
                            <div class="col-md-6 col-sm-6 mb-10">
                                <?= Html::submitButton('<i class="fa fa-shopping-cart"></i> Добавить в корзину', ['class' => 'btn btn-primary btn-block']) ?>    
                            </div>
                        <? } ?>
                        <? ActiveForm::end(); ?> 
                    <? } else { ?>
                        <div class="col-md-12">
                            <?= Yii::t('admin/catalog', 'Под заказ') ?>
                        </div>  
                    <? } ?>
                </div>
                <?
                if (count($group->items) > 1) {
                    ?>
                    <div class="row mb-20">
                        <div class="col-md-12 text-muted">
                            <?= Yii::t('admin/catalog', 'Варианты исполнения') ?>:
                        </div>
                    </div>
                    <div class="row mb-20">
                        <div class="col-md-12">
                            <?
                            foreach ($group->items as $_item) {
                                if ($item->id == $_item->id) {
                                    ?>
                                    <a href="javascript:void(0);" rel="nofollow" class="display-block p-5" style="width:90px;float:left;" title="<?= $_item->title ?>">
                                        <div class="square bgn-center border active" style="background-image:url('<?= $_item->thumb(90, 90) ?>');">
                                        </div>
                                    </a>                                
                                    <?
                                } else {
                                    ?>
                                    <a href="<?= Url::to(['/catalog/item', 'category' => $_item->category->slug, 'slug' => $_item->slug]) ?>" class="display-block p-5" style="width:90px;display:block;float:left;" title="<?= $_item->title ?>">
                                        <div class="square bgn-center border pull-left" style="background-image:url('<?= $_item->thumb(90, 90) ?>');">
                                        </div>
                                    </a>                                   
                                    <?
                                }
                            }
                            ?>
                        </div>                    
                    </div>
                    <?
                }
                ?>
                <div class="row mb-10">
                    <div class="col-md-6 mb-10">
                        <? if (!empty($item->brand)) { ?>
                            <span class="text-muted"><?= Yii::t('admin/catalog', 'Бренд') ?>:</span> <a href="<?= Url::to(['/brand', 'slug' => $item->brand->slug]) ?>" title="<?= Yii::t('admin/catalog', 'Все товары этого бренда') ?>"><?= $item->brand->title ?></a>
                            <br>
                        <? } ?>
                        <? if (!empty($item->data->color)) { ?>
                            <span class="text-muted"><?= Yii::t('admin/catalog', 'Основной цвет') ?>:</span> <?= $item->data->color ?></span>
                            <br>
                        <? } ?>
                        <? if (!empty($item->data->dimensions)) { ?>
                            <span class="text-muted"><?= Yii::t('admin/catalog', 'Размеры (ШхВхГ)') ?>:</span> <?= $item->data->dimensions ?></span>
                            <br>
                        <? } ?>          
                        <? if (!empty($item->data->weight)) { ?>
                            <span class="text-muted"><?= Yii::t('admin/catalog', 'Вес') ?>:</span> <?= $item->data->weight ?></span>
                            <br>
                        <? } ?>
                        <? if (!empty($item->data->volume)) { ?>
                            <span class="text-muted"><?= Yii::t('admin/catalog', 'Объем') ?>:</span> <?= $item->data->volume ?></span>
                            <br>
                        <? } ?>
                        <? if (!empty($item->data->material)) { ?>
                            <span class="text-muted"><?= Yii::t('admin/catalog', 'Материал') ?>:</span> <?= $item->data->material ?></span>
                            <br>
                        <? } ?>
                        <? if (!empty($item->data->country)) { ?>
                            <span class="text-muted"><?= Yii::t('admin/catalog', 'Страна') ?>:</span> <?= $item->data->country ?></span>                   
                            <br>
                        <? } ?>
                        <? if (!empty($item->data->features)) { ?>
                            <br>
                            <span class="text-muted"><?= Yii::t('admin/catalog', 'Особенности') ?>:</span> <?= implode(', ', $item->data->features) ?>
                        <? } ?>
                    </div>
                    <div class="col-md-6 mb-10  text-right">
                        <? if ($item->gift) { ?>
                            <a href="javascript:void(0);" rel="nofollow" title="<?= Yii::t('admin/catalog', 'К этому товару полагается подарок!') ?>" data-url="<?= Url::to(['/sale', 'slug' => $item->gift]) ?>" class="ajaxModalPopup с-second dotted" style="vertical-align: text-bottom;">
                                <i class="fa fa-gift fs-20"></i> <?= Yii::t('admin/catalog', 'К этому товару полагается подарок!') ?>
                            </a>
                            <br>
                        <? } ?>
                    </div>
                </div>                
                <div class="row mb-20">
                    <div class="col-md-12">
                        <?= Block::get('item_info')->text ?>                           
                    </div>
                </div>
                <? if ($settings['enableShare']) { ?>
                    <div class="row mb-20">
                        <div class="col-md-12">
                            <? $this->registerJsFile('//yastatic.net/share2/share.js', ['position' => yii\web\View::POS_END]) ?>
                            <span style="padding-right: 5px;padding-top: 2px;" class="text-muted pull-left">Поделиться: </span><div class="ya-share2 pull-left" data-services="vkontakte,whatsapp,facebook,odnoklassniki,twitter"></div>
                        </div>
                    </div>
                <? } ?>
            </div>
        </div>    
    </div>    
</div>
<br>
<div class="row">		
    <div class="col-md-12">
        <h3 class="page-header"><?= Yii::t('admin/catalog', 'Описание') ?></h3>            
        <p>
            <?= $item->description ?>
        </p>        
    </div>
</div>
<?
if ($settings['enableComment']) {
    ?>
    <div class="row">		
        <div class="col-md-12">
            <?
            echo admin\modules\comment\widgets\Comment::widget([
                'model' => $item,
                'dataProviderConfig' => [
                    'pagination' => [
                        'pageSize' => 20
                    ],
                ]
            ]);
            ?>
        </div>
    </div>  
    <?
}
?>
<?
if ($settings['enableLastViewed']) {
    ?>
    <div class="row">		
        <div class="col-md-12">
            <?
            echo admin\modules\catalog\widgets\LastViewed::widget([
                'currentViewedSlug' => $item->slug,
                'addToCartForm' => $addToCartForm,
            ]);
            ?>
        </div>
    </div>  
    <?
}
?>
