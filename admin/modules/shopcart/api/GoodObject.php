<?

namespace admin\modules\shopcart\api;

use admin\modules\catalog\api\ItemObject;

class GoodObject extends \admin\components\ApiObject {

    public $order_id;
    public $item_id;
    public $options;
    public $discount;
    public $count;
    private $_item;

    public function getItem() {
        if (!$this->_item) {
            $this->_item = new ItemObject($this->model->item);
        }
        return $this->_item;
    }

    public function getPrice() {
        return $this->discount ? round($this->model->price * (1 - $this->discount / 100)) : $this->model->price;
    }

    public function getOldPrice() {
        return $this->model->price;
    }

}
