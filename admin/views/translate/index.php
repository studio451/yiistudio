<?

use yii\helpers\Url;
use kartik\grid\GridView;


$this->title = Yii::t('admin', 'Переводы');
$this->params['breadcrumbs'][] = $this->title;
?>

<?=
GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'condensed' => true,
    'export' => false,
    'columns' => [
        ['class' => 'kartik\grid\SerialColumn'],
        [
            'attribute' => 'translateSourceMessage.category',
            'width' => '100px',
        ],
        [
            'attribute' => 'translateSourceMessage.message',
            'width' => '300px',
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'translation',            
            'readonly' => false,
            'editableOptions' =>
            [
                'header' => Yii::t('admin', 'Перевод'),
                'size' => 'lg',
                'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                'placement' => 'bottom',
                'buttonsTemplate' => '{submit}',
                'formOptions' => [
                    'action' => '/admin/translate/update-json',
                ],
            ],
        ],        
        ['class' => 'yii\grid\ActionColumn',
            'visibleButtons' =>
            ['view' => false,
                'update' => true,
                'delete' => true,
            ]
        ],
    ],
]);
?>
<br>
<p>
    <a href="<?= Url::to(['/admin/translate/message-extract']) ?>" class="btn btn-success"><?= Yii::t('admin', 'Выгрузить сообщения для локализации') ?></a>
</p>