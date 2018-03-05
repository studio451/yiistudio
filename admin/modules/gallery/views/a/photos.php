<?
use admin\widgets\Photos;

$this->title = $model->title;
?>

<?= $this->render('@admin/views/category/_menu') ?>

<?= Photos::widget(['model' => $model])?>