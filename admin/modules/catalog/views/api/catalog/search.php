<?

use admin\modules\page\api\Page;
use yii\helpers\Html;
use yii\helpers\Url;

$page = Page::get('page-catalog-search');

$this->title = $page->seo('title');
$this->params['description'] = $page->seo('description');
$this->params['keywords'] = $page->seo('keywords');
$this->params['breadcrumbs'][] = $page->title;
?>
<h1><?= $page->seo('h1') ?></h1>
<div class="row mb-40">
    <div class="col-md-12">
        <?= $this->render('_search_form', ['text' => $text]) ?>
    </div>
</div>
<? if ($error) : ?>
    <div class="row">
        <div class="col-md-12">
            <?= $error ?>
        </div>
    </div>
<? else : ?>
    <? if (count($items)) : ?>
        <div class="row mb-20">
            <div class="col-md-12">
                <?=
                $category->items_pages(
                        [
                            'prevPageLabel' => '<i class="fa fa-fw fa-long-arrow-left"></i>',
                            'nextPageLabel' => '<i class="fa fa-fw fa-long-arrow-right"></i>',
                            'disabledListItemSubTagOptions' => ['tag' => 'li', 'style' => 'display:none']])
                ?>
            </div>
        </div>
        <? foreach ($items as $item){ ?>
            <div class="row mb-10">
                <div class="col-md-2">
                    <? if (!empty($item->image)) {
                        ?>
                        <a href="<?= Url::to(['/catalog/item', 'category' => $item->category->slug, 'slug' => $item->slug]) ?>" rel="nofollow" class="display-block" style="width:90px;" title="<?= $item->title ?>">
                            <div class="square bgn-center border" style="background-image:url('<?= $item->thumb(90, 90) ?>');">
                            </div>
                        </a>
                    <? } ?>
                </div>
                <div class="col-md-10">                    
                    <?= Html::a($item->title, ['/catalog/item', 'category' => $item->category->slug, 'slug' => $item->slug]) ?>
                    <br>
                    <strong><?= $item->price ?> <i class="fas fa-ruble-sign"></i>
                        <? if ($item->discount) { ?>
                            <del class="small"><?= $item->oldPrice ?></del>
                        <? } ?>
                    </strong>
                </div>
            </div>     
        <? } ?>
        <div class="row mt-25">
            <div class="col-md-12">
                <?=
                $category->items_pages(
                        [
                            'prevPageLabel' => '<i class="fa fa-fw fa-long-arrow-left"></i>',
                            'nextPageLabel' => '<i class="fa fa-fw fa-long-arrow-right"></i>',
                            'disabledListItemSubTagOptions' => ['tag' => 'li', 'style' => 'display:none']])
                ?>
            </div>
        </div>
    <? else : ?>
        <p><?= Yii::t('admin/catalog', 'Сожалеем, ничего не найдено! Попробуйте, поискать еще.') ?></p>
    <? endif; ?>
<? endif; ?>
    