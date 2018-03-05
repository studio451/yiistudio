<?
use yii\helpers\Html;
use admin\modules\guestbook\models\Guestbook;

$this->title = Yii::t('admin/guestbook', 'Просмотреть запись');
$this->registerCss('.guestbook-view dt{margin-bottom: 10px;}');
?>
<?= $this->render('_menu') ?>

<dl class="dl-horizontal guestbook-view">
    <dt><?= Yii::t('admin', 'Имя') ?></dt>
    <dd><?= $model->name ?></dd>

    <? if($this->context->module->settings['enableTitle']) : ?>
    <dt><?= Yii::t('admin', 'Тема сообщения') ?></dt>
    <dd><?= $model->title ?></dd>
    <? endif; ?>

    <? if($this->context->module->settings['enableEmail']) : ?>
        <dt><?= Yii::t('admin', 'E-mail') ?></dt>
        <dd><?= $model->email ?></dd>
    <? endif; ?>

    <dt>IP</dt>
    <dd><?= $model->ip ?> <a href="//freegeoip.net/?q=<?= $model->ip ?>" class="label label-info" target="_blank">info</a></dd>

    <dt><?= Yii::t('admin', 'Дата') ?></dt>
    <dd><?= Yii::$app->formatter->asDatetime($model->time, 'medium') ?></dd>

    <dt><?= Yii::t('admin', 'Текст сообщения') ?></dt>
    <dd><?= nl2br($model->text) ?></dd>
</dl>

<hr>
<h2><small><?= Yii::t('admin/guestbook', 'Наш ответ') ?></small></h2>

<?= Html::beginForm() ?>
    <div class="form-group">
        <?= Html::textarea('Guestbook[answer]', $model->answer, ['class' => 'form-control', 'style' => 'height: 250px']) ?>
    </div>
    <? if($model->answer == '' && $model->email) : ?>
    <div class="checkbox">
        <label>
            <input type="checkbox" name="mailUser" value="1" checked> <?= Yii::t('admin/guestbook', 'Послать уведомление пользователю об ответе') ?>
        </label>
    </div>
    <? endif; ?>
    <?= Html::submitButton(Yii::t('admin', 'Сохранить'), ['class' => 'btn btn-success send-answer']) ?>
<?= Html::endForm() ?>