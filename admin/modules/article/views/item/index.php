<?

use admin\modules\article\models\Item;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('admin/article', 'Статьи');

$module = $this->context->module->id;
?>
<?= $this->render('_menu', ['category' => $model]) ?>

<? if (count($model->items)) : ?>
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
    <? foreach ($model->items as $item) : ?>
                <tr data-id="<?= $item->primaryKey ?>">
                    <td><?= $item->primaryKey ?></td>
                    <td><a href="<?= Url::to(['/admin/' . $module . '/item/edit', 'id' => $item->primaryKey]) ?>"><?= $item->title ?></a></td>
                    <td><?= $item->views ?></td>
                    <td class="status">
                        <?=
                        Html::checkbox('', $item->status == Item::STATUS_ON, [
                            'class' => 'switch',
                            'data-id' => $item->primaryKey,
                            'data-link' => Url::to(['/admin/' . $module . '/item']),
                        ])
                        ?>
                    </td>
                    <td class="text-right">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="<?= Url::to(['/admin/' . $module . '/item/up', 'id' => $item->primaryKey, 'category_id' => $model->primaryKey]) ?>" class="btn btn-default move-up" title="<?= Yii::t('admin', 'Переместить вверх') ?>"><span class="fa fa-arrow-up"></span></a>
                            <a href="<?= Url::to(['/admin/' . $module . '/item/down', 'id' => $item->primaryKey, 'category_id' => $model->primaryKey]) ?>" class="btn btn-default move-down" title="<?= Yii::t('admin', 'Переместить вниз') ?>"><span class="fa fa-arrow-down"></span></a>
                            <a href="<?= Url::to(['/admin/' . $module . '/item/delete', 'id' => $item->primaryKey]) ?>" class="btn btn-default text-red" title="<?= Yii::t('admin', 'Удалить запись') ?>"><span class="fa fa-times"></span></a>
                        </div>
                    </td>
                </tr>
    <? endforeach; ?>
        </tbody>
    </table>
<? else : ?>
    <p><?= Yii::t('admin', 'Записи не найдены') ?></p>
<? endif; ?>