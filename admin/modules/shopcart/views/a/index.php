<?

use admin\modules\shopcart\models\News;
use admin\modules\shopcart\models\Order;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('admin/shopcart', 'Заказы');

$module = $this->context->module->id;
?>
<?= $this->render('_menu') ?>
<? if ($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th width="100">#</th>
                <th width="50"></th>
                <th width="110"><?= Yii::t('admin', 'Дата') ?></th>
                <th width="110"><?= Yii::t('admin', 'Статус') ?></th>
                <th width="170"><?= Yii::t('admin', 'E-mail') ?></th>
                <th width="170"><?= Yii::t('admin', 'Телефон') ?></th>
                <th width="170"><?= Yii::t('admin', 'Имя') ?></th>
                <th><?= Yii::t('admin', 'Адрес') ?></th>
                <th width="110"><?= Yii::t('admin/shopcart', 'Стоимость') ?></th>
                <th width="90"></th>
            </tr>
        </thead>
        <tbody>
            <? foreach ($data->models as $item) : ?>
                <tr>
                    <td>
                        <?= Html::a(Yii::t('admin/shopcart', 'Заказ') . ' №' . $item->primaryKey, ['/admin/shopcart/a/edit', 'id' => $item->primaryKey]) ?>
                    </td>
                    <td><? if ($item->new) : ?>
                            <span class="label label-warning"><?= Yii::t('admin/shopcart', 'НОВЫЙ!') ?></span>
                        <? endif; ?></td>
                    <td><?= Yii::$app->formatter->asDatetime($item->time, 'short') ?></td>
                    <td><?= $item->renderStatus() ?></td>
                    <td><?= $item->email ?></td>
                    <td><?= $item->phone ?></td>
                    <td><?= $item->name ?></td>
                    <td><?= $item->address ?></td>
                    <td><?= $item->totalCost ?> <i class="fas fa-ruble-sign"></i></td>
                    <td>                            
                        <a href="<?= Url::to(['/admin/' . $module . '/a/delete', 'id' => $item->primaryKey]) ?>" class="text-red" title="<?= Yii::t('admin', 'Удалить запись') ?>"><span class="fa fa-times"></span></a>
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