<?

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\grid\DataColumn;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('admin', 'Роли пользователей');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('admin', 'Добавить роль'), ['role-add'], ['class' => 'btn btn-success']) ?>
    </p>
    <?
    $dataProvider = new ArrayDataProvider([
        'allModels' => Yii::$app->authManager->getRoles(),
        'sort' => [
            'attributes' => ['name', 'description'],
        ],
        'pagination' => [
            'pageSize' => 10,
        ],
    ]);
    ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => DataColumn::className(),
                'attribute' => 'name',
                'label' => Yii::t('admin', 'Роль')
            ],
            [
                'class' => DataColumn::className(),
                'label' => Yii::t('admin', 'Разрешения'),
                'format' => ['html'],
                'value' => function($data) {
            return implode('<br>', array_keys(ArrayHelper::map(Yii::$app->authManager->getPermissionsByRole($data->name), 'name', 'name')));
        }
            ],
            [
                'class' => DataColumn::className(),
                'attribute' => 'description',
                'label' => Yii::t('admin', 'Описание')
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' =>
                [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pen"></span>', Url::toRoute(['role-update', 'name' => $model->name]), [
                                    'title' => Yii::t('admin', 'Обновить'),
                                    'data-pjax' => '0',
                        ]);
                    },
                            'delete' => function ($url, $model) {
                        return Html::a('<span class="fa fa-times"></span>', Url::toRoute(['role-delete', 'name' => $model->name]), [
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
        <br>
        <br>
        <br>
        <br>
        <p>
            <a href="<?= Url::to(['/admin/users/rbac-init']) ?>" class="btn btn-danger"><?= Yii::t('admin', 'Инициализация RBAC по-умолчанию. Внимание! Будут удалены все данные о ролях и разрешениях пользователей!') ?></a>
</p>