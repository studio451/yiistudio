<?

namespace admin\modules\delivery\api;

use Yii;
use admin\modules\delivery\models\Delivery as DeliveryModel;

/**
 * Delivery module API
 * @package admin\modules\delivery\api
 *
 */
class Delivery extends \admin\components\API {

    private $_items;

    public function api_items($options = []) {

        if (!$this->_items) {
            $this->_items = [];
            $query = DeliveryModel::find()->status(DeliveryModel::STATUS_ON)->sort();

            //Доставка доступна от
            if (!empty($options['shopcart_cost'])) {
                $query->andFilterWhere(['or', ['<=', 'available_from', $options['shopcart_cost']], ['available_from' => 0]]);
            }

            $this->_items = [];
            foreach ($query->all() as $item) {
                $deliveryObject = new DeliveryObject($item);

                $deliveryObject->price = $item->price;
                if (!empty($options['shopcart_cost'])) {
                    $deliveryObject->shopcart_cost = $options['shopcart_cost'];
                }
                if (!empty($options['shopcart_cost'])) {
                    //Доставка бесплатна от
                    if ($item->free_from > 0 && $item->free_from <= $options['cost']) {
                        $deliveryObject->price = 0;
                    }
                }

                $this->_items[$item->slug] = $deliveryObject;
            }
        }
        return $this->_items;
    }

    public function api_item($id_slug) {
        if (!isset($this->_items[$id_slug])) {
            $this->_items[$id_slug] = $this->findItem($id_slug);
        }
        return $this->_items[$id_slug];
    }

    private function findItem($id_slug) {
        $delivery = DeliveryModel::find()->where(['or', 'id=:id_slug', 'slug=:id_slug'], [':id_slug' => $id_slug])->status(DeliveryModel::STATUS_ON)->one();
        if ($delivery) {
            return new DeliveryObject($delivery);
        } else {
            return null;
        }
    }

}
