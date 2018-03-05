<?
use yii\helpers\Url;

$this->title = Yii::t('admin/subscribe', 'E-mail рассылка');

$module = $this->context->module->id;
?>

<?= $this->render('_menu') ?>

<? if($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th width="50">#</th>
                <th>E-mail</th>
                <th width="150">IP</th>
                <th width="150"><?= Yii::t('admin', 'Дата') ?></th>
                <th width="30"></th>
            </tr>
        </thead>
        <tbody>
    <? foreach($data->models as $item) : ?>
            <tr>
                <td><?= $item->primaryKey ?></td>
                <td><?= $item->email ?></td>
                <td><a href="//freegeoip.net/?q=<?= $item->ip ?>" target="_blank"><?= $item->ip ?></a></td>
                <td><?= Yii::$app->formatter->asDatetime($item->time, 'short') ?></td>
                <td class="control"><a href="<?= Url::to(['/admin/'.$module.'/a/delete-subscriber', 'id' => $item->primaryKey]) ?>" class="fa fa-times text-red" title="<?= Yii::t('admin', 'Удалить запись') ?>"></a></td>
            </tr>
    <? endforeach; ?>
        </tbody>
    </table>
    <?= yii\widgets\LinkPager::widget([
        'pagination' => $data->pagination
    ]) ?>
<? else : ?>
    <p><?= Yii::t('admin', 'Записи не найдены') ?></p>
<? endif; ?>