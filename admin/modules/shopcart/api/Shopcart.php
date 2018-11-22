<?

namespace admin\modules\shopcart\api;

use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\LinkPager;
use admin\modules\catalog\models\Item;
use admin\modules\shopcart\models\Good;
use admin\modules\shopcart\models\Order;
use admin\modules\delivery\models\Delivery;
use admin\modules\payment\models\Payment;
use admin\models\User;

class Shopcart extends \admin\components\API {

    private $_order;
    private $_orders;
    private $_orders_adp;

    public function api_goods() {
        return $this->order->goods;
    }

    public function api_order($id) {
        $order = Order::findOne($id);
        return $order ? new OrderObject($order) : null;
    }

    public function api_orders_pages($options = []) {
        return $this->_orders_adp ? LinkPager::widget(array_merge($options, ['pagination' => $this->_orders_adp->pagination])) : '';
    }

    public function api_orders($options = []) {

        if (Yii::$app->user->isGuest) {
            $this->_orders = [];
        } else {
            if (!$this->_orders) {
                $this->_orders = [];


                $query = Order::find()->where(['user_id' => Yii::$app->user->identity->id])->with('goods');

                if (!empty($options['status'])) {
                    $query->status($options['status']);
                }

                $query->sortDate();

                $this->_orders_adp = new ActiveDataProvider([
                    'query' => $query,
                    'pagination' => !empty($options['pagination']) ? $options['pagination'] : []
                ]);

                foreach ($this->_orders_adp->models as $model) {
                    $this->_orders[] = new OrderObject($model, $options);
                }
            }
        }
        return $this->_orders;
    }

    public function api_add($item_id, $count = 1, $options = '', $increaseOnDuplicate = true) {
        $item = Item::findOne($item_id);
        if (!$item) {
            return ['result' => 'error', 'error' => 'Item no found'];
        }

        if (!$this->order->id) {
            if (!$this->order->model->save()) {
                return ['result' => 'error', 'error' => 'Cannot create order. ' . $this->order->formatErrors()];
            }
            Yii::$app->session->set(Order::SESSION_KEY, $this->order->model->access_token);
        }

        $good = Good::findOne([
                    'order_id' => $this->order->id,
                    'item_id' => $item->primaryKey,
                    'options' => $options
        ]);

        if ($good && !$increaseOnDuplicate) {
            return ['result' => 'error', 'error' => 'Dublicate good in order.'];
        }

        if ($good) {
            $good->count += $count;
        } else {
            $good = new Good([
                'order_id' => $this->order->id,
                'item_id' => $item->primaryKey,
                'count' => (int) $count,
                'options' => $options,
                'discount' => $item->discount,
                'price' => $item->price
            ]);
        }

        if ($good->save()) {
            $response = [
                'result' => 'success',
                'order_id' => $this->order->id,
                'good_id' => $good->primaryKey,
                'item_id' => $item->primaryKey,
                'options' => $good->options,
                'discount' => $good->discount,
                'cost' => $this->order->cost
            ];
            if ($response['discount']) {
                $response['price'] = round($good->price * (1 - $good->discount / 100));
                $response['old_price'] = $good->price;
            } else {
                $response['price'] = $good->price;
            }
            return $response;
        } else {
            return ['result' => 'error', 'error' => $good->formatErrors()];
        }
    }

    public function api_clear() {

        $access_token = $this->token;

        if ($access_token && ($order = Order::find()->where(['access_token' => $access_token])->status(Order::STATUS_BLANK)->one())) {
            Good::deleteAll(['order_id' => $order->id]);
            $order->delete();
        }
        $this->_order = null;
        return ['result' => 'success'];
    }

    public function api_remove($id) {
        $good = Good::findOne($id);
        if (!$good) {
            return ['result' => 'error', 'error' => 'Good not found'];
        }
        if ($good->order_id != $this->order->id) {
            return ['result' => 'error', 'error' => 'Access denied'];
        }

        $good->delete();

        return ['result' => 'success', 'good_id' => $id, 'order_id' => $good->order_id];
    }

