<?
$this->title = Yii::t('admin', 'Логи');
?>

<?= $this->render('_menu') ?>

<? if($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
        <tr>
            <th width="50">#</th>
            <th><?= Yii::t('admin', 'Логин') ?></th>
            <th><?= Yii::t('admin', 'Пароль') ?></th>
            <th>IP</th>
            <th>USER_AGENT</th>
            <th><?= Yii::t('admin', 'Дата') ?></th>
        </tr>
        </thead>
        <tbody>
        <? foreach($data->models as $log) : ?>
            <tr <?= !$log->success ? 'class="danger"' : ''?>>
                <td><?= $log->primaryKey ?></td>
                <td><?= $log->email ?></td>
                <td><?= $log->password ?></td>
                <td><?= $log->ip ?></td>
                <td><?= $log->user_agent ?></td>
                <td><?= Yii::$app->formatter->asDatetime($log->time, 'medium') ?></td>
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