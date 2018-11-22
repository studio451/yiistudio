<?

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use admin\modules\catalog\models\Item;
use admin\widgets\GridSelectedRowsAction;


$this->title = Yii::t('admin', 'Пользователи');

$module = $this->context->module->id;
?>
<?= $this->render('_menu') ?>

<div class="row">
    <? $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['/admin/users'])]); ?>
    <div class="col-md-3">
        <?= $form->field($filterForm, 'email') ?>
    </div>
    <div class="col-md-3 text-right">
        <div class="form-group">
            <label class="control-label">&nbsp;</label>
            <?= Html::submitButton('<i class="fa fa-fw fa-filter"></i> ' . Yii::t('admin', 'Применить фильтр'), ['class' => 'btn btn-primary form-control']) ?>
            <div class="help-block"></div>
        </div>
    </div>
    <? ActiveForm::end(); ?>
</div>

<?=
GridView::widget([
    'id' => 'grid_item',
    'dataProvider' => $dataProvider,
    'condensed' => true,
    'export' => false,
    'columns' => [
        ['class' => 'kartik\grid\CheckboxColumn'],
        [
            'header' => '#',
            'vAlign' => GridView::ALIGN_MIDDLE,
            'attribute' => 'id',
            'width' => '30px'
        ],
        [
            'attribute' => 'email',
            'vAlign' => GridView::ALIGN_MIDDLE,
            'content' => function ($model, $key, $index, $widget) {
                return '<a href="' . Url::to(['/admin/users/edit', 'id' => $model->id]) . '">' . $model->email . '</a>';
            },
                    'width' => '300px',
                ],
                [
                    'header' => 'Роли',
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'width' => '100px',
                    'content' => function ($model, $key, $index, $widget) {
                        $str = '';
                        foreach (array_keys(Yii::$app->authManager->getRolesByUser($model->id)) as $role) {
                            $str .= $role . '&nbsp;&nbsp;&nbsp;';
                        }
                        return $str;
                    },
                ],
                [
                    'header' => 'Авторизация',
                    'vAlign' => GridView::ALIGN_MIDDLE,
                    'width' => '100px',
                    'content' => function ($model, $key, $index, $widget) {
                        return '<a href="' . Url::to(['/admin/users/login', 'id' => $model->id]) . '">' . Yii::t('admin', 'Авторизоватся') . '</a>';
                    },
                        ],                        
                        [
                            'attribute' => 'status',
                            'vAlign' => GridView::ALIGN_MIDDLE,
                            'content' => function ($model, $key, $index, $widget) {
                                return Html::checkbox('', $model->status == Item::STATUS_ON, [
                                            'class' => 'switch',
                                            'data-id' => $model->primaryKey,
                                            'data-link' => Url::to(['/admin/users']),
                                ]);
                            },
                                    'width' => '50px'
                                ],
                                ['class' => 'kartik\grid\ActionColumn',
                                    'template' => '{edit}&nbsp;&nbsp;{delete} ',
                                    'deleteOptions' => ['label' => '<i class="fa fa-times"></i>'],
                                ],
                            ],
                        ]);
                        ?>
                        <div class="row mt-20">
                            <div class="col-md-2">
                                <?= GridSelectedRowsAction::widget(['grid_id' => 'grid_item', 'buttonOptions' => ['class' => 'btn btn-danger', 'content' => '<i class="fa fa-times"></i> ' . Yii::t('admin', 'Удалить отмеченные'), 'title' => Yii::t('admin', 'Удалить отмеченные')], 'action' => Url::to(['/admin/users/delete-json'])]); ?>
    </div>
</div>