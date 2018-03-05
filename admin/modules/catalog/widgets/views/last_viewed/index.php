<?
if(count($items)){
?>
<div class="row mb-15">
    <div class="col-md-12">
            <div class="title-block clearfix">
                <h3 class="h3-body-title">
                    <? echo Yii::t('admin/catalog', 'Вы недавно просматривали'); ?>
                </h3>
                <div class="title-separator"></div>
            </div>
            <?
            $counter = 0;
            $last = false;
            foreach ($items as $item) {
                $counter++;
                if ($counter > 3) {
                    $last = true;
                }
                ?>
                <?= $this->render('@admin/modules/catalog/views/api/catalog/_item', ['item' => $item, 'addToCartForm' => $addToCartForm, 'last' => $last]) ?>
            <? } ?>
    </div>
</div>
<?
}
?>