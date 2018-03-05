<?
$asset = \admin\assets\PublicAsset::register($this);

$this->title = Yii::t('admin/install','Установка');
?>

<h4><?= Yii::t('admin/install','Установка') ?></h4>
<br>
<?= $this->render('_form', ['model' => $model]) ?>
               