<?

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use admin\modules\catalog\models\Brand;
use admin\modules\catalog\models\Category;
use admin\modules\yml\YmlModule;
use yii\web\View;

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


<div class='row'>
    <div class="col-sm-8">
        <?= $form->field($model, 'title') ?>
        <?= $form->field($model, 'class') ?>
        <div class="row">
            <div class="col-md-2">
                <?= $form->field($model, 'status')->dropDownList(['' => Yii::t('admin/yml', '(все)'), '0' => Yii::t('admin', 'не активные'), '1' => Yii::t('admin/yml', 'активные')]) ?>
            </div>
            <div class="col-md-2">
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'brands')->checkboxList(Brand::listAll('id', 'title'), ['separator' => '<br>']); ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'categories')->checkboxList(Category::listAll('id', 'title'), ['separator' => '<br>']); ?>
            </div>    
        </div>
    </div>
    <div class="col-sm-1">
        
    </div>
    <div class="col-sm-3">
        <div class="form-group field-esumki-title required">
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
<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'count'); ?>
    </div>
</div>
<br>
<br>
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