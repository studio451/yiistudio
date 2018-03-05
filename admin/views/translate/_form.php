<?

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model admin\models\TranslateMessage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="translate-message-form">

    <? $form = ActiveForm::begin(); ?>    
    
    <strong><?= Yii::t('admin', 'Категория') ?>: <i><?= $model->translateSourceMessage->category ?></i></strong><br/><br/>

    <?= $form->field($model, 'language')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'translation')->textarea(['rows' => 3]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('admin', 'Добавить') : Yii::t('admin', 'Обновить'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <? ActiveForm::end(); ?>

</div>
