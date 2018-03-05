<?
$this->title = Yii::t('admin', 'Создать категорию');
?>
<?= $this->render('_menu') ?>
<?= $this->render('_form', ['model' => $model, 'parent' => $parent]) ?>