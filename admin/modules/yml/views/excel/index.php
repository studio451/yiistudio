<?

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('admin/yml', 'Работа с excel файлами');

$module = $this->context->module->id;
?>
<? $form = ActiveForm::begin(['action' => Url::to(['/admin/' . $module . '/excel/load-categories-from-excel-file']), 'options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'importFile')->fileInput()->label(Yii::t('admin/yml', 'Укажите excel файл')) ?>

<div class="row">
    <div class="col-md-12">
        <?= Html::submitButton(Yii::t('admin', 'Загрузить категории из excel файла'), ['class' => 'btn btn-primary']) ?>
        <br><small><?= Yii::t('admin', 'Добавляются новые и обновляются существующие категории каталога, колонки "Название", "Тип элемента"') ?></small>
    </div>
</div>

<? ActiveForm::end() ?>
<br>
<br>
<br>
<br>
<? $form = ActiveForm::begin(['action' => Url::to(['/admin/' . $module . '/excel/add-items-from-excel-file']), 'options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'importFile')->fileInput()->label(Yii::t('admin/yml', 'Укажите excel файл')) ?>

<div class="row">
    <div class="col-md-12">
        <?= Html::submitButton(Yii::t('admin', 'Добавить новые элементы из excel файла'), ['class' => 'btn btn-success']) ?>
        <br><small><?= Yii::t('admin', 'Добавляются новые бренды, добавляются новые элементы каталога, при совпадении кода с существующим элементом, обовления не будет, категории должны быть созданы, в категориях должен быть указан "Тип элемента"') ?></small>
    </div>
</div>

<? ActiveForm::end() ?>
<br>
<br>
<br>
<br>
<? $form = ActiveForm::begin(['action' => Url::to(['/admin/' . $module . '/excel/update-items-from-excel-file']), 'options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'importFile')->fileInput()->label(Yii::t('admin/yml', 'Укажите excel файл')) ?>

<div class="row">
    <div class="col-md-12">
        <?= Html::submitButton(Yii::t('admin', 'Обновить элементы из excel файла'), ['class' => 'btn btn-success']) ?>
        <br><small><?= Yii::t('admin', 'Обновляются существующие элементы каталога, колонки "Закупочная цена","Цена","Статус","Описание" и колонки с доп. характеристиками') ?></small>
    </div>
</div>

<? ActiveForm::end() ?>
<br>
<br>
<br>
<br>
<? $form = ActiveForm::begin(['action' => Url::to(['/admin/' . $module . '/excel/load-news-from-excel-file']), 'options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'importFile')->fileInput()->label(Yii::t('admin/yml', 'Укажите excel файл')) ?>

<div class="row">
    <div class="col-md-12">
        <?= Html::submitButton(Yii::t('admin', 'Загрузить новости из excel файла'), ['class' => 'btn btn-primary']) ?>
        <br><small><?= Yii::t('admin', 'Добавляются новые и обновляются существующие новости колонки "Дата", "Название", "Текст"') ?></small>
    </div>
</div>

<? ActiveForm::end() ?>
<br>
<br>
<br>
<br>
<? $form = ActiveForm::begin(['action' => Url::to(['/admin/' . $module . '/excel/load-users-from-excel-file']), 'options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'importFile')->fileInput()->label(Yii::t('admin/yml', 'Укажите excel файл')) ?>

<div class="row">
    <div class="col-md-12">
        <?= Html::submitButton(Yii::t('admin', 'Загрузить пользователей из excel файла'), ['class' => 'btn btn-primary']) ?>
        <br><small><?= Yii::t('admin', 'Добавляются новые и обновляются существующие пользователи колонки "Email", "Имя"') ?></small>
    </div>
</div>

<? ActiveForm::end() ?>
