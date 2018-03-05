<?

use admin\modules\sale\models\Sale;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('admin/sale', 'Акции');

$module = $this->context->module->id;
?>

<?= $this->render('_menu') ?>

<? if ($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th width="50">#</th>
                <th><?= Yii::t('admin', 'Название') ?></th>
                <th width="120"><?= Yii::t('admin', 'Просмотров') ?></th>
                <th width="100"><?= Yii::t('admin', 'Статус') ?></th>
                <th width="120"></th>
            </tr>
        </thead>
        <tbody>
            <? foreach ($data->models as $item) : ?>
                <tr data-id="<?= $item->primaryKey ?>">
                    <td><?= $item->primaryKey ?></td>
                    <td><a href="<?= Url::to(['/admin/' . $module . '/a/edit', 'id' => $item->primaryKey]) ?>"><?= $item->title ?></a></td>
                    <td><?= $item->views ?></td>
                    <td class="status">
                        <?=
                        Html::checkbox('', $item->status == Sale::STATUS_ON, [
                            'class' => 'switch',
                            'data-id' => $item->primaryKey,
                            'data-link' => Url::to(['/admin/' . $module . '/a']),
                        ])
                        ?>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="<?= Url::to(['/admin/' . $module . '/a/up', 'id' => $item->primaryKey]) ?>" class="btn btn-default move-up" title="<?= Yii::t('admin', 'Переместить вверх') ?>"><span class="fa fa-arrow-up"></span></a>
                            <a href="<?= Url::to(['/admin/' . $module . '/a/down', 'id' => $item->primaryKey]) ?>" class="btn btn-default move-down" title="<?= Yii::t('admin', 'Переместить вниз') ?>"><span class="fa fa-arrow-down"></span></a>
                            <a href="<?= Url::to(['/admin/' . $module . '/a/delete', 'id' => $item->primaryKey]) ?>" class="btn btn-default text-red" title="<?= Yii::t('admin', 'Удалить запись') ?>"><span class="fa fa-times"></span></a>
                        </div>
                    </td>
                </tr>
            <? endforeach; ?>
        </tbody>
    </table>
    <?=
    yii\widgets\LinkPager::widget([
        'pagination' => $data->pagination
    ])
    ?>
<? else : ?>
    <p><?= Yii::t('admin', 'Записи не найдены') ?></p>
<? endif; ?>