<?
use yii\helpers\Url;

$asset = \admin\assets\PublicAsset::register($this);

$this->title = Yii::t('admin', 'Установка завершена');
?>

<h3><?= Yii::t('admin', 'Установка завершена!') ?>
</h3>
<br>
<? if (!INSTALLED) { ?>
<p class="bg-warning p-10">
   <b><?= Yii::t('admin', 'ВНИМАНИЕ!<br>Установите значение константы INSTALLED в {path} равным true и обновите эту страницу.', ['path' => Yii::getAlias('@webroot/index.php')]) ?></b>
</p>
<p class="bg-success p-10">
   <b><?= Yii::t('admin', 'Вы также можете установить демо-данные из папки {path}.', ['path' => Yii::getAlias('@app/demo_data')]) ?></b>
   <?= Yii::t('admin', 'Подробнее в файле {file}.', ['file' => Yii::getAlias('@app/demo_data/README.md')]) ?>
</p>
<br>
<a href="<?= Url::to(['/admin/api/install']) ?>"> <?= Yii::t('admin', 'Повторная установка') ?> <?= \admin\AdminModule::NAME ?></a>
<br>
<br>
<? } else {?>
<a href="<?= Url::to(['/admin']) ?>"> <?= Yii::t('admin', 'Перейти в панель управления') ?> <?= \admin\AdminModule::NAME ?></a>
<br>
<br>
<a href="<?= Url::to(['/']) ?>"> <?= Yii::t('admin', 'Перейти на сайт') ?></a>
<? } ?>