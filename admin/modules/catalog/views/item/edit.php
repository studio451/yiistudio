<?

use admin\widgets\Photos;

$this->title = Yii::t('admin', 'Редактирование') . ' ' . $model->title;
?>
<?= $this->render('_menu', ['category' => $model->category]) ?>
<?= $this->render('_form', ['model' => $model, 'dataForm' => $dataForm]) ?>
<div class="row mb-50">
    <div class="col-md-7">

    </div>
    <div class="col-md-5">
        <?= Photos::widget(['model' => $model,'save_model' => true]) ?>
    </div>
</div>
