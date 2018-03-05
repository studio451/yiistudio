<?
use admin\models\Module;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('admin', 'Модули');
?>

<?= $this->render('_menu') ?>

<? if($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
        <tr>
            <th width="50">#</th>
            <th><?= Yii::t('admin', 'Код') ?></th>
            <th><?= Yii::t('admin', 'Название') ?></th>
            <th width="150"><?= Yii::t('admin', 'Иконка') ?></th>
            <th width="100"><?= Yii::t('admin', 'Статус') ?></th>            
            <th width="150"></th>
        </tr>
        </thead>
        <tbody>
        <? foreach($data->models as $module) : ?>
            <tr>
                <td><?= $module->primaryKey ?></td>
                <td><a href="<?= Url::to(['/admin/modules/edit', 'id' => $module->primaryKey]) ?>" title="<?= Yii::t('admin', 'Редактировать') ?>"><?= $module->name ?></a></td>
                <td><?= $module->title ?></td>
                <td>
                    <? if($module->icon) : ?>
                        <span class="fa fa-<?= $module->icon ?>"></span> <?= $module->icon ?>
                    <? endif; ?>
                </td>
                <td class="status">
                    <?= Html::checkbox('', $module->status == Module::STATUS_ON, [
                        'style' => 'display:none',
                        'class' => 'switch',
                        'data-id' => $module->primaryKey,
                        'data-link' => Url::to(['/admin/modules']),
                        'data-reload' => '1'
                    ]) ?>
                </td>                
                <td class="control">
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="<?= Url::to(['/admin/modules/up', 'id' => $module->primaryKey]) ?>" class="btn btn-default" title="<?= Yii::t('admin', 'Переместить вверх') ?>"><span class="fa fa-arrow-up"></span></a>
                        <a href="<?= Url::to(['/admin/modules/down', 'id' => $module->primaryKey]) ?>" class="btn btn-default" title="<?= Yii::t('admin', 'Переместить вниз') ?>"><span class="fa fa-arrow-down"></span></a>
                        <a href="<?= Url::to(['/admin/modules/delete', 'id' => $module->primaryKey]) ?>" class="btn btn-default text-red" title="<?= Yii::t('admin', 'Удалить запись') ?>"><span class="fa fa-times"></span></a>
                    </div>
                </td>
            </tr>
        <? endforeach; ?>
        </tbody>
        <?= yii\widgets\LinkPager::widget([
            'pagination' => $data->pagination
        ]) ?>
    </table>
<? else : ?>
    <p><?= Yii::t('admin', 'Записи не найдены') ?></p>
<? endif; ?>