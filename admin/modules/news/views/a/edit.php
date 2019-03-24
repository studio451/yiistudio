<?
$this->title = Yii::t('admin/news', 'Новость');
?>
<?= $this->render('_menu') ?>

<?= $this->render('_submenu', ['model' => $model]) ?>

<?= $this->render('_form', ['model' => $model]) ?>