<?

use yii\helpers\Url;

$this->title = Yii::t('admin/catalog', 'Бренды');

$module = $this->context->module->id;
?>

<?= $this->render('_menu') ?>

<? if ($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
            <tr>

                <th width="50">#</th>
                <th width="200"><?= Yii::t('admin', 'Название') ?></th>
                <th><?= Yii::t('admin', 'Краткий текст') ?></th>
                <th width="30"></th>
            </tr>
        </thead>
        <tbody>
    <? foreach ($data->models as $item) : ?>
                <tr>
                    <td><?= $item->primaryKey ?></td>                    
                    <td><a href="<?= Url::to(['/admin/' . $module . '/brand/edit', 'id' => $item->primaryKey]) ?>"><?= $item->title ?></a></td>
                    <td><?= $item->short ?></td>
                    <td><a href="<?= Url::to(['/admin/' . $module . '/brand/delete', 'id' => $item->primaryKey]) ?>" class="fa fa-times text-red" title="<?= Yii::t('admin/catalog', 'Удалить бренд') ?>"></a></td>
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