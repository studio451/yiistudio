<?

namespace admin\modules\shopcart\controllers\api;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use admin\modules\catalog\api\Catalog;
use admin\modules\shopcart\api\Shopcart;
use admin\models\User;

class ShopcartController extends \admin\components\APIController {

    public function actionIndex() {

        $orderFormClass = '\\' . APP_NAME . '\models\OrderForm';
        $orderForm = new $orderFormClass();


        if (!Yii::$app->user->isGuest) {
            //Заполняем профиль пользователя
            $user = User::findByUsername(Yii::$app->user->identity->email);
            $orderForm->attributes = $user->data;
        }

        return $this->render(Yii::$app->getModule('admin')->activeModules['shopcart']->settings['templateShopcartIndex'], [
                    'goods' => Shopcart::goods(),
                    'orderForm' => $orderForm,
        ]);
    }

    public function actionCost() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['cost' => Shopcart::cost()];
    }

    public function actionSuccess($id, $token = null) {
        if ($token) {
            return $this->render(Yii::$app->getModule('admin')->activeModules['shopcart']->settings['templateShopcartSuccess'], ['id' => $id, 'token' => $token]);
        } else {
            return $this->render(Yii::$app->getModule('admin')->activeModules['shopcart']->settings['templateShopcartSuccessGuest'], ['id' => $id]);
        }
    }

    public function actionSuccessPayment($id) {
        return $this->render(Yii::$app->getModule('admin')->activeModules['shopcart']->settings['templateShopcartSuccessPayment'], ['id' => $id]);
    }

    public function actionCreate() {

        $orderFormClass = '\\' . APP_NAME . '\models\OrderForm';
        $orderForm = new $orderFormClass();

        if ($orderForm->load(Yii::$app->request->post())) {
            $delivery_id = Yii::$app->request->post('delivery_id');
            if ($orderForm->validate()) {
                $payment_id = Yii::$app->request->post('payment_id')[$delivery_id];
                $result = Shopcart::create($orderForm->phone, $orderForm->name, $orderForm->address, $orderForm->comment, $delivery_id, $payment_id, []);
                if ($result['status'] == 'success') {
                    Yii::$app->session->remove('shopcartUserInputData');
                    if (Yii::$app->user->isGuest) {
                        return $this->redirect(['/shopcart/success', 'id' => $result['order_id']]);
                    } else {
                        if ($result['payment_is_manual']) {
                            return $this->redirect(['/shopcart/success', 'id' => $result['order_id'], 'token' => $result['token']]);
                        } else {
                            Yii::$app->session->setFlash('success', Yii::t('admin/shopcart', 'Ваш заказ успешно создан!'));
                            return $this->redirect(['/shopcart/order', 'id' => $result['order_id'], 'token' => $result['token']]);
                        }
                    }
                } else {
                    Yii::$app->session->setFlash('error', $result['error']);
                }
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionAdd($id) {
        $item = Catalog::item($id);

        if (!$item) {
            throw new NotFoundHttpException(Yii::t('admin/shopcart', 'Элемент не найден'));
        }

        $addToCartFormClass = '\\' . APP_NAME . '\models\AddToCartForm';
        $addToCartForm = new $addToCartFormClass();
        $success = 0;
        if ($addToCartForm->load(Yii::$app->request->post()) && $addToCartForm->validate()) {
            $response = Shopcart::add($item->id, $addToCartForm->count);
            $success = $response['result'] == 'success' ? 1 : 0;
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        return ['success' => $success, 'cost' => $response['cost']];
    }

    public function actionRemove($id) {
        Shopcart::remove($id);
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionUpdate() {
        Shopcart::update(Yii::$app->request->post('Good'));
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionOrder($id, $token) {
        $orderFormClass = '\\' . APP_NAME . '\models\OrderForm';
        $orderForm = new $orderFormClass();
        $order = Shopcart::order($id);
        if (!$order || $order->access_token != $token || !Yii::$app->user->can('userShopcartOrderPermission', ['order' => $order])) {
            throw new NotFoundHttpException(Yii::t('admin/shopcart', 'Заказ не найден'));
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax(Yii::$app->getModule('admin')->activeModules['shopcart']->settings['templateShopcartOrder'], ['order' => $order, 'orderForm' => $orderForm]);
        } else {
            return $this->render(Yii::$app->getModule('admin')->activeModules['shopcart']->settings['templateShopcartOrder'], ['order' => $order, 'orderForm' => $orderForm]);
        }
    }

    public function actionOrders() {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax(Yii::$app->getModule('admin')->activeModules['shopcart']->settings['templateShopcartOrders'], [
                        'orders' => Shopcart::orders([
                            'status' => $status, //!!!
                            'pagination' => [
                                'defaultPageSize' => 10,
                                'forcePageParam' => false,
                                'pageSizeParam' => false,
                                'pageParam' => 'page',
                            ],
                        ]),
                        'orders_pages' => Shopcart::orders_pages(),
            ]);
        } else {
            return $this->render(Yii::$app->getModule('admin')->activeModules['shopcart']->settings['templateShopcartOrders'], [
                        'orders' => Shopcart::orders([
                            'status' => $status, //!!!
                            'pagination' => [
                                'defaultPageSize' => 10,
                                'forcePageParam' => false,
                                'pageSizeParam' => false,
                                'pageParam' => 'page',
                            ],
                        ]),
                        'orders_pages' => Shopcart::orders_pages(),
            ]);
        }
    }

    public function actionFast($id) {
        $item = Catalog::item($id);

        if (!$item) {
            throw new NotFoundHttpException(Yii::t('admin/shopcart', 'Элемент не найден'));
        }

        Shopcart::clear();
        Shopcart::add($item->id, 1);

        $orderFormClass = '\\' . APP_NAME . '\models\OrderForm';
        $orderForm = new $orderFormClass();

        if (!Yii::$app->user->isGuest) {
            //Заполняем профиль пользователя
            $user = User::findByUsername(Yii::$app->user->identity->email);
            $orderForm->attributes = $user->data;
        }

        return $this->renderAjax(Yii::$app->getModule('admin')->activeModules['shopcart']->settings['templateShopcartFast'], [
                    'goods' => Shopcart::goods(),
                    'orderForm' => $orderForm,
        ]);
    }

}
