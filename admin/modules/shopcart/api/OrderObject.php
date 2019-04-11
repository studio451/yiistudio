<?

namespace admin\modules\shopcart\api;

use Yii;
use admin\modules\shopcart\models\Good;

class OrderObject extends \admin\base\ApiObject {

    public $name;
    public $address;
    public $phone;
    public $email;
    public $user_id;
    public $comment;
    public $access_token;
    public $delivery_details;
    public $payment_details;
    public $data;
    private $_goods;
    private $_cost;
    private $_delivery_cost;
    private $_total_cost;

    public function getGoods() {
        if (!$this->_goods) {
            $this->_goods = [];
            if ($this->id) {
                foreach (Good::find()->where(['order_id' => $this->id])->with('item')->all() as $good) {
                    $this->_goods[] = new GoodObject($good);
                }
            }
        }
        return $this->_goods;
    }

    public function getDate() {
        return Yii::$app->formatter->asDatetime($this->model->time, 'short');
    }

    public function getDeliveryDetails() {
        return $this->model->delivery_details;
    }

    public function getPaymentDetails() {
        return $this->model->payment_details;
    }

    public function getDeliveryId() {
        return $this->model->delivery_id;
    }

    public function getPaymentId() {
        return $this->model->payment_id;
    }

    public function getCost() {
        if ($this->_cost === null) {
            $this->_cost = $this->model->cost;
        }
        return $this->_cost;
    }

    public function getDeliveryCost() {
        if ($this->_delivery_cost === null) {
            $this->_delivery_cost = $this->model->delivery_cost;
        }
        return $this->_delivery_cost;
    }

    public function getTotalCost() {
        if ($this->_total_cost === null) {
            $this->_total_cost = $this->cost + $this->deliveryCost;
        }
        return $this->_total_cost;
    }

    public function getStatus() {
        return $this->model->statusName;
    }

    public function getPaidStatus() {
        return $this->model->paidStatusName;
    }

    public function isPaid() {
        return $this->model->isPaid();
    }

    public function isNotPaid() {
        return $this->model->isNotPaid();
    }

    public function renderStatus() {
        return $this->model->renderStatus();
    }

    public function renderPaidStatus() {
        return $this->model->renderPaidStatus();
    }

}
