<?

use yii\helpers\Url;
use admin\models\Setting;
use admin\assets\FontAwesomeAsset;

$fontAwesomeAsset = FontAwesomeAsset::register($this);

$this->title = Yii::t('admin', 'Система');
?>

<h4 class="mb-40"><?= Yii::t('admin', 'Версия') ?>: <b><?= Setting::get('admin_version') ?></b></h4>
<?
if (\admin\AdminModule::VERSION > floatval(Setting::get('admin_version'))) {
    ?>
    <div class="row mb-20">
        <div class="col-md-2">
            <a href="<?= Url::to(['/admin/system/update']) ?>" class="btn btn-success"><?= Yii::t('admin', 'Обновить') ?></a>
        </div>
    </div>
    <?
}
?>

<div class="row mb-20">
    <div class="col-md-4">
        <a href="<?= Url::to(['/admin/system/migrate-down']) ?>" class="btn btn-default btn-block"><i class="fa fa-angle-double-down"></i> <?= Yii::t('admin', 'Миграция: откат на предыдущую') ?></a>
    </div>
    <div class="col-md-4">
        <a href="<?= Url::to(['/admin/system/migrate']) ?>" class="btn btn-default btn-block"><i class="fa fa-angle-double-up"></i> <?= Yii::t('admin', 'Миграция: накатить следующую') ?></a>
    </div>
</div>
<div class="row mb-20">
    <div class="col-md-4">
        <a href="<?= Url::to(['/admin/system/migrate-app-down']) ?>" class="btn btn-default btn-block"><i class="fa fa-angle-double-down"></i> <?= Yii::t('admin', 'Миграция приложения: откат на предыдущую') ?></a>
    </div>
    <div class="col-md-4">
        <a href="<?= Url::to(['/admin/system/migrate-app']) ?>" class="btn btn-default btn-block"><i class="fa fa-angle-double-up"></i> <?= Yii::t('admin', 'Миграция приложения: накатить следующую') ?></a>
    </div>
</div>
<div class="row mb-20">
    <div class="col-md-4">
        <a href="<?= Url::to(['/admin/system/flush-cache']) ?>" class="btn btn-success btn-block"><i class="fa fa-flash"></i> <?= Yii::t('admin', 'Очистить кеш') ?></a>
    </div>
    <div class="col-md-4">
        <a href="<?= Url::to(['/admin/system/clear-assets']) ?>" class="btn btn-success btn-block"><i class="fa fa-refresh"></i> <?= Yii::t('admin', 'Обновить файлы ресурсов (.js, .css, .png, .jpg, ...)') ?></a>
    </div>
    <div class="col-md-2">        
    </div>
    <div class="col-md-4">
        <?
        if (YII_DEBUG) {
            ?>
            <a href="<?= Url::to(['/admin/system/clear-items']) ?>" class="btn btn-danger btn-block"><i class="fa fa-close"></i> <?= Yii::t('admin', 'Удалить все элементы каталога') ?></a>
            <?
        }
        ?>
    </div>
</div>
<div class="row mb-20">
    <div class="col-md-4">
        <a href="<?= Url::to(['/admin/system/clear-thumbs']) ?>" class="btn btn-success btn-block"><i class="fa fa-close"></i> <?= Yii::t('admin', 'Очистить thumbs-директорию') ?></a>
    </div>
    <div class="col-md-4">
        <a href="<?= Url::to(['/admin/system/clear-tmp']) ?>" class="btn btn-success btn-block"><i class="fa fa-close"></i> <?= Yii::t('admin', 'Очистить tmp-директорию') ?></a>
    </div>
    <div class="col-md-2">        
    </div>
    <div class="col-md-4">
        <?
        if (YII_DEBUG) {
            ?>
            <a href="<?= Url::to(['/admin/system/clear-photos']) ?>" class="btn btn-danger btn-block"><i class="fa fa-close"></i> <?= Yii::t('admin', 'Удалить все фото') ?></a>
            <?
        }
        ?>
    </div>
</div>
<div class="row mb-20">
    <div class="col-md-4">
        <a href="<?= Url::to(['/admin/system/recreate-groups']) ?>" class="btn btn-warning btn-block"><i class="fa fa-refresh"></i> <?= Yii::t('admin', 'Пересоздание групп элементов каталога') ?></a>
    </div>
    <div class="col-md-4">
        <a href="<?= Url::to(['/admin/system/resave-items']) ?>" class="btn btn-warning btn-block"><i class="fa fa-refresh"></i> <?= Yii::t('admin', 'Пересохранение элементов каталога') ?></a>
    </div>
    <div class="col-md-4">
        <?
        if (YII_DEBUG) {
            ?>
            <a href="<?= Url::to(['/admin/system/clear-users-no-order']) ?>" class="btn btn-danger btn-block"><i class="fa fa-close"></i> <?= Yii::t('admin', 'Удалить пользователей, у которых нет заказов') ?></a>
            <?
        }
        ?>
    </div>
</div>
<?
if (YII_DEBUG) {
    ?>
    <br>
    <br>
    <div class="tab-pane active p-20">       

        <?
        try {
            $last_version = 0;
            $categories = [];
            foreach ($fontAwesomeAsset->iconsFromYaml() as $icon) {
                if (isset($icon['categories'][1])) {
                    $categories[$icon['categories'][1]][] = $icon;
                } else {
                    $categories[$icon['categories'][0]][] = $icon;
                }
                if ((float) $last_version < (float) $icon['created']) {
                    $last_version = (float) $icon['created'];
                }
            }
            ?>
            <h2><?= Yii::t('admin', 'Font Awesome иконки v{last_version}', ['last_version' => $last_version]) ?> </h2>
            <a href="http://fontawesome.io" title="http://fontawesome.io">http://fontawesome.io</a>
            <br>
            <?
            foreach ($categories as $category => $icons) {
                echo '<br><h3>' . $category . '</h3><hr><div class="row">';
                foreach ($icons as $icon) {
                    echo '<div class="col-md-2 col-sm-3"><i class="fa fa-' . $icon['id'] . '"></i> ' . $icon['id'] . '</div>';
                }
                echo '</div>';
            }
        } catch (\Exception $e) {
            echo '<p class="bg-warning p-10">' . $e->getMessage() . '</p>';
        }
        ?>
    </div>

    <?
}
?>