<?

use yii\helpers\Url;

$this->title = Yii::t('admin/yml', 'Карта сайта');

$module = $this->context->module->id;
?>
<?= $this->render('_menu') ?>
<? if ($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th width="50">#</th>
                <th><?= Yii::t('admin/sitemap', 'Модели для генерации карты сайта') ?></th>
                <th width="30"></th>
            </tr>
        </thead>
        <tbody>
            <? foreach ($data->models as $item) : ?>
                <tr>
                    <td><?= $item->primaryKey ?></td>
                    <td><a href="<?= Url::to(['/admin/' . $module . '/a/edit', 'id' => $item->primaryKey]) ?>"><?= $item->class ?></a></td>   
                    <td><a href="<?= Url::to(['/admin/' . $module . '/a/delete', 'id' => $item->primaryKey]) ?>" class="fa fa-times text-red" title="<?= Yii::t('admin', 'Удалить запись') ?>"></a></td>
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
<br>
<br>
<div class='row'>
    <div class="col-md-6">
        <?= Yii::t('admin/sitemap', 'Текущий индексный файл карты сайта:') ?> <a href="<?= Url::to($this->context->module->settings['mainFile'], true) ?>" target="_blank" title="<?= Yii::t('admin/yml', 'Карта сайта') ?>"><?= Url::to($this->context->module->settings['mainFile'], true) ?> <i class="fa fa-external-link"></i></a>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?= Url::to(['/admin/' . $module . '/a/generate']) ?>" class="btn btn-success"><?= Yii::t('admin/sitemap', 'Генерировать карту сайта') ?></a>
    </div>
</div>
<br>