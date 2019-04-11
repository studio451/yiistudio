<?

use yii\helpers\Html;
use yii\helpers\Url;
use admin\assets\ToolbarAsset;
use admin\models\Setting;

$asset = ToolbarAsset::register($this);
?>
<a class="admin-toolbar text-center" title="<?= Yii::t('admin', 'Вход в Панель управления') ?>" style="<?= Setting::get('toolbar_position') ?>:5px;" href="<?= Url::to(['/admin']) ?>" >
    <i class="fa fa-cogs"></i>
</a>
<div class="admin-toolbar text-center" title="<?= Yii::t('admin', 'Переключение режима LiveEdit') ?>" style="<?= Setting::get('toolbar_position') ?>:60px;">
    <?=
    Html::checkbox('', LIVE_EDIT, [
        'style' => 'display:none',
        'class' => 'switch',
        'data-link' => Url::to(['/admin/system/live-edit']),
        'data-reload' => '1'
    ])
    ?> 
</div>
<?
if (YII_DEBUG) 
    {
?> 
    <a class="admin-toolbar text-center" title="<?= Yii::t('admin', 'Очистить кеш') ?>" style="<?= Setting::get('toolbar_position') ?>:115px;" href="<?= Url::to(['/admin/system/flush-cache']) ?>" >
        <i class="fa fa-fire-alt"></i>
    </a>
    <a class="admin-toolbar text-center" title="<?= Yii::t('admin', 'Обновить файлы ресурсов (.js, .css, .png, .jpg, ...)') ?>" style="<?= Setting::get('toolbar_position') ?>:170px;" href="<?= Url::to(['/admin/system/clear-assets']) ?>" >
        <i class="fa fa-redo"></i>
    </a>
    <a class="admin-toolbar text-center" title="<?= Yii::t('admin', 'Включена отладка') ?>" style="<?= Setting::get('toolbar_position') ?>:225px;" href="<?= Url::to(['/debug']) ?>" >
        <span style="color:#FFCC33"><i class="fa fa-exclamation-triangle"></i><span>
    </a>
<?
    }
?>