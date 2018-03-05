<?
$this->title = Yii::t('admin', 'Редактировать пользователя');
?>
<?= $this->render('_menu') ?>
<?=
$this->render('_form', [
    'model' => $model,
    'roles' => $roles,
    'user_permit' => $user_permit,
    'userForm' => $userForm,
])
?>

