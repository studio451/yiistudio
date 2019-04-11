<?

use yii\helpers\Url;

$action = $this->context->action->id;
$module = $this->context->module->id;
?>
<ul class="nav nav-pills">
    <li <?= ($action === 'index') ? 'class="active"' : '' ?>>
        <a href="<?= Url::to('/admin/' . $module . '/import') ?>">
            <? if ($action === 'edit') : ?>
                <i class="fa fa-chevron-left fs-12"></i>
            <? endif; ?>
            <?= Yii::t('admin/yml', 'Список импортов') ?>
        </a>
    </li>
    <li <?= ($action === 'create') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/' . $module . '/import/create']) ?>"><?= Yii::t('admin', 'Создать') ?></a></li>
</ul>
<br/>