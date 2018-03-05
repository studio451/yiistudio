<?
use yii\helpers\Html;
use yii\helpers\Url;
use admin\modules\subscribe\models\History;

$this->title = Yii::t('admin/subscribe', 'Просмотр истории рассылки');
$module = $this->context->module->id;
?>
<?= $this->render('_menu') ?>

<div class="row mb-20">
        <div class="col-md-6">
            <span style="vertical-align: super;"><?= Yii::t('admin', 'Статус: не рассылалась/рассылалась') ?>:</span>
            <?=
            Html::checkbox('', $model->status == History::STATUS_ON, [
                'class' => 'switch',
                'data-id' => $model->primaryKey,
                'data-link' => Url::to(['/admin/' . $module . '/a']),
            ]);
            ?>
        </div>
    </div>
<?= $this->render('_form', ['model' => $model]) ?>
<br>
<br>
<?= Html::a('<i class="fa fa-envelope"></i> ' . Yii::t('admin/subscribe', 'Разослать подписчикам'), ['/admin/' . $module . '/a/send', 'id' => $model->id],['class' => 'btn btn-success']) ?>