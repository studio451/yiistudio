<?

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this \yii\web\View */
/* @var $commentModel \admin\modules\comment\models\Comment */
/* @var $encryptedEntity string */
/* @var $formId string comment form id */
?>
<div class="comment-form-container">
    <? $form = ActiveForm::begin([
        'options' => [
            'id' => $formId,
            'class' => 'comment-box',
        ],
        'action' => Url::to(['/comment/create', 'entity' => $encryptedEntity]),
        'validateOnChange' => false,
        'validateOnBlur' => false,
    ]); ?>

    <? echo $form->field($commentModel, 'content', ['template' => '{input}{error}'])->textarea(['placeholder' => Yii::t('admin/comment', 'Добавить комментарий...'), 'rows' => 4, 'data' => ['comment' => 'content']]) ?>
    <? echo $form->field($commentModel, 'parentId', ['template' => '{input}'])->hiddenInput(['data' => ['comment' => 'parent-id']]); ?>
    <div class="comment-box-partial">
        <div class="button-container show">
            <? echo Html::a(Yii::t('admin/comment', 'Нажмите здесь, чтобы отменить ответ'), '#', ['id' => 'cancel-reply', 'class' => 'pull-right', 'data' => ['action' => 'cancel-reply']]); ?>
            <? echo Html::submitButton(Yii::t('admin/comment', 'Опубликовать комментарий'), ['class' => 'btn btn-primary']); ?>
        </div>
    </div>
    <? $form->end(); ?>
    <div class="clearfix"></div>
</div>
