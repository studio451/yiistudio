<?
$action = $this->context->action->id;
$module = $this->context->module->id;
?>
<ul class="nav nav-pills">
    <li <?= ($action === 'index') ? 'class="active"' : '' ?>>
        <a href="<?= $this->context->getReturnUrl(['/admin/'.$module . '/checkout']) ?>">
            <?= Yii::t('admin', 'Список') ?>
        </a>
    </li>
</ul>
<br>