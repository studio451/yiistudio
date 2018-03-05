<?
use yii\helpers\Url;

$action = $this->context->action->id;
$module = $this->context->module->id;
?>

<ul class="nav nav-tabs">
    <li <?= ($action === 'edit') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/'.$module.'/a/edit', 'id' => $model->primaryKey]) ?>"><?= Yii::t('admin/payment', 'Редактировать службы доставки') ?></a></li>
</ul>
<br>