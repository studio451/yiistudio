<?

use yii\helpers\Url;
use admin\modules\carousel\api\SlickLightbox;

$this->title = $sale->seo('title');
$this->params['description'] = $sale->seo('description');
$this->params['keywords'] = $sale->seo('keywords');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin/sale', 'Акции'), 'url' => ['/sale']];
$this->params['breadcrumbs'][] = $sale->title;
?>
<h1><?= $sale->seo('h1') ?></h1>
<br><span class="label label-primary"><?= $sale->date ?></span><br><br>
<?= $sale->text ?>
<? if (count($sale->photos)) : ?>
    <p>
        <?
        SlickLightbox::begin([
        ]);
        ?>
        <? foreach ($sale->photos as $photo) : ?>
            <?= $photo->box(180, 180) ?>
        <? endforeach; ?>
        <? SlickLightbox::end(); ?>
    </p>
<? endif; ?>
<p>
    <? foreach ($sale->tags as $tag) : ?>
        <a rel="nofollow" href="<?= Url::to(['/sale', 'tag' => $tag]) ?>" class="label label-info"><?= $tag ?></a>
    <? endforeach; ?>
</p>

<small class="text-muted"><?= Yii::t('admin/sale', 'Просмотров:') ?> <?= $sale->views ?></small>