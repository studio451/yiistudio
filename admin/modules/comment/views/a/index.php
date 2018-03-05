<?

use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\StringHelper;
use yii\widgets\Pjax;
use admin\modules\comment\moderation\enums\Status;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \admin\modules\comment\models\search\CommentSearch */
/* @var $commentModel \admin\modules\comment\models\Comment */

$this->title = Yii::t('admin/comment', 'Комментарии');
?>
<?= $this->render('_menu') ?>
<div class="comment-index">
    <? Pjax::begin(['timeout' => 10000]); ?>
    <?
    echo GridView::widget([
        'condensed' => true,
        'export' => false,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
                [
                'header' => '#',
                'vAlign' => GridView::ALIGN_MIDDLE,
                'attribute' => 'id',
                'width' => '30px'
            ],
                [
                'attribute' => 'content',
                'content' => function ($model, $key, $index, $widget) {
                    return '<a href="' . Url::to(['/admin/comment/a/edit', 'id' => $model->primaryKey]) . '">' . StringHelper::truncate($model->content, 100) . '</a>';
                },                
            ],
                [
                'attribute' => 'createdBy',
                'width' => '200px;',
                'value' => function ($model) {
                    return $model->author->email;
                },
                'filter' => $commentModel::getAuthors(),
                'filterInputOptions' => ['prompt' => Yii::t('admin/comment', 'Выберите автора'), 'class' => 'form-control'],
            ],
                [
                'attribute' => 'status',
                'width' => '200px;',
                'value' => function ($model) {
                    return Status::getLabel($model->status);
                },
                'filter' => Status::listData(),
                'filterInputOptions' => ['prompt' => Yii::t('admin/comment', 'Выберите статус'), 'class' => 'form-control'],
            ],
                [
                'attribute' => 'createdAt',
                'width' => '200px;',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDatetime($model->createdAt, 'short');
                },
                'filter' => false,
            ],
                ['class' => 'kartik\grid\ActionColumn',
                'template' => '{delete} ',
                'deleteOptions' => ['label' => '<i class="fa fa-times"></i>'],
            ],
        ],
    ]);
    ?>
    <? Pjax::end(); ?>
</div>
