<?

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $model->title;
?>
<?= $this->render('_menu') ?>
<?= $this->render('_submenu', ['model' => $model]) ?>

<? if (sizeof($model->settings) > 0) : ?>
    <?= Html::beginForm(); ?>
    <? foreach ($model->settings as $key => $value) : ?>
        <? if (is_array($value)) : ?>
            <?= Html::hiddenInput('text', 'Settings[' . $key . ']', $value, ['class' => 'form-control']); ?>
        <? elseif (!is_bool($value)) : ?>
            <div class="form-group">
                <label><?= $key; ?></label>
                <?= Html::input('text', 'Settings[' . $key . ']', $value, ['class' => 'form-control']); ?>
            </div>
        <? else : ?>
            <div class="checkbox">
                <label>
                    <?= Html::checkbox('Settings[' . $key . ']', $value, ['uncheck' => 0]) ?> <?= $key ?>
                </label>
            </div>
        <? endif; ?>
    <? endforeach; ?>
    <?= Html::submitButton(Yii::t('admin', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
    <? Html::endForm(); ?>
<? else : ?>
    <?= $model->title ?> <?= Yii::t('admin', 'модуль не имеет никаких настроек') ?>
<? endif; ?>
<a href="<?= Url::to(['/admin/modules/restore-settings', 'id' => $model->id]) ?>" class="pull-right text-warning"><i class="glyphicon glyphicon-flash"></i> <?= Yii::t('admin', 'Восстановить настройки по-умолчанию') ?></a>

<div class="row mt-40">
    <div class="col-md-4">
        <a href="<?= Url::to(['/admin/modules/migrate-down', 'id' => $model->id]) ?>" class="btn btn-default btn-block"><i class="fa fa-angle-double-down"></i> <?= Yii::t('admin', 'Миграция: откат на предыдущую') ?></a>
    </div> 
    <div class="col-md-4">
        <a href="<?= Url::to(['/admin/modules/migrate', 'id' => $model->id]) ?>" class="btn btn-default btn-block"><i class="fa fa-angle-double-up"></i> <?= Yii::t('admin', 'Миграция: накатить следующую') ?></a>
    </div>
   
</div>           