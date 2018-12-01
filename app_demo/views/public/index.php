<?

use yii\helpers\Html;
use yii\helpers\Url;
use admin\widgets\Menu;
use admin\modules\catalog\api\Catalog;
use admin\modules\page\api\Page;
use admin\modules\news\api\News;
use admin\modules\carousel\api\Slick;
use admin\modules\block\api\Block;

$page = Page::get('page-shop');

$this->title = $page->seo('title');
$this->params['description'] = $page->seo('description');
$this->params['keywords'] = $page->seo('keywords');
?>
<div class="row pl-30 pr-30 mb-30" style="height:180px;overflow: hidden;">
    <?
    Slick::begin([
        'clientOptions' => [
            'breakpoints' => 800,
            'autoplay' => true,
            'dots' => false,
            'adaptiveHeight' => false,
            'padding' => '20px',
            'infinite' => true,
            'prevArrow' =>
            '<a style="z-index:111;font-size: 55px;position: absolute;left:-30px;top: 50%;padding: 0;
    -webkit-transform: translate(0, -50%);
    -ms-transform: translate(0, -50%);
    transform: translate(0, -50%);cursor:pointer;width:30px;"><i class="fa fa-angle-left"></i></a>',
            'nextArrow' =>
            '<a style="z-index:111;font-size: 55px;position: absolute;right:-30px;top: 50%;padding: 0;
    -webkit-transform: translate(0, -50%);
    -ms-transform: translate(0, -50%);
    transform: translate(0, -50%);cursor:pointer;width:30px;text-align:right;"><i class="fa fa-angle-right"></i></a>'
        ],
    ]);
    ?>

    <?
    $category = Catalog::category();
    foreach ($category->brands() as $brand) {
        if ($brand->image) {
            ?><div class="row">
                <div class="col-md-7 col-md-offset-1 col-sm-7 col-sm-offset-1">
                    <div class="mt-50" ><a style="font-size: 26px;font-weight: bold;color: #b4b1ab;" href="<?= Url::to(['/brand', 'slug' => $brand->slug]) ?>"><?= $brand->title ?></a></div>
                    <div style="height:60px;"><?= $brand->short ?></div>
                </div>
                <div class="col-md-3 col-sm-3">
                    <a href="<?= Url::to(['/brand', 'slug' => $brand->slug]) ?>"><?= Html::img($brand->thumb(180, 180), ['class' => 'pull-right']) ?></a>
                </div>
            </div>
            <?
        }
    }
    ?>
    <? Slick::end(); ?>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-10">
                <ul class="list-unstyled">
                    <? foreach (News::last(4) as $news) { ?>
                        <li>      
                            <div class="row">
                                <div class="col-md-2"><span class="text-nowrap"><i class="fa fa-clock-o"></i> <?= $news->date ?></span></div>
                                <div class="col-md-10">
                                    <a href="<?= Url::to(['/news', 'slug' => $news->slug]) ?>"><?= $news->title ?></a>
                                </div>
                            </div>
                        </li>
                    <? } ?>
                </ul>
            </div>
            <div class="col-md-2 text-right">
                <a href="<?= Url::to(['/news']) ?>"><?= Yii::t('app','Все новости') ?><i class="fa fa-fw fa-chevron-down"></i></a>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <?= Block::get('bunner1')->text ?>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12 text-justify">
                <p><?= Block::get('public-index-welcome')->text ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <h3><?= Yii::t('app', 'Каталог') ?></h3>
                <div class="row">
                    <div class="col-ss-12 col-xs-6 col-sm-12 col-md-12">
                        <div class="border p-10 pl-20 pr-20 mb-20 bg-first">
                            <?=
                            Menu::widget(['items' => $category->menu(),
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
                </div>
                <h3><?= Yii::t('app', 'Бренды') ?></h3>
                <div class="row">
                    <div class="col-md-12">
                        <div class="border p-20 bg-first">
                            <? foreach ($category->brands() as $brand) { ?>
                                <div class="fs-16 mb-5"><a href="<?= Url::to(['/brand', 'slug' => $brand->slug]) ?>"><?= $brand->title ?></a></div>
                            <? } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-10">
                <div class="row mb-15">
                    <div class="col-md-12">
                        <div class="sidebar-header sidebar-header-lg"><a href="<?= Url::to(['/catalog', 'slug' => 'smartfony_apple']) ?>"><?= Yii::t('app', 'Apple iPhone') ?></a></div>
                        <div class="row"> 
                            <?
                            $counter = 0;
                            $last = false;
                            foreach (Catalog::items_last(4, ['brand_id' => 2, 'category_id' => 2]) as $item) {
                                $counter++;
                                if ($counter > 3) {
                                    $last = true;
                                }
                                ?>
                                <?= $this->render('@admin/modules/catalog/views/api/catalog/_item', ['item' => $item, 'addToCartForm' => new \app_demo\models\AddToCartForm(), 'last' => $last]) ?>
                            <? } ?>
                        </div>
                    </div>
                </div>
                <div class="sidebar-header sidebar-header-lg"><?= Yii::t('app', 'Новинки') ?></div>                
                <div class="row"> 
                    <? foreach (Catalog::groups_last(4) as $group) : ?>            
                        <?
                        echo $this->render('@admin/modules/catalog/views/api/catalog/_group', ['group' => $group, 'addToCartForm' => new \app_demo\models\AddToCartForm()])
                        ?>
                    <? endforeach; ?>
                </div>
            </div>
        </div>
        <div class="row text-justify">
            <div class="col-md-12">
                <h1><?= $page->seo('h1') ?></h1>
                <p><?= $page->text ?></p>
            </div>
        </div>
    </div>
</div>