<?

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use admin\widgets\Menu;

$this->registerLinkTag(['rel' => 'canonical', 'href' => Url::to(Yii::$app->request->getPathInfo(), true)]);

$this->title = $brand->seo('title');

$this->params['description'] = $brand->seo('description');
$this->params['keywords'] = $brand->seo('keywords');

$this->params['breadcrumbs'][] = ['label' => Yii::t('admin/catalog', 'Бренды'), 'url' => ['/brand']];
$this->params['breadcrumbs'][] = $brand->title;
?>
<h1 class="page-header"><?= $brand->seo('h1') ?></h1>
<div class="row">
    <div class="col-md-2 col-sm-3">
        <div class="row">  
            <?
            foreach ($brand->categories() as $category) {
                $menu_items[] = ['label' => $category->title . ' ' . $brand->title, 'url' => Url::to(['/catalog', 'slug' => $category->slug . '_' . $brand->slug])];
            }

            if (count($menu_items) > 0) {
                ?>
                <div class="col-ss-12 col-xs-6 col-sm-12 col-md-12">
                    <div class="border p-10 pl-20 pr-20 mb-20 bg-first">
                        <?=
                        Menu::widget(['items' => $menu_items,
                            'options' => [
                                'class' => 'list-unstyled',
                            ],
                            'linkActiveTemplate' => '<div class="fs-16 mt-10 lh-16"><b><a href="{url}">{label}</a></b></div>',
                            'linkTemplate' => '<div class="fs-16 mt-10 lh-16"><a href="{url}">{label}</a></div>',
                            'route' => ltrim(Yii::$app->request->pathInfo, '/')
                        ]);
                        ?>
                    </div> 
                </div>
                <?
            }
            ?>
            <noindex>
                <div class="col-ss-12 col-xs-6 col-sm-12 col-md-12">
                    <div class="border p-15 mb-20">
                        <? $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['/brand', 'slug' => $brand->slug])]); ?>
                        <?= $form->field($filterForm, 'price_from')->textInput(['class' => 'form-control input-sm']) ?>
                        <?= $form->field($filterForm, 'price_to')->textInput(['class' => 'form-control input-sm']) ?>
                        <?= $form->field($filterForm, 'color')->dropDownList(\admin\helpers\Color::ymlColorsOptions(Yii::t('admin', '(не выбрано)')), ['class' => 'form-control input-sm']) ?>
                        <?= Html::submitButton('Применить', ['class' => 'btn btn-primary btn-block ']) ?>
                        <? ActiveForm::end(); ?>
                    </div> 
                </div>
            </noindex>
        </div>
    </div>
    <div class="col-md-10 col-sm-9">
        <?=
        $this->render('@admin/modules/catalog/views/api/catalog/_category', [
            'category' => $brand,
            'groups' => $groups,
            'addToCartForm' => $addToCartForm,
            'filterForm' => $filterForm,
            'sort' => $sort,
            'pagination' => $pagination,
        ])
        ?>
        <? if ($showDescription) { ?>
            <div class="row">
                <div class="col-md-12 text-justify">
                    <?= $brand->description ?>
                </div>
            </div>
        <? } ?>
    </div> 
</div>