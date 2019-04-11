<?
use yii\helpers\Url;

$action = $this->context->action->id;
$module = $this->context->module->id;
?>
<ul class="nav nav-pills">
    <li <?= ($action === 'index') ? 'class="active"' : '' ?>>
        <a href="<?= Url::to('/admin/' . $module . '/checkout') ?>">
            <?= Yii::t('admin', 'Список') ?>
        </a>
    </li>
</ul>
<br>