<?
use yii\helpers\Url;

$action = $this->context->action->id;
$module = $this->context->module->id;

$historyUrl = Url::to(['/admin/'.$module.'/a/history']);

?>
<ul class="nav nav-pills">
    <li <?= ($action === 'index') ? 'class="active"' : '' ?>>
        <a href="<?= Url::to(['/admin/'.$module]) ?>"><?= Yii::t('admin/subscribe', 'Подписчики') ?></a>
    </li>
    <li <?= ($action === 'create') ? 'class="active"' : '' ?>>
        <a href="<?= Url::to(['/admin/'.$module.'/a/create']) ?>">
        <?= Yii::t('admin/subscribe', 'Создать рассылку') ?>
        </a>
    </li>
    <li <?= ($action === 'history') ? 'class="active"' : '' ?>>
        <a href="<?= $historyUrl ?>">
            <? if($action === 'edit') : ?>
                <i class="fa fa-chevron-left fs-12"></i>
            <? endif; ?>
            <?= Yii::t('admin/subscribe', 'Рассылки') ?>
        </a>
    </li>
</ul>
<br>
