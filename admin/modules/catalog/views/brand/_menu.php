<?

use yii\helpers\Url;

$action = $this->context->action->id;
$module = $this->context->module->id;
?>
<ul class="nav nav-pills">
    <li <?= ($action === 'index') ? 'class="active"' : '' ?>>
        <a href="<?= $this->context->getReturnUrl(['/admin/' . $module . '/brand']) ?>">
            <? if ($action === 'edit') : ?>
                <i class="glyphicon glyphicon-chevron-left fs-12"></i>
            <? endif; ?>
            <?= Yii::t('admin', 'Список брендов') ?>
        </a>
    </li>
    <li <?= ($action === 'create') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/' . $module . '/brand/create']) ?>"><?= Yii::t('admin', 'Создать бренд') ?></a></li>
</ul>
<br>