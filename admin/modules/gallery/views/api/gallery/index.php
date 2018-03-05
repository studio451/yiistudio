<?
use yii\helpers\Html;
use yii\helpers\Url;
use admin\helpers\Image;
use admin\modules\gallery\api\Gallery;
use admin\modules\page\api\Page;

$page = Page::get('page-gallery');

$this->title = $page->seo('title', $page->model->title);
$this->params['breadcrumbs'][] = $page->model->title;
?>
<h1><?= $page->seo('h1', $page->title) ?></h1>
<br/>

<? foreach(Gallery::categories() as $album) : ?>
    <a class="center-block" href="<?= Url::to(['/gallery', 'slug' => $album->slug]) ?>">
        <?= Html::img(Image::thumb($album->image, 160, 160)) ?><br/><?= $album->title ?>
    </a>
    <br/>
<? endforeach; ?>
