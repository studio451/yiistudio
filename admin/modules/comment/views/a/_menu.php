<?

use yii\helpers\Url;

$action = $this->context->action->id;
$module = $this->context->module->id;
?>
<ul class="nav nav-pills">
    <li <?= ($action == 'index') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/' . $module . '/a/index']) ?>"><? if ($action != 'index') echo '<i class="glyphicon glyphicon-chevron-left fs-12"></i> ' ?><?= Yii::t('admin/comment','Комментарии') ?></a></li>
</ul>
<br>