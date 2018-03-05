<?

use yii\helpers\Url;
use admin\modules\carousel\api\SlickLightbox;

$this->title = $news->seo('title');
$this->params['description'] = $news->seo('description');
$this->params['keywords'] = $news->seo('keywords');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin/news', 'Новости'), 'url' => ['/news']];
$this->params['breadcrumbs'][] = $news->title;
?>
<h1><?= $news->seo('h1') ?></h1>
<br><span class="label label-primary"><?= $news->date ?></span><br><br>
<?= $news->text ?>
<? if (count($news->photos)) : ?>
<p>   
    <?
    SlickLightbox::begin([
    ]);
    ?>
    <? foreach ($news->photos as $photo) : ?>
        <?= $photo->box(100, 100) ?>
    <? endforeach; ?>
    <? SlickLightbox::end(); ?>
</p>
<? endif; ?>
<p>
    <? foreach ($news->tags as $tag) : ?>
        <a rel="nofollow" href="<?= Url::to(['/news', 'tag' => $tag]) ?>" class="label label-info"><?= $tag ?></a>
    <? endforeach; ?>
</p>

<small class="text-muted"><?= Yii::t('admin/news', 'Просмотров') ?>: <?= $news->views ?></small>