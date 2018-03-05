<?

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<? ActiveForm::begin(['action' => 'https://' . $settings['mode'] . '.yandex.ru/eshop.xml']) ?>

<?= Html::hiddenInput('shopId', $settings['shopid']) ?>
<?= Html::hiddenInput('scid', $settings['scid']) ?>
<?= Html::hiddenInput('sum', $order->getTotalCost()) ?>
<?= Html::hiddenInput('customerNumber', $order->user_id) ?>
<?= Html::hiddenInput('paymentType', $settings['type']) ?>
<?= Html::hiddenInput('orderNumber', $order->id) ?>
<?= Html::hiddenInput('cps_phone', Html::encode($order->phone)) ?>
<?= Html::hiddenInput('cps_email', Html::encode($order->email)) ?>
<?= Html::hiddenInput('shopSuccessURL', Yii::$app->urlManager->createAbsoluteUrl(['admin/payment/success', 'id' => $order->id])) ?>
<?= Html::hiddenInput('shopFailURL', Yii::$app->urlManager->createAbsoluteUrl(['admin/payment/fail', 'id' => $order->id])) ?>
<?= Html::submitButton(Yii::t('admin/payment', 'Оплатить заказ'), ['class' => 'btn btn-lg btn-success']) ?>

<? ActiveForm::end(); ?>
