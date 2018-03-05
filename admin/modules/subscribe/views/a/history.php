<?
use yii\helpers\Html;
use yii\helpers\Url;
use admin\modules\subscribe\models\History;

$this->title = Yii::t('admin/subscribe', 'История');

$module = $this->context->module->id;
?>

<?= $this->render('_menu') ?>

<? if ($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th width="50">#</th>
                <th><?= Yii::t('admin/subscribe', 'Тема') ?></th>
                <th width="150"><?= Yii::t('admin', 'Дата') ?></th>
                <th width="120"><?= Yii::t('admin/subscribe', 'Отправлено') ?></th>
                <th width="100"><?= Yii::t('admin', 'Статус') ?></th>
                <th width="120"></th>
            </tr>
        </thead>
        <tbody>
            <? foreach ($data->models as $item) : ?>
                <tr>
                    <td><?= $item->primaryKey ?></td>
                    <td><a href="<?= Url::to(['/admin/' . $module . '/a/edit', 'id' => $item->primaryKey]) ?>"><?= $item->subject ?></a></td>
                    <td><?= Yii::$app->formatter->asDatetime($item->time, 'short') ?></td>
                    <td><?= $item->sent ?></td>
                    <td class="status">
                        <?=
                        Html::checkbox('', $item->status == History::STATUS_ON, [
                            'class' => 'switch',
                            'data-id' => $item->primaryKey,
                            'data-link' => Url::to(['/admin/' . $module . '/a']),
                        ])
                        ?>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">                            
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