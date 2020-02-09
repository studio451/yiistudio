<?

use admin\helpers\Image;
use admin\models\Photo;
use yii\helpers\Html;
use yii\helpers\Url;
use admin\assets\PhotosAsset;

PhotosAsset::register($this);

$class = get_class($this->context->model);
$item_id = $this->context->model->primaryKey;

$linkParams = [
    'class' => $class,
    'item_id' => $item_id,
    'save_model' => $this->context->save_model,
];

$photoTemplate = '<tr data-id="{{id}}"><td>{{id}}</td>\
    <td><a href="{{photo_image}}" class="photo-box" title="{{photo_description}}" rel="admin-photos"><img class="photo-thumb" id="photo-{{id}}" src="{{photo_thumb}}"></a></td>\
    <td>\
        <textarea class="form-control photo-description">{{photo_description}}</textarea>\
        <a href="' . Url::to(['/admin/photos/description/{{id}}']) . '" class="btn btn-sm btn-primary disabled save-photo-description">' . Yii::t('admin', 'Сохранить') . '</a>\
    </td>\
    <td>\
        <div role="group">\
            <a href="' . Url::to(['/admin/photos/up/{{id}}'] + $linkParams) . '" class="move-up" title="' . Yii::t('admin', 'Переместить вверх') . '"><span class="fa fa-arrow-up"></span></a>&nbsp;&nbsp;\
            <a href="' . Url::to(['/admin/photos/down/{{id}}'] + $linkParams) . '" class="move-down" title="' . Yii::t('admin', 'Переместить вниз') . '"><span class="fa fa-arrow-down"></span></a>&nbsp;&nbsp;\
            <a href="' . Url::to(['/admin/photos/image/{{id}}'] + $linkParams) . '" class="change-image-button" title="' . Yii::t('admin', 'Изменить фото') . '"><span class="fa fa-image"></span></a>&nbsp;&nbsp;\
            <a href="' . Url::to(['/admin/photos/delete/{{id}}'] + $linkParams) . '" class="delete-photo" title="' . Yii::t('admin', 'Удалить фото') . '"><span class="fa fa-times"></span></a>\
            <input type="file" name="Photo[image]" class="change-image-input hidden">\
        </div>\
    </td>\
</tr>';
//$this->registerJs("var photoTemplate = '".$photoTemplate."';", \yii\web\View::POS_HEAD);
?>
<script>
    var photoTemplate = '<?=$photoTemplate?>';
</script>
<?



$photoTemplate = str_replace('>\\', '>', $photoTemplate);
$photoTemplate = str_replace('&nbsp;\\', '&nbsp;', $photoTemplate);
?>


<table id="photo-table" class="table table-hover" style="display: <?= count($photos) ? 'table' : 'none' ?>;">
    <thead>
        <tr>
            <th width="50">#</th>
            <th width="150"><?= Yii::t('admin', 'Фото') ?></th>
            <th><?= Yii::t('admin', 'Описание') ?></th>
            <th width="150"></th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($photos as $photo) : ?>
            <?=
            str_replace(
                    ['{{id}}', '{{photo_thumb}}', '{{photo_image}}', '{{photo_description}}'], [$photo->primaryKey, Image::thumb($photo->image, Photo::PHOTO_THUMB_WIDTH, Photo::PHOTO_THUMB_HEIGHT), $photo->image, $photo->description], $photoTemplate)
            ?>
        <? endforeach; ?>
    </tbody>
</table>
<div class="row mb-20">
    <div class="col-md-12">
        <button id="photo-upload" class="btn btn-primary btn-block"><span class="fa fa-upload"></span> <?= Yii::t('admin', 'Загрузить фото') ?></button>
        <small id="uploading-text" class="text-muted"><?= Yii::t('admin', 'Загрузка... Пожалуйста, ждите...') ?><span></span></small>
    </div>
</div>
<p class="empty" style="display: <?= count($photos) ? 'none' : 'block' ?>;"><?= Yii::t('admin', 'Нет фото') ?>.</p>
<?= Html::beginForm(Url::to(['/admin/photos/upload'] + $linkParams), 'post', ['enctype' => 'multipart/form-data']) ?>
<?=
Html::fileInput('', null, [
    'id' => 'photo-file',
    'class' => 'hidden',
    'multiple' => 'multiple',
])
?>
<? Html::endForm() ?>
