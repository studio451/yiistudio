<?

use admin\helpers\Image;
use admin\widgets\DateTimePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use admin\widgets\Redactor;
use admin\modules\seo\widgets\SeoTextForm;
use admin\modules\catalog\models\Brand;
use admin\modules\catalog\models\Category;
use admin\modules\catalog\models\Item;
use admin\widgets\TagsInput;

$settings = $this->context->module->settings;
$module = $this->context->module->id;
?>

<?
$form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
        ]);
?>
<h1 class="mb-20"><?= $model->title ?></h1>
<? if ($model->external_name) { ?>
    <div class="row mb-20">
        <div class="col-md-6"><span class="label label-danger"><?= Yii::t('admin/catalog', 'Позиция из внешнего источника') ?>: <?= $model->external_name ?> <?= $model->external_id ?></span> </div>
    </div>
<? } ?>
<?
if ($model->primaryKey) {
    ?>
    <div class="row mb-20">
        <div class="col-md-6">
            <span style="vertical-align: super;"><?= Yii::t('admin', 'Активность вкл./выкл.') ?>:</span>
            <?=
            Html::checkbox('', $model->status == Item::STATUS_ON, [
                'class' => 'switch',
                'data-id' => $model->primaryKey,
                'data-link' => Url::to(['/admin/catalog/item']),
            ]);
            ?>
        </div>
    </div>
    <?
}
?>

<div class="row mb-20">
    <div class="col-md-10">

        <div class="row">
            <div class="col-md-3"><?= $form->field($model, 'category_id')->dropDownList(Category::listAll('id', 'title'), ['multiple' => false]) ?></div>
            <div class="col-md-2"><?= $form->field($model, 'brand_id')->dropDownList(Brand::listAll('id', 'title'), ['multiple' => false]) ?></div>
            <div class="col-md-2"><?= $form->field($model, 'name') ?></div>
            <div class="col-md-2"><?= $form->field($model, 'article') ?></div>            
        </div>
        <div class="row">
            <div class="col-md-2"><?= $form->field($model, 'base_price') ?></div>  
            <div class="col-md-2"><?= $form->field($model, 'price') ?></div>
            <div class="col-md-2"><?= $form->field($model, 'discount') ?></div>
        </div>
        <div class="row">             
            <div class="col-md-2"><?= $form->field($model, 'available') ?></div>
            <div class="col-md-2"><?= $form->field($model, 'new') ?></div>
            <div class="col-md-2"><?= $form->field($model, 'gift') ?></div>
        </div>        
    </div>
    <div class="col-md-2">
        <div class="col-md-12 text-right">
            <? if ($settings['itemThumb']) : ?>
                <?
                if ($model->image) {
                    ?>
                    <img src="<?= Image::thumb($model->image, 180) ?>">       
                <? } ?>
            <? endif; ?>
        </div>
    </div>
</div>
<div class="row mb-20">
    <div class="col-md-6">
        <? if ($settings['itemDescription']) : ?>
            <?=
            $form->field($model, 'description')->widget(Redactor::className(), [
                'options' => [
                    'imageUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'catalog'], true),
                    'fileUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'catalog'], true),
                ]
            ])
            ?>
        <? endif; ?>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'slug') ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'time')->widget(DateTimePicker::className()); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= SeoTextForm::widget(['model' => $model]) ?>  
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'tagNames')->widget(TagsInput::className()) ?>
            </div>
        </div>
    </div>   
    <div class="col-md-1">
    </div>
    <div class="col-md-5">
        <?= $dataForm ?>
    </div>     
</div>
<div class="row mb-20">
    <div class="col-md-3">
        <?= Html::submitButton(Yii::t('admin', 'Сохранить'), ['class' => 'btn btn-primary btn-block']) ?>      
    </div>
    <div class="col-md-9">
    </div>
</div>
<? ActiveForm::end(); ?>
