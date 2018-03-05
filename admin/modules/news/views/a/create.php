<?
$this->title = Yii::t('admin/news', 'Создать новость');
?>
<?= $this->render('_menu') ?>
<?= $this->render('_form', ['model' => $model]) ?>