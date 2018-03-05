<?
$this->title = Yii::t('admin/article', 'Создать статью');
?>
<?= $this->render('_menu', ['category' => $category]) ?>
<?= $this->render('_form', ['model' => $model]) ?>