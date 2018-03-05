<?

use yii\helpers\Url;

$this->title = Yii::t('admin/yml', 'Импорты');

$module = $this->context->module->id;
?>
<?= $this->render('_menu') ?>

<? if ($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th width="50">#</th>
                <th><?= Yii::t('admin', 'Название') ?></th>
                <th><?= Yii::t('admin', 'URL') ?></th>
                <th width="30"></th>
            </tr>
        </thead>
        <tbody>
            <? foreach ($data->models as $item) : ?>
                <tr>
                    <td><?= $item->primaryKey ?></td>
                    <td><a href="<?= Url::to(['/admin/' . $module . '/import/edit', 'id' => $item->primaryKey]) ?>"><?= $item->title ?></a></td>   
                    <td><a target="_blank" href="<?= $item->url ?>"><?= $item->url ?></a></td>  
                    <td><a href="<?= Url::to(['/admin/' . $module . '/import/delete', 'id' => $item->primaryKey]) ?>" class="fa fa-times text-red" title="<?= Yii::t('admin', 'Удалить запись') ?>"></a></td>
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