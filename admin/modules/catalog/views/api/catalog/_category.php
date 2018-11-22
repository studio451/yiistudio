<?

use admin\widgets\Sort;
use admin\widgets\PageSize;
use admin\assets\ImageSwapAsset;

$this->registerAssetBundle(ImageSwapAsset::className());
?>

<div class="row">
    <div class="col-sm-7 col-md-7 mb-10">               
        <?= Sort::widget(['sort' => $sort, 'attributes' => ['price', 'title', 'time'], 'title' => Yii::t('admin', 'Сортировать по') . ':']) ?>  
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <?= PageSize::widget(['pagination' => $pagination, 'attributes' => ['36', '72', '144'], 'title' => Yii::t('admin', 'Показывать по') . ':']) ?>
    </div>
    <div class="col-sm-5 col-md-5 mb-10 text-right text-left-sm">
    <?=
    $category->groups_pages(
            [
                'prevPageLabel' => '<i class="fa fa-fw fa-long-arrow-left"></i>',
                'nextPageLabel' => '<i class="fa fa-fw fa-long-arrow-right"></i>',
                'disabledListItemSubTagOptions' => ['tag' => 'li', 'style' => 'display:none']])
    ?>
    </div>
</div>
<div class="row">
<? if (count($groups)) : ?>
        <? foreach ($groups as $group) : ?>
            <?= $this->render('@admin/modules/catalog/views/api/catalog/_group', ['group' => $group, 'addToCartForm' => $addToCartForm]) ?>
        <? endforeach; ?>
    <? else : ?>
        <div class="col-md-12">
        <?= Yii::t('admin/catalog', 'Нет элементов для отображения') ?>
        </div>
        <? endif; ?>
</div>
<div class="row">
    <div class="col-md-12 mb-20 text-right  text-left-sm">
<?=
$category->groups_pages(
        [
            'prevPageLabel' => '<i class="fa fa-fw fa-long-arrow-left"></i>',
            'nextPageLabel' => '<i class="fa fa-fw fa-long-arrow-right"></i>',
            'disabledListItemSubTagOptions' => ['tag' => 'li', 'style' => 'display:none']
        ]
)
?>
    </div>
</div>