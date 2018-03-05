<?

use yii\helpers\Url;

$this->title = Yii::t('admin/payment', 'Операции оплаты');

$module = $this->context->module->id;
?>

<?= $this->render('_menu') ?>

<? if ($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th width="50px">#</th>
                <th width="150px"><?= Yii::t('admin', 'Дата') ?></th>
                <th width="150px"><?= Yii::t('admin', 'Статус') ?></th>                
                <th><?= Yii::t('admin', 'Описание') ?></th>
                <th><?= Yii::t('admin', 'Заказ') ?></th>
                <th width="120"></th>
            </tr>
        </thead>
        <tbody>
            <? foreach ($data->models as $item) : ?>               
                <tr data-id="<?= $item->primaryKey ?>">
                    <td><?= $item->primaryKey ?></td>
                    <td><?= Yii::$app->formatter->asDatetime($item->time, 'short') ?></td>
                    <td><?= $item->renderStatus() ?></td>                    
                    <td><?= $item->description ?> <a href="javascript:void(0);" data-toggle="modal" data-target="#requestModal_<?= $item->id ?>"><i class="fa fa-info-circle"></i></a><?= $item->renderModal('requestModal_' . $item->id) ?></td>
                    <td>    
                        <? if ($item->order_id) { ?>
                            <a href="<?= Url::to(['/admin/shopcart/a/edit', 'id' => $item->order_id]) ?>" title="<?= Yii::t('admin', 'Удалить запись') ?>"><?= Yii::t('admin', 'Заказ №') ?><?= $item->order_id ?></a>
                        <? } else {?>
                            -
                        <? } ?>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">                            
                            <a href="<?= Url::to(['/admin/' . $module . '/checkout/delete', 'id' => $item->primaryKey]) ?>" class="text-red" title="<?= Yii::t('admin', 'Удалить запись') ?>"><i class="fa fa-times"></i></a>
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