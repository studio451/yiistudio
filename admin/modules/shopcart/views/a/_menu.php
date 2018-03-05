<?
use yii\helpers\Url;

$action = $this->context->action->id;
$module = $this->context->module->id;

$backTo = null;
$indexUrl = Url::to(['/admin/'.$module]);
$pendingUrl = Url::to(['/admin/'.$module.'/a/pending']);
$processedUrl = Url::to(['/admin/'.$module.'/a/processed']);
$sentUrl = Url::to(['/admin/'.$module.'/a/sent']);
$completedUrl = Url::to(['/admin/'.$module.'/a/completed']);
$failsUrl = Url::to(['/admin/'.$module.'/a/fails']);
$blankUrl = Url::to(['/admin/'.$module.'/a/blank']);

if($action === 'edit')
{
    $returnUrl = $this->context->getReturnUrl($indexUrl);

    if(strpos($returnUrl, 'pending') !== false){
        $backTo = 'pending';
        $processedUrl = $returnUrl;
    } elseif(strpos($returnUrl, 'processed') !== false) {
        $backTo = 'processed';
        $sentUrl = $returnUrl;
    } elseif(strpos($returnUrl, 'sent') !== false) {
        $backTo = 'sent';
        $sentUrl = $returnUrl;
    } elseif(strpos($returnUrl, 'completed') !== false) {
        $backTo = 'completed';
        $completedUrl = $returnUrl;
    } elseif(strpos($returnUrl, 'fails') !== false) {
        $backTo = 'fails';
        $failsUrl = $returnUrl;
    } elseif(strpos($returnUrl, 'blank') !== false) {
        $backTo = 'blank';
        $blankUrl = $returnUrl;
    } else {
        $backTo = 'index';
        $indexUrl = $returnUrl;
    }
}
?>
<ul class="nav nav-pills">
    <li <?= ($action === 'index') ? 'class="active"' : '' ?>>
        <a href="<?= $indexUrl ?>">
            <? if($backTo === 'index') : ?>
                <i class="glyphicon glyphicon-chevron-left fs-12"></i>
            <? endif; ?>
            <?= Yii::t('admin/shopcart', 'Все') ?>
            <? if($this->context->all > 0) : ?>
                <span class="badge"><?= $this->context->all ?></span>
            <? endif; ?>
        </a>
    </li>
    <li <?= ($action === 'pending') ? 'class="active"' : '' ?>>
        <a href="<?= $pendingUrl ?>">
            <? if($backTo === 'pending') : ?>
                <i class="glyphicon glyphicon-chevron-left fs-12"></i>
            <? endif; ?>
            <?= Yii::t('admin/shopcart', 'В обработке') ?>
            <? if($this->context->pending > 0) : ?>
                <span class="badge"><?= $this->context->pending ?></span>
            <? endif; ?>
        </a>
    </li>
    <li <?= ($action === 'processed') ? 'class="active"' : '' ?>>
        <a href="<?= $processedUrl ?>">
            <? if($backTo === 'processed') : ?>
                <i class="glyphicon glyphicon-chevron-left fs-12"></i>
            <? endif; ?>
            <?= Yii::t('admin/shopcart', 'Обработан') ?>
            <? if($this->context->processed > 0) : ?>
                <span class="badge"><?= $this->context->processed ?></span>
            <? endif; ?>
        </a>
    </li>
    <li <?= ($action === 'sent') ? 'class="active"' : '' ?>>
        <a href="<?= $sentUrl ?>">
            <? if($backTo === 'sent') : ?>
                <i class="glyphicon glyphicon-chevron-left fs-12"></i>
            <? endif; ?>
            <?= Yii::t('admin/shopcart', 'Отправлен') ?>
            <? if($this->context->sent > 0) : ?>
                <span class="badge"><?= $this->context->sent ?></span>
            <? endif; ?>
        </a>
    </li>
    <li <?= ($action === 'completed') ? 'class="active"' : '' ?>>
        <a href="<?= $completedUrl ?>">
            <? if($backTo === 'completed') : ?>
                <i class="glyphicon glyphicon-chevron-left fs-12"></i>
            <? endif; ?>
            <?= Yii::t('admin/shopcart', 'Выполнен') ?>
        </a>
    </li>
    <li <?= ($action === 'fails') ? 'class="active"' : '' ?>>
        <a href="<?= $failsUrl ?>">
            <? if($backTo === 'fails') : ?>
                <i class="glyphicon glyphicon-chevron-left fs-12"></i>
            <? endif; ?>
            <?= Yii::t('admin/shopcart', 'Ошибка') ?>
        </a>
    </li>
    <li <?= ($action === 'blank') ? 'class="active"' : '' ?>>
        <a href="<?= $blankUrl ?>">
            <? if($backTo === 'blank') : ?>
                <i class="glyphicon glyphicon-chevron-left fs-12"></i>
            <? endif; ?>
            <?= Yii::t('admin/shopcart', 'Корзины') ?>
        </a>
    </li>
</ul>
<br>