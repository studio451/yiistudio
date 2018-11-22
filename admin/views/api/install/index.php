<?
$asset = \admin\assets\PublicAsset::register($this);

$this->title = Yii::t('admin/install','Установка');
?>

<h4><?= Yii::t('admin/install','Установка') . ' <b>' . \admin\AdminModule::NAME. '</b> v' . \admin\AdminModule::VERSION ?></h4>
<a href="https://yiistudio.ru" target="_blank" title="https://yiistudio.ru">https://yiistudio.ru</a>
<br>

<?= $text ?>
<br>
<?= $this->render('_form', ['model' => $model]) ?>
               