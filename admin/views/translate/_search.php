<?

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model admin\models\translate\TranslateMessageSearch */
/* @var $form yii\widgets\ActiveForm */
?>

    <? $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'language') ?>

    <?= $form->field($model, 'translation') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('admin', 'Поиск'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('admin', 'Сбросить'), ['class' => 'btn btn-default']) ?>
    </div>

    <? ActiveForm::end(); ?>