    public function api_update($goods) {
        if (is_array($goods) && count($this->order->goods)) {
            foreach ($this->order->goods as $good) {
                if (!empty($goods[$good->id])) {
                    $count = (int) $goods[$good->id];
                    if ($count > 0) {
                        $good->model->count = $count;
                        $good->model->update();
                    }
                }
            }
        }
    }

    public function api_create($phone, $name, $address, $comment, $delivery_id, $payment_id, $data = []) {
        $model = $this->order->model;

        if (!$this->order->id || $model->status != Order::STATUS_BLANK) {
            return ['result' => 'error', 'error' => 'Заказ не найден'];
        }
        if (!count($this->order->goods)) {
            return ['result' => 'error', 'error' => 'В заказе нет товаров'];
        }

        $delivery = Delivery::find()->where(['id' => $delivery_id])->status(Delivery::STATUS_ON)->andFilterWhere(['or', ['<=', 'available_from', $this->order->cost], ['available_from' => 0]])->one();
        if (!$delivery) {
            return ['result' => 'error', 'error' => Yii::t('admin/shopcart', 'Не найдена служба доставки')];
        }

        $payment = null;

        //Способы оплаты доступные для этой службы
        foreach ($delivery->payments as $_payment) {
            if ($_payment->id == $payment_id) {
                if ($_payment->status == Payment::STATUS_ON) {
                    //Способ оплаты доступен при стоимости корзины до
                    if ($_payment->available_to > 0) {
                        if ($_payment->available_to >= $this->order->cost) {
                            $payment = $_payment;
                            break;
                        }
                    } else {
                        $payment = $_payment;
                        break;
                    }
                }
            }
        }

        if (!$payment) {
            return ['result' => 'error', 'error' => Yii::t('admin/shopcart', 'Не найден способ оплаты')];
        }

        $model->phone = $phone;
        $model->name = $name;
        $model->comment = $comment;
        //Нужен ли адрес для доставки
        if ($delivery->need_address) {
            $model->address = $address;
        }
        $model->data = $data;
        $model->status = Order::STATUS_PENDING;

        //Служба доставки
        $model->delivery_id = $delivery->id;
        $model->delivery_cost = $delivery->price;
        if ($delivery->free_from > 0 && $delivery->free_from <= $this->order->cost) {
            $model->delivery_cost = 0;
        }
        $model->delivery_details = $delivery->title;

        if (Yii::$app->user->isGuest) {
            if ($payment->is_manual) {
                //Способ оплаты
                $model->payment_id = $payment->id;
                $model->payment_details = $payment->title;
            } else {
                return ['result' => 'error', 'error' => Yii::t('admin/shopcart', 'Онлайн-оплата возможна, только для зарегистрированных пользователей!')];
            }
        } else {



            $user = User::findByUsername(Yii::$app->user->identity->email);
            //Заполняем данные профиля пользователя
            $data = $user->data;
            
            $data['phone'] = $model->phone;
            $data['name'] = $model->name;
            $data['address'] = $address;
            
            $user->data = $data;    
            
            $user->save();

            //Пользователь авторизован, меняем на его email
            $model->email = $user->email;
            $model->user_id = $user->id;

            //Способ оплаты
            $model->payment_id = $payment->id;
            $model->payment_details = $payment->title;
        }

        if ($model->save()) {
            return [
                'status' => 'success',
                'order_id' => $model->id,
                'token' => $model->access_token,
                'payment_is_manual' => $payment->is_manual,
            ];
        } else {
            return ['result' => 'error', 'error' => $model->formatErrors()];
        }
    }

    public function api_cost() {
        return $this->order->cost;
    }

    public function getOrder() {
        if (!$this->_order) {
            $access_token = $this->token;

            if (!$access_token || !($order = Order::find()->where(['access_token' => $access_token])->status(Order::STATUS_BLANK)->one())) {
                $order = new Order();
            }

            $this->_order = new OrderObject($order);
        }
        return $this->_order;
    }

    public function getToken() {
        return Yii::$app->session->get(Order::SESSION_KEY);
    }

}
