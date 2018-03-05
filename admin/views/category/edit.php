<?
$this->title = Yii::t('admin', 'Редактировать категорию');
?>
<?= $this->render('_menu') ?>

<? if(!empty($this->params['submenu'])) echo $this->render('_submenu', ['model' => $model], $this->context); ?>
<?= $this->render('_form', ['model' => $model, 'parent' => $parent]) ?>