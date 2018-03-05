<?
$this->title = Yii::t('admin', 'Создать пользователя');
?>
<?= $this->render('_menu') ?>
<?=
$this->render('_form', [
    'model' => $model,
    'roles' => $roles,
    'user_permit' => $user_permit,
    ])
?>