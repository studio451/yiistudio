<?

use yii\helpers\StringHelper;
use yii\helpers\Url;
use admin\modules\feedback\models\Feedback;

$this->title = Yii::t('admin/feedback', 'Сообщения из форм обратной связи');
$module = $this->context->module->id;
?>

<?= $this->render('_menu') ?>

<? if ($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th width="40">#</th>
                <th width="300"><?= Yii::t('admin', 'Тип сообщения') ?></th>
                <th><?= Yii::t('admin', 'Данные') ?></th>                 
                <th width="150"><?= Yii::t('admin', 'Дата') ?></th>
                <th width="100"><?= Yii::t('admin/feedback', 'Ответ') ?></th>
                <th width="30"></th>
            </tr>
        </thead>
        <tbody>
            <? foreach ($data->models as $item) : ?>
                <tr>
                    <td><a href="<?= Url::to(['/admin/' . $module . '/a/view', 'id' => $item->primaryKey]) ?>"><?= $item->primaryKey ?></a></td>
                    <? if ($item->type == Feedback::TYPE_FEEDBACK) { ?>
                        <td><a href="<?= Url::to(['/admin/' . $module . '/a/view', 'id' => $item->primaryKey]) ?>"><b><i class="fa fa-comment"></i></b> <?= Yii::t('admin', 'Сообщение из формы обратной связи') ?></a></td>
                    <? } else { ?>
                        <td><a href="<?= Url::to(['/admin/' . $module . '/a/view', 'id' => $item->primaryKey]) ?>"><b><i class="fa fa-phone"></i></b> <?= Yii::t('admin', 'Клиент просит перезвонить') ?></a></td>
                    <? } ?>
                    <? if ($item->type == Feedback::TYPE_FEEDBACK) { ?>
                        <td> <span class="text-muted"><?= Yii::t('admin', 'Имя') ?>:</span> <?= $item->name ?> <br><span class="text-muted"><?= Yii::t('admin', 'Текст') ?>:</span> <?= ($this->context->module->settings['enableTitle'] && $item->title != '') ? $item->title : StringHelper::truncate($item->text, 64, '...') ?></td>
                    <? } else { ?>
                        <td> <span class="text-muted"><?= Yii::t('admin', 'Тел.') ?>:</span> <?= $item->phone ?> <br>&nbsp;</td>
                    <? } ?>
                    <td><?= Yii::$app->formatter->asDatetime($item->time, 'short') ?></td>
                    <td>
                        <? if ($item->status == Feedback::STATUS_ANSWERED) : ?>
                            <span class="text-success"><?= Yii::t('admin', 'Да') ?></span>
                        <? else : ?>
                            <span class="text-danger"><?= Yii::t('admin', 'Нет') ?></span>
                        <? endif; ?>
                    </td>
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