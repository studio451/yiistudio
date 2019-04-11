<?
use yii\helpers\Url;

$action = $this->context->action->id;
?>
    <ul class="nav nav-pills">
        <li <?= ($action === 'index') ? 'class="active"' : '' ?>>
            <a href="<?= Url::to('/admin/settings') ?>">
                <? if($action === 'edit') : ?>
                    <i class="glyphicon glyphicon-chevron-left fs-12"></i>
                <? endif; ?>
                <?= Yii::t('admin', 'Список') ?>
            </a>
        </li>
        <li <?= ($action === 'create') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/settings/create']) ?>"><?= Yii::t('admin', 'Создать') ?></a></li>
    </ul>
    <br>