<?

use yii\helpers\Url;

$this->title = Yii::t('admin/file', 'Файлы');

$module = $this->context->module->id;
?>

<?= $this->render('_menu') ?>

<? if ($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th width="50">#</th>
                <th><?= Yii::t('admin', 'Название') ?></th>
                <th width="100"><?= Yii::t('admin/file', 'Размер') ?></th>
                <th width="130"><?= Yii::t('admin/file', 'Кол-во скачиваний') ?></th>
                <th width="150"><?= Yii::t('admin', 'Дата') ?></th>
                <th width="120"></th>
            </tr>
        </thead>
        <tbody>
            <? foreach ($data->models as $item) : ?>
                <tr data-id="<?= $item->primaryKey ?>">
                    <td><?= $item->primaryKey ?></td>
                    <td><a href="<?= Url::to(['/admin/' . $module . '/a/edit', 'id' => $item->primaryKey]) ?>"><?= $item->title ?></a></td>
                    <td><?= Yii::$app->formatter->asShortSize($item->size, 2) ?></td>
                    <td><?= $item->downloads ?></td>
                    <td><?= Yii::$app->formatter->asDatetime($item->time, 'short') ?></td>
                    <td class="control">
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