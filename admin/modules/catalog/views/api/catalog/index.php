<?

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use admin\widgets\Menu;
use admin\modules\carousel\api\Slick;
use admin\modules\sale\api\Sale;

$this->registerLinkTag(['rel' => 'canonical', 'href' => Url::to(Yii::$app->request->getPathInfo(), true)]);

if ($brand) {
    $title = $category->title . ' ' . $brand->title;
} else {
    $title = $category->title;
}

$this->title = $category->seo('title', $title);
$this->params['description'] = $category->seo('description', $title);
$this->params['keywords'] = $category->seo('keywords', $title);

foreach ($category->parents as $parent) {
    if ($parent->slug == 'catalog') {
        $this->params['breadcrumbs'][] = ['label' => $parent->title, 'url' => Url::to(['/catalog'])];
    } else {
        $this->params['breadcrumbs'][] = ['label' => $parent->title, 'url' => Url::to(['/catalog', 'slug' => $parent->slug])];
    }
}
if ($brand) {
    $this->params['breadcrumbs'][] = ['label' => $category->title, 'url' => Url::to(['/catalog', 'slug' => $category->slug])];
}



$this->params['breadcrumbs'][] = $title;
?>
<h1 class="page-header"><?= $category->seo('h1', $title) ?></h1>
<div class="row">    
    <div class="col-md-2 col-sm-3">
        <div class="row">    
            <?
            $menu_items = $category->menu('category');
            if (count($menu_items) > 0) {
                ?>
                <div class="col-ss-12 col-xs-6 col-sm-12 col-md-12">
                    <div class="border p-10 pl-20 pr-20 mb-20 bg-first">
                        <?=
                        Menu::widget(['items' => $menu_items,
                            'options' => [
                                'class' => 'list-unstyled',
                            ],
                            'linkActiveTemplate' => '<div class="fs-16 mt-10 lh-16"><b><a href="{url}">{label}</a></b></div>',
                            'linkTemplate' => '<div class="fs-16 mt-10 lh-16"><a href="{url}">{label}</a></div>',
                            'route' => ltrim(Yii::$app->request->pathInfo, '/')
                        ]);
                        ?>
                    </div> 
                </div>   
                <?
            }
            ?>
            <noindex>
                <div class="col-ss-12 col-xs-6 col-sm-12 col-md-12">
                    <div class="border p-15 mb-20">
                        <?
                        if ($brand) {
                            $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['/catalog', 'slug' => $category->slug . '_' . $brand->slug])]);
                        } else {
                            $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['/catalog', 'slug' => $category->slug])]);
                        }
                        ?>
                        <? if (!$brand) { ?>
                            <?= $form->field($filterForm, 'brand_id')->checkboxList($category->brandsOptions(), ['separator' => '<br>', 'itemOptions' => ['labelOptions' => ['class' => 'filter-checkbox-label']]]); ?>
                        <? } ?>
                        <label class="control-label"><?= Yii::t('admin/catalog', 'Цена') ?></label>
                        <?= $form->field($filterForm, 'price_from', ['template' => "{input}"])->textInput(['class' => 'form-control input-sm', 'placeholder' => Yii::t('admin/catalog', 'От')])->label() ?>
                        <?= $form->field($filterForm, 'price_to', ['template' => "{input}"])->textInput(['class' => 'form-control input-sm', 'placeholder' => Yii::t('admin/catalog', 'До')])->label() ?>
                        <?= Html::submitButton('Применить', ['class' => 'btn btn-primary btn-block ']) ?>
                        <? ActiveForm::end(); ?>
                    </div> 
                </div>    
            </noindex>
            <div class="col-ss-12 <? if (count($menu_items) > 0) { ?> col-xs-12 <? } else { ?> col-xs-6 <? } ?> col-sm-12 col-md-12">
                <div class="mb-20">
<?
Slick::begin([
    'clientOptions' => [
        'autoplaySpeed' => 3000,
        'easing' => 'bounce',
        'autoplay' => true,
        'dots' => true,
        'arrows' => false,
        'adaptiveHeight' => false,
        'padding' => '20px',
        'infinite' => true,
        'prevArrow' =>
        '<a style="z-index:111;font-size: 55px;position: absolute;left:10px;top: 50%;padding: 0;
    -webkit-transform: translate(0, -50%);
    -ms-transform: translate(0, -50%);
    transform: translate(0, -50%);cursor:pointer;width:30px;color:#fff;"><i class="fa fa-angle-left"></i></a>',
        'nextArrow' =>
        '<a style="z-index:111;font-size: 55px;position: absolute;right:10px;top: 50%;padding: 0;
    -webkit-transform: translate(0, -50%);
    -ms-transform: translate(0, -50%);
    transform: translate(0, -50%);cursor:pointer;width:30px;text-align:right;color:#fff;"><i class="fa fa-angle-right"></i></a>'
    ],
]);
?>
                    <?
                    foreach (Sale::last(4) as $sale) {
                        ?>
                        <?= $sale->smallBanner ?>
                        <?
                    }
                    ?>
                    <? Slick::end(); ?>
                </div>
            </div>

        </div>
    </div>    
    <div class="col-md-10 col-sm-9">
<?=
$this->render('@admin/modules/catalog/views/api/catalog/_category', [
    'category' => $category,
    'groups' => $groups,
    'addToCartForm' => $addToCartForm,
    'filterForm' => $filterForm,
    'sort' => $sort,
    'pagination' => $pagination,
])
?>
        <? if ($showDescription) { ?>
            <div class="row">
                <div class="col-md-12 text-justify">
    <?= $category->description ?>
                </div>
            </div>
<? } ?>
    </div>    
</div>
