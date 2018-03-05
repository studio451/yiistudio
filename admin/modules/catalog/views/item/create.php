<?
$this->title = Yii::t('admin/catalog', 'Добавить элемент');
?>
<?= $this->render('_menu', ['category' => $category]) ?>
<?= $this->render('_form', ['model' => $model, 'dataForm' => $dataForm]) ?>