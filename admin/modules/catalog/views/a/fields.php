<?
use yii\helpers\Html;
use admin\modules\catalog\assets\FieldsAsset;
use admin\modules\catalog\models\Category;

$this->title = Yii::t('admin/catalog', 'Поля категории: ') . $model->title;

$this->registerAssetBundle(FieldsAsset::className());

$this->registerJs('
var fieldTemplate = \'\
    <tr>\
        <td>'. Html::input('text', null, null, ['class' => 'form-control field-name']) .'</td>\
        <td>'. Html::input('text', null, null, ['class' => 'form-control field-title']) .'</td>\
        <td>\
            <select class="form-control field-type">'.str_replace("\n", "", Html::renderSelectOptions('', Category::$fieldTypes)).'</select>\
        </td>\
        <td><textarea class="form-control field-options" placeholder="'.Yii::t('admin/catalog', 'Укажите варианты через запятую').'" style="display: none;"></textarea></td>\
        <td class="text-right">\
            <div class="btn-group btn-group-sm" role="group">\
                <a href="#" class="btn btn-default move-up" title="'. Yii::t('admin', 'Переместить вверх') .'"><span class="fa fa-arrow-up"></span></a>\
                <a href="#" class="btn btn-default move-down" title="'. Yii::t('admin', 'Переместить вниз') .'"><span class="fa fa-arrow-down"></span></a>\
                <a href="#" class="btn btn-default text-red delete-field" title="'. Yii::t('admin', 'Удалить запись') .'"><span class="fa fa-times"></span></a>\
            </div>\
        </td>\
    </tr>\';
', \yii\web\View::POS_HEAD);
?>
<?= $this->render('@admin/views/category/_menu') ?>
<?= $this->render('_submenu', ['model' => $model]) ?>
<br>
<?= Html::button('<i class="glyphicon glyphicon-plus fs-12"></i> '.Yii::t('admin/catalog', 'Добавить поле'), ['class' => 'btn btn-default', 'id' => 'addField']) ?>

<table id="categoryFields" class="table table-hover">
    <thead>
        <th><?= Yii::t('admin', 'Код') ?></th>
        <th><?= Yii::t('admin', 'Название') ?></th>
        <th><?= Yii::t('admin/catalog', 'Тип') ?></th>
        <th><?= Yii::t('admin/catalog', 'Опции') ?></th>
        <th width="120"></th>
    </thead>
    <tbody>
    <? foreach($model->fields as $field) : ?>
        <tr>
            <td><?= Html::input('text', null, $field->name, ['class' => 'form-control field-name']) ?></td>
            <td><?= Html::input('text', null, $field->title, ['class' => 'form-control field-title']) ?></td>
            <td>
                <select class="form-control field-type">
                    <?= Html::renderSelectOptions($field->type, Category::$fieldTypes) ?>
                </select>
            </td>
            <td>
                <textarea class="form-control field-options" placeholder="<?= Yii::t('admin/catalog', 'Укажите варианты через запятую') ?>" <?= !$field->options ? 'style="display: none;"' : '' ?> ><?= is_array($field->options) ? implode(',', $field->options) : $field->options ?></textarea>
            </td>
            <td class="text-right">
                <div class="btn-group btn-group-sm" role="group">
                    <a href="#" class="btn btn-default move-up" title="<?= Yii::t('admin', 'Переместить вверх') ?>"><span class="fa fa-arrow-up"></span></a>
                    <a href="#" class="btn btn-default move-down" title="<?= Yii::t('admin', 'Переместить вниз') ?>"><span class="fa fa-arrow-down"></span></a>
                    <a href="#" class="btn btn-default text-red delete-field" title="<?= Yii::t('admin', 'Удалить запись') ?>"><span class="fa fa-times"></span></a>
                </div>
            </td>
        </tr>
    <? endforeach; ?>
    </tbody>
</table>
<br>
<?= Html::button('<i class="fa fa-check"></i> '.Yii::t('admin/catalog', 'Сохранить поля'), ['class' => 'btn btn-primary', 'id' => 'saveCategoryBtn']) ?>
