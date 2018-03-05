<?
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\grid\DataColumn;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = Yii::t('admin/rbac', 'Разрешения пользователей');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('admin/rbac', 'Добавить новое правило'), ['permission-add'], ['class' => 'btn btn-success']) ?>
    </p>
<?
$dataProvider = new ArrayDataProvider([
      'allModels' => Yii::$app->authManager->getPermissions(),
      'sort' => [
          'attributes' => ['name', 'description'],
      ],
      'pagination' => [
          'pageSize' => 10,
      ],
 ]);
?>

<?=GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'class'     => DataColumn::className(),
            'attribute' => 'name',
            'label'     => Yii::t('admin/rbac', 'Правило')
        ],
        [
            'class'     => DataColumn::className(),
            'attribute' => 'description',
            'label'     => Yii::t('admin/rbac', 'Описание')
        ],
        ['class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            'buttons' =>
                [
                    'update' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::toRoute(['permission-update', 'name' => $model->name]), [
                                        'title' => Yii::t('admin', 'Обновить'),
                                        'data-pjax' => '0',
                                    ]); },
                    'delete' => function ($url, $model) {
                                return Html::a('<span class="fa fa-close"></span>', Url::toRoute(['permission-delete','name' => $model->name]), [
                                        'title' => Yii::t('admin', 'Удалить'),
                                        'data-confirm' => Yii::t('admin', 'Вы уверены, что хотите удалить этот элемент?'),
                                        'data-method' => 'post',
                                        'data-pjax' => '0',
                                    ]);
                        }
                ]
        ],
        ]
    ]);
?>
</div>