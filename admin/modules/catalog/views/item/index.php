<?

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use admin\modules\catalog\models\Item;
use admin\modules\yml\models\Import;
use admin\widgets\GridSelectedRowsAction;
use admin\helpers\Image;

$this->title = Yii::t('admin/catalog', 'Каталог');

$module = $this->context->module->id;
?>
<?= $this->render('_menu', ['category' => $model]) ?>

<div class="row">
    <? $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['/admin/catalog/item', 'id' => $category_id])]); ?>
    <div class="col-md-4">
        <?= $form->field($filterForm, 'brand_id')->dropDownList($brands) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($filterForm, 'status')->dropDownList($status) ?>
    </div>
    <div class="col-md-1">
        <?= $form->field($filterForm, 'price_from') ?>
    </div>
    <div class="col-md-1">
        <?= $form->field($filterForm, 'price_to') ?>
    </div>
    <div class="col-md-4 text-right">
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
            'attribute' => 'image',
            'content' => function ($model, $key, $index, $widget) {
                return '<a href="' . Url::to(['/admin/catalog/item/edit', 'id' => $model->primaryKey]) . '">' . '<img src="' . Image::thumb($model->image, 45, 45, true) . '"></a>';
            },
            'width' => '45px'
        ],
            [
            'attribute' => 'title',
            'vAlign' => GridView::ALIGN_MIDDLE,
            'content' => function ($model, $key, $index, $widget) {
                return '<a href="' . Url::to(['/admin/catalog/item/edit', 'id' => $model->primaryKey]) . '">' . $model->title . '</a>';
            },
            'width' => '300px',
        ],
            [
            'attribute' => 'name',
            'vAlign' => GridView::ALIGN_MIDDLE,
            'width' => '100px',
        ],
            [
            'attribute' => 'article',
            'vAlign' => GridView::ALIGN_MIDDLE,
            'width' => '100px',
        ],
            [
            'attribute' => 'slug',
            'vAlign' => GridView::ALIGN_MIDDLE,
        ],
            [
            'attribute' => 'price',
            'vAlign' => GridView::ALIGN_MIDDLE,
            'width' => '100px',
        ],
            [
            'attribute' => 'status',
            'vAlign' => GridView::ALIGN_MIDDLE,
            'content' => function ($model, $key, $index, $widget) {
                return Html::checkbox('', $model->status == Item::STATUS_ON, [
                            'class' => 'switch',
                            'data-id' => $model->primaryKey,
                            'data-link' => Url::to(['/admin/catalog/item']),
                ]);
            },
            'width' => '50px'
        ],
            ['class' => 'kartik\grid\ActionColumn',
            'template' => '{up}{down}&nbsp;&nbsp;{copy}&nbsp;&nbsp;{delete} ',
            'deleteOptions' => ['label' => '<i class="fa fa-times"></i>'],
            'buttons' => [
                'copy' => function($url, $model, $key) {
                    return Html::a(
                                    '<span class="fa fa-copy">', Url::to(['/admin/catalog/item/copy', 'id' => $model->category_id, 'item_id' => $model->primaryKey]), ['title' => Yii::t('admin', 'Копировать')]
                    );
                },
                'up' => function($url, $model, $key) {
                    return Html::a(
                                    '<span class="fa fa-arrow-up">', Url::to(['/admin/catalog/item/up', 'id' => $model->primaryKey, 'category_id' => $model->category_id]), ['title' => Yii::t('admin', 'Вверх')]
                    );
                },
                'down' => function($url, $model, $key) {
                    return Html::a(
                                    '<span class="fa fa-arrow-down">', Url::to(['/admin/catalog/item/down', 'id' => $model->primaryKey, 'category_id' => $model->category_id]), ['title' => Yii::t('admin', 'Вниз')]
                    );
                }
            ],
        ],
    ],
]);
?>
<div class="row mt-20">
    <div class="col-md-2">
        <?= GridSelectedRowsAction::widget(['grid_id' => 'grid_item', 'buttonOptions' => ['class' => 'btn btn-danger', 'content' => '<i class="fa fa-times"></i> ' . Yii::t('admin', 'Удалить отмеченные'), 'title' => Yii::t('admin', 'Удалить отмеченные')], 'action' => Url::to(['/admin/catalog/item/delete-json'])]); ?>
    </div>   
    <div class="col-md-2">
        <?
        $params = array_merge([0 => '/admin/catalog/item/export-to-excel'], Yii::$app->request->get());
        ?>
        <a class="btn btn-success pull-left" href="<?= Url::to($params) ?>"><i class="fa fa-fw fa-download"></i> <?= Yii::t('admin/catalog', 'Выгрузить категорию в Excel') ?></a>
    </div>   
    <div class="col-md-6 text-right">
        <? $form = ActiveForm::begin(['action' => Url::to(['/admin/yml/excel/update-items-from-excel-file']), 'options' => ['enctype' => 'multipart/form-data']]) ?>
        <?= Html::submitButton('<i class="fa fa-fw fa-upload"></i> ' . Yii::t('admin/catalog', 'Обновить цены и характеристики из Excel'), ['class' => 'btn btn-primary pull-right']) ?>
        <?= $form->field(new Import(['scenario' => 'import']), 'importFile')->fileInput(['class' => 'pull-right'])->label('') ?>
        <? ActiveForm::end() ?><br><small><?= Yii::t('admin', 'Обновляются существующие элементы каталога, колонки "Закупочная цена","Цена","Статус","Описание" и колонки с доп. характеристиками') ?></small>

    </div>     
    <? if (Yii::$app->getModule('admin')->activeModules['yml']->settings['exportYandexMarketId']) { ?>
        <div class="col-md-2 text-right">
            <a class="btn btn-primary" href="<?= Url::to(['/admin/yml/export/execute', 'id' => Yii::$app->getModule('admin')->activeModules['yml']->settings['exportYandexMarketId']]) ?>" title="<?= Yii::t('admin', 'Экспорт на Яндекс.Маркет') ?>"><span class="fa fa-arrow-right"></span> <?= Yii::t('admin', 'Экспорт на Яндекс.Маркет') ?></a>
        </div>
    <? } ?>
</div>




