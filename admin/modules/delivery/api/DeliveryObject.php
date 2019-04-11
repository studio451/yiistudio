<?

namespace admin\modules\delivery\api;

use admin\base\Api;
use yii\helpers\Url;
use admin\modules\payment\api\PaymentObject;
use admin\modules\payment\models\Payment;

class DeliveryObject extends \admin\base\ApiObject {

    public $slug;
    public $price;    
    public $free_from;
    public $need_address;
    public $description;
    public $shopcart_cost; //Текущая стоимость корзины
    private $_payments;

    public function getTitle() {
        return LIVE_EDIT ? Api::liveEdit($this->model->title, $this->editLink) : $this->model->title;
    }

    public function getEditLink() {
        return Url::to(['/admin/delivery/a/edit', 'id' => $this->id]);
    }

    public function getPayments() {
        if (!$this->_payments) {
            $this->_payments = [];
            if ($this->id) {
                //Способы оплаты доступные для этой службы
                foreach ($this->model->payments as $payment) {
                    if ($payment->status != Payment::STATUS_ON) {
                        continue;
                    }
                    //Способ оплаты доступен при стоимости корзины до
                    if (!empty($this->shopcart_cost)) {
                        if ($payment->available_to > 0 && $payment->available_to < $this->shopcart_cost) {
                            continue;
                        }
                    }
                    $this->_payments[$payment->slug] = new PaymentObject($payment);
                }
            }
        }
        return $this->_payments;
    }

}
