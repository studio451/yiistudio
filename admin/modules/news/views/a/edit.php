<?
$this->title = Yii::t('admin/news', 'Просмотр истории рассылки');
?>
<?= $this->render('_menu') ?>

<?= $this->render('_submenu', ['model' => $model]) ?>

<?= $this->render('_form', ['model' => $model]) ?>