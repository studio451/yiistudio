<?

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use admin\modules\yml\YmlModule;

$module = $this->context->module->id;
?>
<?
$form = ActiveForm::begin([
            'enableAjaxValidation' => true,
            'options' => ['class' => 'model-form']
        ]);
?>
<div class='row'>
    <div class="col-sm-6">
        <div class='row'>
            <div class="col-sm-12">
                <?= $form->field($model, 'title') ?>
            </div>
        </div>
        <div class='row'>
            <div class="col-md-11">
                <?= $form->field($model, 'url') ?>                
            </div>
            <div class="col-md-1 text-right">
                <div class="form-group">
                    <label class="control-label">&nbsp</label>
                    <div><a href="<?= $model->url ?>" target="_blank" title="<?= $model->url ?>"><i class="fa fa-external-link"></i></a></div>
                    <div class="help-block"></div>
                </div>                
            </div>
        </div>
        <div class='row'>
            <div class="col-sm-12">
                <?= $form->field($model, 'class') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'max_number_of_uploaded_photos'); ?>
            </div>
        </div>
        <div class='row'>
            <div class="col-sm-12">
                <?= $form->field($model, 'external_name') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'count'); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'asAttachment')->checkbox(); ?>                         
            </div>
        </div>
    </div>
    <div class="col-sm-6">
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
<div class="row mb-60">
    <div class="col-md-6">
        <?= Html::submitButton(Yii::t('admin', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
    </div>
    <div class="col-md-6 text-right">
        <a class="btn btn-success" href="<?= Url::to(['/admin/' . $module . '/import/execute', 'id' => $model->primaryKey]) ?>" title="<?= Yii::t('admin', 'Выполнить импорт в excel') ?>"><span class="fa fa-file-excel-o"></span> <?= Yii::t('admin', 'Выполнить импорт в excel') ?></a>
        <a class="btn btn-danger" href="<?= Url::to(['/admin/' . $module . '/import/execute', 'id' => $model->primaryKey, 'full' => 1]) ?>" title="<?= Yii::t('admin', 'Выполнить импорт') ?>"><span class="fa fa-file-excel-o"></span> <?= Yii::t('admin', 'Выполнить импорт') ?></a>
    </div>
</div>
<? ActiveForm::end(); ?>

<? $form = ActiveForm::begin(['action' => Url::to(['/admin/' . $module . '/import/load-items-from-excel-file', 'id' => $model->primaryKey]), 'options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'importFile')->fileInput()->label(Yii::t('admin/yml', 'Укажите excel файл')) ?>

<div class="row ">
    <div class="col-md-12">
        <?= Html::submitButton(Yii::t('admin', 'Загрузить элементы из excel файла'), ['class' => 'btn btn-primary']) ?>
        <br><small><?= Yii::t('admin', 'Добавляются новые бренды, добавляются новые и обновляются старые элементы каталога, категории должны быть созданы, в категориях должен быть указан "Тип элемента"') ?></small>
    </div>
</div>

<? ActiveForm::end() ?>