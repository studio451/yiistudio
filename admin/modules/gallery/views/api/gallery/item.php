<?

use admin\modules\page\api\Page;
use admin\modules\carousel\api\SlickLightbox;

$this->title = $album->seo('title', $album->model->title);

$page = Page::get('page-gallery');

$this->params['breadcrumbs'][] = ['label' => $page->model->title, 'url' => ['/gallery']];
$this->params['breadcrumbs'][] = $album->model->title;
?>
<h1><?= $album->seo('h1', $album->title) ?></h1>


<? if (count($photos)) { ?>
    <?
    SlickLightbox::begin([
    ]);
    ?>
    <? foreach ($photos as $photo) { ?>
        <?= $photo->box(200, 200) ?>
    <? } ?>
    <? SlickLightbox::end(); ?>
    <br/>
<? } else { ?>
    <p><?= Yii::t('admin/gallery', 'Нет изображений') ?></p>
<? } ?>
<?=
$album->pages([
    'prevPageLabel' => '<i class="fa fa-fw fa-long-arrow-left"></i>',
    'nextPageLabel' => '<i class="fa fa-fw fa-long-arrow-right"></i>',
    'disabledListItemSubTagOptions' => ['tag' => 'li', 'style' => 'display:none']
])
?>
