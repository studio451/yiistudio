<?
$this->title = Yii::t('admin/page', 'Редактировать страницу');
?>
<?= $this->render('_menu') ?>
<?= $this->render('_form', ['model' => $model]) ?>