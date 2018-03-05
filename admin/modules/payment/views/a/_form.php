<?

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use admin\widgets\Redactor;

$module = $this->context->module->id;
?>
<?
$form = ActiveForm::begin([
            'enableAjaxValidation' => true,
            'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
        ]);
?>
<?= $form->field($model, 'title') ?>
<?= $form->field($model, 'slug') ?>
<?= $form->field($model, 'class') ?>
<small>
    <p>
        admin\modules\payment\payment_systems\Manual - <?= Yii::t('admin', 'без онлайн-оплаты, статус оплачено меняется только через Панель управления') ?>
    </p>
    <p>
        admin\modules\payment\payment_systems\YandexMoney - <?= Yii::t('admin', 'прием денег через кошелек Яндекс.Деньги') ?>
    </p>
    <p>
        admin\modules\payment\payment_systems\YandexKassa - <?= Yii::t('admin', 'оплата через ПШ Яндекс.Касса') ?>
    </p>
</small>
<?= $form->field($model, 'is_manual')->checkbox() ?>
<?= $form->field($model, 'available_to') ?>
<?=
$form->field($model, 'description')->widget(Redactor::className(), [
    'options' => [
        'imageUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'catalog'], true),
        'fileUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'catalog'], true),
    ]
])
?>

<? $model->renderDataForm(); ?>


<div class="row">
    <div class="col-md-6">
        <?= Html::submitButton(Yii::t('admin', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
    </div>
    <div class="col-md-4">
        
    </div>
    <div class="col-md-2">
        <? if (Yii::$app->user->can("SuperAdmin")) { ?> <a href="<?= Url::to(['/admin/payment/a/default-data', 'id' => $model->id]) ?>" class="btn btn-warning  btn-block"><i class="fa fa-flash"></i> <?= Yii::t('admin', 'Настройки по-умолчанию') ?></a><? } ?>
    </div>
</div>
<? ActiveForm::end(); ?>
<? if (Yii::$app->user->can("SuperAdmin")) { ?> 
    <div class="row mt-75">
        <?= Html::beginForm(Url::to(['/admin/payment/a/test', 'id' => $model->id]), 'post') ?>
        <div class="col-md-6">

        </div>
        <div class="col-md-2">
            <?= Html::input('text', 'amount', '', ['class' => 'form-control', 'placeholder' => Yii::t('admin', 'Сумма заказа')]) ?>
        </div>
        <div class="col-md-2">
            <?= Html::input('text', 'order_id', '', ['class' => 'form-control', 'placeholder' => Yii::t('admin', 'Номер заказа')]) ?>
        </div>
        <div class="col-md-2">
            <?= Html::submitButton(Yii::t('admin', 'Тестовая оплата заказа'), ['class' => 'btn btn-danger btn-block']) ?>            
        </div>
        <?= Html::endForm() ?>
    </div>
<? } ?>