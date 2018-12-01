<?

$asset = \admin\assets\PublicAsset::register($this);

$this->title = Yii::t('admin','Ошибка установки');
?>

<h4><?= Yii::t('admin','Ошибка установки') ?></h4>
<br>
<?= $error ?>
               