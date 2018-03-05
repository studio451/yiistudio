<?

use admin\modules\news\api\News;
use admin\modules\page\api\Page;
use yii\helpers\Html;
use yii\helpers\Url;

$page = Page::get('page-news');

$this->title = $page->seo('title');
$this->params['description'] = $page->seo('description');
$this->params['keywords'] = $page->seo('keywords');
$this->params['breadcrumbs'][] = $page->title;
?>
<h1><?= $page->seo('h1') ?></h1>
<br/>
<? foreach ($news as $item) : ?>
    <div class="row">
        <div class="col-md-10">
            <span class="label label-primary"><?= $item->date ?></span> <?= Html::a($item->title, ['/news', 'slug' => $item->slug]) ?>
            <p class="mt-10"><?= $item->short ?></p>
            <p>
                <? foreach ($item->tags as $tag) : ?>
                    <a rel="nofollow" href="<?= Url::to(['/news', 'tag' => $tag]) ?>" class="label label-info"><?= $tag ?></a>
                <? endforeach; ?>
            </p>
        </div>
        <div class="col-md-2">
            <? if (!empty($item->image)) {
                ?>
                <?= Html::img($item->thumb(160, 120)) ?>
            <? } ?>
        </div>
    </div>
    <br>
<? endforeach; ?>
<?= News::pages([
                    'prevPageLabel' => '<i class="fa fa-fw fa-long-arrow-left"></i>',
                    'nextPageLabel' => '<i class="fa fa-fw fa-long-arrow-right"></i>',
                    'disabledListItemSubTagOptions' => ['tag' => 'li', 'style' => 'display:none']
                ]) ?>
