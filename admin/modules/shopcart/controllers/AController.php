<?

namespace admin\modules\shopcart\controllers;

use Yii;
use yii\data\ActiveDataProvider;

use admin\modules\shopcart\models\Good;
use admin\modules\shopcart\models\Order;

class AController extends \admin\base\admin\Controller {

    public $all = 0;
    public $pending = 0;
    public $processed = 0;
    public $sent = 0;

    public function init() {
        parent::init();

        $this->all = Order::find()->where(['<>','status',(int)Order::STATUS_BLANK])->count();
        $this->pending = Order::find()->status(Order::STATUS_PENDING)->count();
        $this->processed = Order::find()->status(Order::STATUS_PROCESSED)->count();
        $this->sent = Order::find()->status(Order::STATUS_SENT)->count();
    }

    public function actionIndex() {
        return $this->render('index', [
                    'data' => new ActiveDataProvider([
                        'query' => Order::find()->with('goods')->where(['<>','status',(int)Order::STATUS_BLANK])->desc(),
                        'totalCount' => $this->all
                            ])
        ]);
    }

    public function actionPending() {
        return $this->render('index', [
                    'data' => new ActiveDataProvider([
                        'query' => Order::find()->with('goods')->status(Order::STATUS_PENDING)->desc(),
                        'totalCount' => $this->pending
                            ])
        ]);
    }
    
    public function actionProcessed() {
        return $this->render('index', [
                    'data' => new ActiveDataProvider([
                        'query' => Order::find()->with('goods')->status(Order::STATUS_PROCESSED)->desc(),
                        'totalCount' => $this->processed
                            ])
        ]);
    }

    public function actionSent() {
        return $this->render('index', [
                    'data' => new ActiveDataProvider([
                        'query' => Order::find()->with('goods')->status(Order::STATUS_SENT)->desc(),
                        'totalCount' => $this->sent
                            ])
        ]);
    }

    public function actionCompleted() {
        return $this->render('index', [
                    'data' => new ActiveDataProvider([
                        'query' => Order::find()->with('goods')->status(Order::STATUS_COMPLETED)->desc()
                            ])
        ]);
    }

    public function actionFails() {
        return $this->render('index', [
                    'data' => new ActiveDataProvider([
                        'query' => Order::find()->with('goods')->where(['in', 'status', [Order::STATUS_DECLINED, Order::STATUS_ERROR, Order::STATUS_RETURNED]])->desc()
                            ])
        ]);
    }

    public function actionBlank() {
        return $this->render('index', [
                    'data' => new ActiveDataProvider([
                        'query' => Order::find()->with('goods')->status(Order::STATUS_BLANK)->desc()
                            ])
        ]);
    }

    public function actionEdit($id) {
        $request = Yii::$app->request;

        $orderFormClass = '\\' . APP_NAME . '\models\OrderForm';
        $orderForm = new $orderFormClass();

        $order = Order::findOne($id);

        if ($order === null) {
            $this->flash('error', Yii::t('admin', 'Запись не найдена'));
            return $this->redirect(['/admin/' . $this->module->id]);
        }


        if ($request->post('status')) {
            $newStatus = $request->post('status');
            $oldStatus = $order->status;

            $order->status = $newStatus;
            $order->remark = filter_var($request->post('remark'), FILTER_SANITIZE_STRING);

            if ($order->save()) {
                if ($newStatus != $oldStatus && $request->post('notify')) {
                    $order->notifyUser();
                }
                $this->flash('success', Yii::t('admin/shopcart', 'Заказ обновлен'));
            } else {
                $this->flash('error', Yii::t('admin', 'Ошибка при обновлении записи. {0}', $order->formatErrors()));
            }
            return $this->refresh();
        } else {
            if ($order->new > 0) {
                $order->new = 0;
                $order->update();
            }
            $order = Order::findOne($id);
            $goods = Good::find()->where(['order_id' => $order->primaryKey])->with('item')->asc()->all();

            return $this->render('edit', [
                        'order' => $order,
                        'goods' => $goods,
                        'orderForm' => $orderForm
            ]);
        }
    }

    public function actionDelete($id) {
        if (($model = Order::findOne($id))) {
            $model->delete();
        } else {
            $this->error = Yii::t('admin', 'Запись не найдена');
        }
        return $this->formatResponse(Yii::t('admin/shopcart', 'Заказ удален'));
    }

    public function actionData($id) {

        $order = Order::findOne($id);

        if ($order === null) {
            $this->flash('error', Yii::t('admin', 'Запись не найдена'));
            return $this->redirect(['/admin/' . $this->module->id . '/a/edit', 'id' => $order->id]);
        }

        if (is_array(Yii::$app->request->post('data'))) {
            $order->data = Yii::$app->request->post('data');
            if ($order->save()) {
                $this->flash('success', Yii::t('admin/shopcart', 'Дополнительные данные заказа обновлены'));
                return $this->redirect(['/admin/shopcart/a/edit', 'id' => $order->id]);
            }
        }
        $this->flash('error', Yii::t('admin', 'Ошибка при обновлении записи. {0}', $order->formatErrors()));
        return $this->redirect(['/admin/shopcart/a/edit', 'id' => $order->id]);
    }

    public function actionExportToExcel($id) {

        $order = Order::findOne($id);

        if ($order === null) {
            $this->flash('error', Yii::t('admin', 'Запись не найдена'));
            return $this->redirect(['/admin/' . $this->module->id . '/a/edit', 'id' => $order->id]);
        }

        $class = Yii::$app->getModule('admin')->activeModules['shopcart']->settings['modelExportOrderToExcel'];
        
        $orderToExcel = new $class();
        $orderToExcel->export($order);
        
    }

}
