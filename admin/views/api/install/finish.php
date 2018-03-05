<?

use yii\helpers\Url;

$asset = \admin\assets\PublicAsset::register($this);

$this->title = Yii::t('admin/install', 'Установка завершена');
?>

<h4><?= Yii::t('admin/install', 'Установка завершена') ?>
</h4>
<br>
<? if (!\admin\AdminModule::INSTALLED) { ?>
<p class="bg-warning p-10">
   <b><?= Yii::t('admin/install', 'ВНИМАНИЕ! Измените константу \admin\AdminModule::INSTALLED в значение "true"!') ?></b>
</p>
<br>
<? } ?>
<a href="<?= Url::to(['/admin']) ?>"> <?= Yii::t('admin/install', 'Перейти в панель управления') ?> <?= \admin\AdminModule::NAME ?></a>
<br>
<br>
<a href="<?= Url::to(['/']) ?>"> <?= Yii::t('admin/install', 'Перейти на сайт') ?></a>