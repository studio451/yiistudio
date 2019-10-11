<?

use yii\helpers\Url;
use admin\modules\carousel\api\SlickLightbox;

$this->title = $item->seo('title', $item->model->title);
$this->params['breadcrumbs'][] = ['label' => $item->category->title, 'url' => ['article/', 'slug' => $item->category->slug]];
$this->params['breadcrumbs'][] = $item->model->title;

$settings = Yii::$app->getModule('admin')->activeModules['article']->settings;
?>
<h1 class="page-header"><?= $item->seo('h1', $item->title) ?></h1>

<?= $item->text ?>

<? if (count($item->photos)) : ?>
    <?
    SlickLightbox::begin([
    ]);
    ?>
    <? foreach ($item->photos as $photo) : ?>
        <?= $photo->box(200, 200) ?>
    <? endforeach; ?>
    <? SlickLightbox::end(); ?>
    <br/>
<? endif; ?>
<br>
<p>
    <? foreach ($item->tags as $tag) : ?>
        <a rel="nofollow" href="<?= Url::to(['/article', 'slug' => $item->category->slug, 'tag' => $tag]) ?>" class="label label-info"><?= $tag ?></a>
    <? endforeach; ?>
</p>
<?
if ($settings['enableViews']) {
    ?>
    <small class="text-muted"><?= Yii::t('admin/article', 'Просмотров') ?>: <?= $item->views ?></small>
    <?
}
?>
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