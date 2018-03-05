<?

use yii\helpers\Html;
use yii\helpers\Url;
use admin\modules\editable\Editable;

/* @var $this \yii\web\View */
/* @var $model \admin\modules\comment\models\Comment */
/* @var $maxLevel null|integer comment max level */
?>
<li class="comment" id="comment-<? echo $model->id; ?>">
    <div class="comment-content" data-comment-content-id="<? echo $model->id ?>">
        <div class="comment-details">
            <div class="comment-action-buttons">
                <? if (Yii::$app->getUser()->can('admin')) : ?>
                    <? echo Html::a('<span class="fa fa-trash"></span> ' . Yii::t('admin/comment', 'Удалить'), '#', ['data' => ['action' => 'delete', 'url' => Url::to(['/comment/delete', 'id' => $model->id]), 'comment-id' => $model->id]]); ?>
                <? endif; ?>
                <? if (!Yii::$app->user->isGuest && ($model->level < $maxLevel || is_null($maxLevel))) : ?>
                    <? echo Html::a("<span class='fa fa-share'></span> " . Yii::t('admin/comment', 'Ответить'), '#', ['class' => 'comment-reply', 'data' => ['action' => 'reply', 'comment-id' => $model->id]]); ?>
                <? endif; ?>
            </div>
            <div class="comment-author-name">
                <span><? echo $model->getAuthorName(); ?></span>
                <? echo Html::a($model->getPostedDate(), $model->getAnchorUrl(), ['class' => 'comment-date']); ?>
            </div>
            <div class="comment-body">
                <? if (Yii::$app->getModule('admin')->activeModules['comment']->settings['enableInlineEdit'] && Yii::$app->getUser()->can('admin')): ?>
                    <? echo Editable::widget([
                        'model' => $model,
                        'attribute' => 'content',
                        'url' => '/comment/default/quick-edit',
                        'options' => [
                            'id' => 'editable-comment-' . $model->id,
                        ],
                    ]); ?>
                <? else: ?>
                    <? echo $model->getContent(); ?>
                <? endif; ?>
            </div>
        </div>
    </div>
</li>
<? if ($model->hasChildren()) : ?>
    <ul class="children">
        <? foreach ($model->getChildren() as $children) : ?>
            <li class="comment" id="comment-<? echo $children->id; ?>">
                <? echo $this->render('_list', ['model' => $children, 'maxLevel' => $maxLevel]) ?>
            </li>
        <? endforeach; ?>
    </ul>
<? endif; ?>
