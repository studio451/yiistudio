<?

use admin\modules\shopcart\api\Shopcart;
use admin\modules\delivery\api\Delivery;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use admin\modules\block\api\Block;
use yii\web\View;

if (Yii::$app->session->has('shopcartUserInputData')) {
    $shopcartUserInputData = Yii::$app->session->get('shopcartUserInputData');
    if (is_array($shopcartUserInputData)) {
        if ($shopcartUserInputData['delivery']) {
            $delivery_slug = $shopcartUserInputData['delivery'];
        }
        if ($shopcartUserInputData['payment']) {
            $payment_slug = $shopcartUserInputData['payment'];
        }
        if ($shopcartUserInputData['name']) {
            $orderForm->name = $shopcartUserInputData['name'];
        }
        if ($shopcartUserInputData['phone']) {
            $orderForm->phone = $shopcartUserInputData['phone'];
        }
        if ($shopcartUserInputData['comment']) {
            $orderForm->comment = $shopcartUserInputData['comment'];
        }
    }
}

$deliveries = Delivery::items(['shopcart_cost' => Shopcart::cost()]);
?>

<?
$form = ActiveForm::begin(['action' => Url::to(['/shopcart/create']), 'id' => 'shopcart_create', 'enableClientValidation' => true]);
?>
<?
if (count($deliveries)) {
    //Проверяем существует ли служба доставки сохраненная в сессии
    if (!$deliveries[$delivery_slug]) {
        $delivery_slug = key($deliveries);
        $payment_slug = key($deliveries[$delivery_slug]->payments);
    } else {
        //Проверяем существует ли способ оплаты сохраненный в сессии
        if (!$deliveries[$delivery_slug]->payments[$payment_slug]) {
            $payment_slug = key($deliveries[$delivery_slug]->payments);
        }
    }
    ?>
    <input type="hidden" id="shopcart_cost" value="<?= Shopcart::cost() ?>">
    <div class="row">
        <div class="col-md-7">
            <table class="table">
                <thead>
                    <tr>
                        <th><?= Yii::t('admin/shopcart', 'Службы доставки') ?></th>                        
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <?
                            $need_address = false;
                            $delivery_price = 0;
                            foreach ($deliveries as $delivery) {
                                $free_delivery = false;
                                if ($delivery->free_from > 0) {
                                    if (Shopcart::cost() > $delivery->free_from) {
                                        $free_delivery = true;
                                    }
                                }
                                ?>
                                <div class="radio">
                                    <label for="delivery_<?= $delivery->id ?>">
                                        <input type="radio" name="delivery_id" class="shopcart_delivery_radio" id="delivery_<?= $delivery->id ?>"
                                               value="<?= $delivery->id ?>"
                                               data-slug="<?= $delivery->slug ?>"
                                               data-price="<?= $free_delivery ? '0' : $delivery->price ?>"
                                               data-free-from="<?= $delivery->free_from ?>"
                                               data-need-address="<?= $delivery->need_address ?>"
                                               <?
                                               if ($delivery_slug == $delivery->slug) {
                                                   $need_address = $delivery->need_address;
                                                   if (!$free_delivery) {
                                                       $delivery_price = $delivery->price;
                                                   }
                                                   echo 'checked';
                                               }
                                               ?>>
                                        <?= $delivery->title ?> - <?= $free_delivery ? '<del class="text-muted"><small>' . $delivery->price . '</small></del> 0' : $delivery->price ?>
                                        <i class="fas fa-ruble-sign"></i>

                                        <? if ($delivery->free_from > 0) {
                                            ?>
                                            (<?= Yii::t('admin/shopcart', "бесплатно при стоимости заказа от "); ?> <?= $delivery->free_from; ?> <i class="fas fa-ruble-sign"></i>)
                                        <? } ?>                                        
                                    </label>
                                    <div class="text-muted">
                                        <?= $delivery->description ?>
                                    </div>
                                </div>
                                <?
                            }
                            ?>
                        </td>
                    </tr>                    
                </tbody>
            </table>         
            <table class="table">
                <thead>
                    <tr>
                        <th><?= Yii::t('admin/shopcart', 'Способы оплаты') ?></th>                        
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <?
                            foreach ($deliveries as $delivery) {
                                $checked = true;
                                ?>
                                <div id="shopcart_delivery_payment_<?= $delivery->id; ?>" class="shopcart_delivery_payment_div" style="display:<?
                                if ($delivery_slug == $delivery->slug) {
                                    echo 'block';
                                } else {
                                    echo 'none';
                                }
                                ?>">
                                         <?
                                         foreach ($delivery->payments as $payment) {
                                             ?>
                                        <div class="radio">
                                            <label for="payment_<?= $delivery->id ?>_<?= $payment->id ?>">
                                                <input type="radio" name="payment_id[<?= $delivery->id ?>]" id="payment_<?= $delivery->id ?>_<?= $payment->id ?>"
                                                       value="<?= $payment->id ?>"
                                                       data-slug="<?= $payment->slug ?>"
                                                       <?
                                                       if ($delivery->slug == $delivery_slug) {
                                                           if ($payment->slug == $payment_slug) {
                                                               echo 'checked';
                                                           }
                                                       } else {
                                                           if ($checked) {
                                                               echo 'checked';
                                                               $checked = false;
                                                           }
                                                       }
                                                       ?>>
                                                       <?= $payment->title ?>
                                            </label>
                                            <div class="text-muted">
                                                <?= $payment->description ?>
                                            </div>
                                        </div>
                                        <?
                                    }
                                    ?>
                                </div>
                                <?
                            }
                            ?>
                        </td>
                    </tr>                    
                </tbody>
            </table> 
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-4">
            <table class="table">
                <thead>
                    <tr>
                        <th><?= Yii::t('admin/shopcart', 'Данные для заказа') ?></th>                        
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <?
                            echo $form->field($orderForm, 'name')->label(Yii::t('admin/shopcart', 'Ваше имя'));
                            echo $form->field($orderForm, 'phone')->label(Yii::t('admin/shopcart', 'Телефон'));;
                            if ($need_address) {
                                echo $form->field($orderForm, 'address')->label(Yii::t('admin/shopcart', 'Адрес (можно не заполнять)'), ['id' => 'address_label']);
                            } else {
                                echo $form->field($orderForm, 'address')->textInput(['style' => 'display:none'])->label(Yii::t('admin/shopcart', 'Адрес (можно не заполнять)'), ['id' => 'address_label', 'style' => 'display:none']);
                            }
                            echo $form->field($orderForm, 'comment')->textarea()->label(Yii::t('admin/shopcart', 'Комментарий (если нужно)'));
                            ?>                           
                        </td>
                    </tr>                    
                </tbody>
            </table>            
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-right">
            <h3><?= Yii::t('admin/shopcart', 'Итого стоимость заказа: ') ?> <span id="shopcart_total_cost"><?= Shopcart::cost() + $delivery_price ?></span> <i class="fas fa-ruble-sign"></i></h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <?
            if (Yii::$app->user->isGuest && $fast != true) {
                ?>        
                <noindex>
                    <?= Yii::t('admin/shopcart', 'Чтобы отслеживать состояние заказов, получать персональные скидки, участвовать в акциях, оплачивать заказы онлайн, нужно') ?> <a rel="nofollow" href="javascript:void(0);" title="<?= Yii::t('admin', 'Войти в личный кабинет') ?>" data-url="<?= Url::to(['/user/login']) ?>" class="dotted ajaxModalPopup"><?= Yii::t('admin', 'войти в личный кабинет') ?></a> <?= Yii::t('admin', 'или') ?> <a rel="nofollow" href="javascript:void(0);" title="<?= Yii::t('admin', 'Зарегистрироваться') ?>" data-url="<?= Url::to(['/user/registration']) ?>" class="dotted ajaxModalPopup"><?= Yii::t('admin', 'зарегистрироваться') ?></a>.
                </noindex>
                <?
            }
            ?>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-4 text-right">            
            <?= Html::submitButton(Yii::t('admin/shopcart', 'Оформить заказ'), ['class' => 'btn btn-success btn-lg']); ?>
        </div>
    </div>
    <?
} else {
    ?>
    <div class="row">
        <div class="col-md-7 text-justify">
            <?= Block::get('not_delivery')->text ?>
        </div>
        <div class="col-md-4 col-md-offset-1 text-right">
            <?= Html::submitButton(Yii::t('admin/shopcart', 'Оформить заказ'), ['class' => 'btn btn-success btn-lg']); ?>
        </div>
    </div>
<? } ?>
<?
ActiveForm::end();
?>
<?
$js = <<<SCRIPT
$(".shopcart_delivery_radio").change(function () {
    $(".shopcart_delivery_payment_div").hide();
    $("#shopcart_delivery_payment_" + $(this).val()).show();
    $("#shopcart_total_cost").html(parseInt($("#shopcart_cost").val()) + parseInt($(this).attr("data-price")));
    if ($(this).attr("data-need-address") === "1")
    {
        $("#address_label").show();
        $("#address").show();
    } else
    {
        $("#address_label").hide();
        $("#address").hide();
    }
});
$(".ajaxModalPopup").on("click", function () {
    _g_session("shopcartUserInputData", {
        name: $("#name").val(),
        phone: $("#phone").val(),
        comment: $("#comment").val(),
        delivery: $("input[name=delivery_id]:checked").attr("data-slug"),
        payment: $("input[name='payment_id[" + $("input[name=delivery_id]:checked").val() + "]']:checked").attr("data-slug"),
    });
});
SCRIPT;
$this->registerJs($js, View::POS_READY);
?>
