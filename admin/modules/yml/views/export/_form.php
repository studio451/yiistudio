<?

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use admin\modules\catalog\models\Brand;
use admin\modules\catalog\models\Category;
use admin\modules\catalog\models\Item;
use admin\modules\yml\YmlModule;
use yii\web\View;
use admin\assets\AdminModuleAsset;

AdminModuleAsset::register($this);

$module = $this->context->module->id;
?>
<?
$form = ActiveForm::begin([
            'enableAjaxValidation' => true,
            'options' => ['class' => 'model-form']
        ]);
?>

<?=
$form->field($model, 'to_excel')->hiddenInput()->label('');
$js[] = "$('#a_YML').on('click', function(){
            $('#export-to_excel').val(0)});";
$js[] = "$('#a_Excel').on('click', function(){
            $('#export-to_excel').val(1)});";
$this->registerJs(implode(PHP_EOL, $js), View::POS_READY);
?>   
<?
$_items = Item::find()->select(['id', 'category_id', 'brand_id'])->where(['in', 'id', (array) $model->items])->asArray()->all();
?>   




<div class="row mb-30">
    <div class="col-sm-8">
        <?= $form->field($model, 'title') ?>
        <?= $form->field($model, 'class') ?>
        <?= $form->field($model, 'status')->dropDownList(['' => Yii::t('admin/yml', '(все)'), '0' => Yii::t('admin', 'не активные'), '1' => Yii::t('admin/yml', 'активные')]) ?>
        <?= $form->field($model, 'count'); ?>        
    </div>
    <div class="col-sm-1">

    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <label class="control-label">Столбцы в excel</label><br>
            <?
            $count = 0;
            foreach (YmlModule::getFields() as $field) {
                $count++;
                if (is_array($field)) {
                    echo $count . '.  ' . $field['attribute'] . '<br>';
                } else {
                    echo $count . '.  ' . $field . '<br>';
                }
            }
            ?>
            <div class="help-block"></div>
        </div>
    </div>
</div>
<div class="row mb-30">
    <div class="col-sm-12">
        <small>
            Укажите бренды, категории или отдельные элементы каталога, которые подлежат выгрузке. При выборе категории и бренда выгружаться будут элементы, которые принадлежат и категории и бренду.
            <br>
            Отдельные элементы будут выгружаться, даже если они не пренадлежат бренду или категории. Если бренд или категория не указаны, то будут выгружены все элементы каталога.
        </small>
    </div>
</div>
<div class="row mb-30">
    <div class="col-sm-4">
        <?= $form->field($model, 'brands')->checkboxList(Brand::listAll('id', 'title'), ['separator' => '<br>']); ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'categories')->checkboxList(Category::listAll('id', 'title'), ['separator' => '<br>']); ?>
    </div>
    <div id="export-catalog" class="col-sm-4">
        <div class="form-group">
            <label class="control-label">Отдельные элементы каталога</label><br>
        </div>
        <input type="hidden" name="Export[items][]">
        <?
        $tree = Category::tree('catalog', true);
        foreach ($tree->children as $node) {
            echo $node->title . '<br/>';
            foreach ($node->children as $node) {
                echo '&nbsp;&nbsp;&nbsp;' . $node->title . '<br>';

                $subQuery = Item::find()->select('brand_id')->where(['category_id' => $node->id]);
                $query = Brand::find()->status(Brand::STATUS_ON)->join('INNER JOIN', ['i' => $subQuery], 'i.brand_id = ' . Brand::tableName() . '.id');

                if (!empty($options['where'])) {
                    $query->andWhere(['is not', 'image', null]);
                }
                if (!empty($options['orderBy'])) {
                    $query->orderBy($options['orderBy']);
                } else {
                    $query->orderBy(['title' => SORT_ASC]);
                }
                $brands = $query->all();
                foreach ($brands as $brand) {

                    $str = "";
                    $expand = "false";
                    foreach ($_items as $_item) {
                        if ($_item['category_id'] == $node->id && $_item['brand_id'] == $brand->id) {
                            $expand = "true";
                        }
                    }
                    if ($expand == "true") {
                        $str .= '<span>';
                        $items = Item::find()->select(['id', 'name', 'article'])->where(['and', ['category_id' => $node->id], ['brand_id' => $brand->id]])->asArray()->all();
                        foreach ($items as $item) {
                            $checked = "";
                            foreach ($_items as $_item) {
                                if ($_item['id'] == $item['id']) {
                                    $checked = 'checked';
                                }
                            }
                            $str .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input ' . $checked . ' type="checkbox" name="Export[items][]" value="' . $item['id'] . '">' . $item['name'] . ' ' . $item['article'] . '<br>';
                        }
                        $str .= '</span>';
                    } else {
                        $str .= '<span></span>';
                    }



                    echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="dotted" data-expand="' . $expand . '" data-category-id="' . $node->id . '" data-brand-id="' . $brand->id . '">' . $brand->title . '<br></span>';
                    echo $str;
                }
            }
        }
        ?>
    </div>
