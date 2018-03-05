<?

use yii\helpers\Url;

$action = $this->context->action->id;
$module = $this->context->module->id;
?>
<ul class="nav nav-pills">
    <li <?= ($action === 'index') ? 'class="active"' : '' ?>>
        <a href="<?= $this->context->getReturnUrl(['/admin/' . $module]) ?>">
            <? if ($action === 'edit') : ?>
                <i class="glyphicon glyphicon-chevron-left fs-12"></i>
            <? endif; ?>
            <?= Yii::t('admin', 'Список') ?>
        </a>
    </li>
    <li <?= ($action === 'create') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/' . $module . '/a/create']) ?>"><?= Yii::t('admin', 'Создать') ?></a></li>
</ul>
<br>
<? if ($action === 'edit') : ?>
    <ul class="nav nav-pills">
        <li>
            <a href="<?= $this->context->getReturnUrl(['/admin/' . $module]) ?>">
                <i class="glyphicon glyphicon-chevron-left fs-12"></i>
                <?= Yii::t('admin/block', 'HTML-блоки') ?>
            </a>
        </li>
    </ul>
    <br>
<? endif; ?>