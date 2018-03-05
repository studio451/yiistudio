<?

use yii\helpers\Html;
use admin\modules\feedback\models\Feedback;
use yii\widgets\ActiveForm;

$this->title = Yii::t('admin/feedback', 'Просмотр сообщения');
$this->registerCss('.feedback-view dt{margin-bottom: 10px;}');

if ($model->status == Feedback::STATUS_ANSWERED) {
    $this->registerJs('$(".send-answer").click(function(){return confirm("' . Yii::t('admin/feedback', 'Вы уверены, что хотите послать ответ повторно?') . '");})');
}
?>
<?= $this->render('_menu', ['noanswer' => $model->status == Feedback::STATUS_ANSWERED]) ?>

<dl class="dl-horizontal feedback-view">

    <? if ($model->type == Feedback::TYPE_CALLBACK) {
        ?>
        <dt><b><i class="fa fa-phone"></i></b></dt>
        <dd><b><?= Yii::t('admin', 'Клиент просит перезвонить') ?></b></dd>
    <? } else { ?>
        <dt><b><i class="fa fa-comment"></i></b></dt>
        <dd><b><?= Yii::t('admin', 'Сообщение из формы обратной связи') ?></b></dd>
    <? } ?>
    <dt><?= Yii::t('admin', 'Имя') ?></dt>
    <dd><?= $model->name ?></dd>
    <? if ($model->type == Feedback::TYPE_FEEDBACK) { ?>
        <dt>E-mail</dt>
        <dd><?= $model->email ?></dd>
    <? } ?>
    <? if ($this->context->module->settings['enablePhone'] || $model->type == Feedback::TYPE_CALLBACK) {
        ?>
        <dt><?= Yii::t('admin', 'Телефон') ?></dt>
        <dd><?= $model->phone ?></dd>
    <? } ?>
    <? if ($this->context->module->settings['enableTitle'] && $model->type == Feedback::TYPE_FEEDBACK) : ?>
        <dt><?= Yii::t('admin', 'Название') ?></dt>
        <dd><?= $model->title ?></dd>
    <? endif; ?>

    <dt>IP</dt>
    <dd><?= $model->ip ?> <a href="//freegeoip.net/?q=<?= $model->ip ?>" class="label label-info" target="_blank">info</a></dd>

    <dt><?= Yii::t('admin', 'Дата') ?></dt>
    <dd><?= Yii::$app->formatter->asDatetime($model->time, 'medium') ?></dd>

    <? if ($model->type == Feedback::TYPE_FEEDBACK) { ?>
        <dt><?= Yii::t('admin', 'Текст') ?></dt>
        <dd><?= nl2br($model->text) ?></dd>
    <? } ?>
</dl>
<? if ($model->type == Feedback::TYPE_FEEDBACK) { ?>
    <hr>
    <h3 class="mb-20"><?= Yii::t('admin/feedback', 'Наш ответ: ') ?></h3>

    <? $form = ActiveForm::begin() ?>
    <?= $form->field($model, 'answer_subject') ?>
    <?= $form->field($model, 'answer_text')->textarea(['style' => 'height: 250px']) ?>
    <?= Html::submitButton(Yii::t('admin', 'Отправить ответ'), ['class' => 'btn btn-success send-answer']) ?>
    <? ActiveForm::end() ?>
<? } ?>