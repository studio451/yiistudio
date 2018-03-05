<?
use yii\helpers\Url;

$action = $this->context->action->id;
$module = $this->context->module->id;
?>
<ul class="nav nav-pills">
    <? if($action === 'index') : ?>
        <li><a href="<?= Url::to(['/admin/'.$module]) ?>"><i class="glyphicon glyphicon-chevron-left fs-12"></i> <?= Yii::t('admin', 'Категории') ?></a></li>
    <? endif; ?>
    <li <?= ($action === 'index') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/'.$module.'/item', 'id' => $category->primaryKey]) ?>"><? if($action !== 'index') echo '<i class="glyphicon glyphicon-chevron-left fs-12"></i> ' ?><?= $category->title ?></a></li>
    <li <?= ($action === 'create') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/'.$module.'/item/create', 'id' => $category->primaryKey]) ?>"><?= Yii::t('admin/catalog', 'Добавить элемент') ?></a></li>    
</ul>
<br>