</div>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="<?= $model->to_excel ? '' : 'active' ?>"><a href="#tab_YML" id="a_YML" data-toggle="tab" aria-expanded="true"><?= Yii::t('admin/yml', 'YML') ?></a></li>
        <li class="<?= $model->to_excel ? 'active' : '' ?>"><a href="#tab_Excel" id="a_Excel" data-toggle="tab" aria-expanded="false"><?= Yii::t('admin/yml', 'Excel') ?></a></li>                    
    </ul>
    <div class="tab-content">
        <div class="tab-pane <?= $model->to_excel ? '' : 'active' ?>" id="tab_YML">
            <div class="row mt-20 mb-40">
                <div class="col-sm-12">
                    <?= Yii::t('admin/yml', 'Текущий файл:') ?> <a href="<?= '/exports_yml/yml_' . $model->shop_name . '.xml' ?>" target="_blank" title="<?= '/exports_yml/yml_' . $model->shop_name . '.xml' ?>"><?= Yii::$app->request->serverName . '/exports_yml/yml_' . $model->shop_name . '.xml' ?> <i class="fa fa-external-link"></i></a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-12">
                            <?= $form->field($model, 'shop_name'); ?>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <?= $form->field($model, 'shop_company'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <?= $form->field($model, 'shop_url'); ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                </div> 
                <div class="col-sm-4">
                    <div class="row">
                        <div class="col-sm-12">
                            <?= $form->field($model, 'shop_agency'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <?= $form->field($model, 'shop_email'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?= $form->field($model, 'shop_cpa')->checkbox() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <?= $form->field($model, 'all_delivery_options_cost'); ?>
                </div>
                <div class="col-sm-3">
                    <?= $form->field($model, 'all_delivery_options_days'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?= $form->field($model, 'delivery_free_from'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <?= $form->field($model, 'delivery_options_cost'); ?>
                </div>
                <div class="col-sm-3">
                    <?= $form->field($model, 'delivery_options_days'); ?>
                </div>
            </div>
        </div>
        <div class="tab-pane <?= $model->to_excel ? 'active' : '' ?>" id="tab_Excel">
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'asAttachment')->checkbox(); ?>                         
                </div>
            </div>
        </div>                     
    </div>            
</div>
<div class="row">
    <div class="col-md-6">
        <?= Html::submitButton(Yii::t('admin', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
    </div>
    <div class="col-md-6 text-right">
        <a class="btn btn-success" href="<?= Url::to(['/admin/' . $module . '/export/execute', 'id' => $model->primaryKey]) ?>" title="<?= Yii::t('admin', 'Выполнить экспорт') ?>"><span class="fa fa-arrow-right"></span> <?= Yii::t('admin', 'Выполнить экспорт') ?></a>
    </div>
</div>
<? ActiveForm::end(); ?>