<?

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\grid\GridView;

$this->title = Yii::t('admin', 'Бэкапы');
?>
<?= $this->render('_menu') ?>

<br>
<br>
<?
$form = ActiveForm::begin([
            'action' => ['create'],
            'method' => 'post',
            'layout' => 'inline',
        ])
?>

<?= $form->field($model, 'isArchive')->checkbox() ?>

<?= $form->field($model, 'schemaOnly')->checkbox() ?>

<? if ($model->hasPresets()): ?>
    <?= $form->field($model, 'preset')->dropDownList($model->getCustomOptions(), ['prompt' => '']) ?>
<? endif ?>

<?= Html::submitButton(Yii::t('admin', 'Создать дамп'), ['class' => 'btn btn-success']) ?>

<? ActiveForm::end() ?>
<br>
<br>
<?=
GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'type',
            'label' => Yii::t('admin', 'Тип'),
        ],
        [
            'attribute' => 'name',
            'label' => Yii::t('admin', 'Название'),
        ],
        [
            'attribute' => 'size',
            'label' => Yii::t('admin', 'Размер'),
        ],
        [
            'attribute' => 'create_at',
            'label' => Yii::t('admin', 'Дата создания'),
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{download}&nbsp;&nbsp;{restore}&nbsp;&nbsp;{delete}',
            'buttons' => [
                'download' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-download"></span>', [
                                'download',
                                'id' => $model['id'],
                                    ], [
                                'title' => Yii::t('admin', 'Скачать'),
                                'class' => '',
                    ]);
                },
                        'restore' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-import"></span>', [
                                'select',
                                'id' => $model['id'],
                                    ], [
                                'title' => Yii::t('admin', 'Восстановить'),
                                'class' => '',
                    ]);
                },
                        'delete' => function ($url, $model) {
                    return Html::a('<span class="fa fa-times"></span>', [
                                'delete',
                                'id' => $model['id'],
                                    ], [
                                'title' => Yii::t('admin', 'Удалить'),
                                'data-method' => 'post',
                                'data-confirm' => Yii::t('admin', 'Вы уверены?'),
                                'class' => '',
                    ]);
                },
                    ],
                ],
            ],
        ])
        ?>
        <br>
        <?=
        Html::a(Yii::t('admin', 'Удалить все'), ['delete-all'], [
            'class' => 'btn btn-danger',
            'data-method' => 'post',
            'data-confirm' => Yii::t('admin', 'Вы уверены?'),
                ]
        )
        ?>
            