<?

namespace admin\modules\payment\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use admin\components\Controller;
use admin\modules\payment\models\Payment;
use admin\modules\shopcart\models\Order;

use admin\behaviors\SortableController;
use admin\behaviors\StatusController;

class AController extends Controller {

    public function behaviors() {
        return [
            [
                'class' => SortableController::className(),
                'model' => Payment::className()
            ],
            [
                'class' => StatusController::className(),
                'model' => Payment::className()
            ]
        ];
    }

    public function actionIndex() {
        $data = new ActiveDataProvider([
            'query' => Payment::find()->sort(),
        ]);

        return $this->render('index', [
                    'data' => $data
        ]);
    }

    public function actionCreate() {
        $model = new Payment;

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                if ($model->save()) {
                    $this->flash('success', Yii::t('admin/payment', 'Способ оплаты создан'));
                    return $this->redirect(['/admin/' . $this->module->id]);
                } else {
                    $this->flash('error', Yii::t('admin', 'Ошибка. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        } else {

            $model->class = "admin\modules\payment\payment_systems\Manual";

            return $this->render('create', [
                        'model' => $model
            ]);
        }
    }

    public function actionEdit($id) {
        $model = Payment::findOne($id);
        $class = $model->class;
        $model = $class::findOne($model->id);

        if ($model === null) {
            $this->flash('error', Yii::t('admin', 'Способ оплаты не найден'));
            return $this->redirect(['/admin/' . $this->module->id]);
        }

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {

                if ($model->save()) {
                    $this->flash('success', Yii::t('admin/payment', 'Способ оплаты обновлен'));
                } else {
                    $this->flash('error', Yii::t('admin', 'Ошибка при обновлении записи. {0}', $model->formatErrors()));
                }
                return $this->refresh();
            }
        } else {
            return $this->render('edit', [
                        'model' => $model
            ]);
        }
    }

    public function actionDelete($id) {
        if (($model = Payment::findOne($id))) {
            $model->delete();
        } else {
            $this->error = Yii::t('admin', 'Способ оплаты не найден');
        }
        return $this->formatResponse(Yii::t('admin/payment', 'Способ оплаты удален'));
    }

    public function actionUp($id) {
        return $this->move($id, 'up');
    }

    public function actionDown($id) {
        return $this->move($id, 'down');
    }

    public function actionOn($id) {
        return $this->changeStatus($id, Payment::STATUS_ON);
    }

    public function actionOff($id) {
        return $this->changeStatus($id, Payment::STATUS_OFF);
    }

    public function actionDefaultData($id) {
        $model = Payment::findOne($id);
        $class = $model->class;
        $model = $class::findOne($model->id);

        if ($model === null) {
            $this->flash('error', Yii::t('admin', 'Способ оплаты не найден'));
        } else {
            $model->setDefaultData();
            $model->save();
            $this->flash('success', Yii::t('admin', 'Установлены настройки по-умолчанию'));
        }
        return $this->back();
    }

    public function actionProcess($id) {

        $order = Order::findOne($id);

        if ($order === null) {
            $this->flash('error', Yii::t('admin', 'Запись не найдена'));
            return $this->redirect(['/admin/shopcart/a/edit', 'id' => $order->id]);
        }

        if (Yii::$app->request->post()) {
            $order->paid_status = Yii::$app->request->post('paid_status');
            $order->paid_time = time();
            $order->paid_details = 'Статус оплаты заказа изменен в панели управления на "' . $order->getPaidStatusName() . '"';

            if ($order->save()) {
                $this->flash('success', Yii::t('admin/shopcart', 'Статус оплаты заказа изменен'));
                Payment::adminProcessCheckout('Статус оплаты заказа изменен на "' . $order->getPaidStatusName() . '"', $order);
            } else {
                $this->flash('error', Yii::t('admin', 'Ошибка при обновлении записи. {0}', $order->formatErrors()));
                Payment::adminProcessCheckout('Ошибка при обновлении записи. {0}', $order->formatErrors(), $order);
            }
        }
        return $this->redirect(['/admin/shopcart/a/edit', 'id' => $order->id]);
    }

    public function actionTest($id) {
        $model = Payment::findOne($id);
        $class = $model->class;
        $model = $class::findOne($model->id);

        if ($model === null) {
            $this->flash('error', Yii::t('admin', 'Способ оплаты не найден'));
            return $this->redirect(['/admin/' . $this->module->id]);
        }

        if (Yii::$app->request->post()) {
            $model->test(Yii::$app->request->post('order_id'), Yii::$app->request->post('amount'));
            $this->flash('warning', Yii::t('admin', 'HTTP-уведомление отправлено'));
        }
        return $this->redirect(['/admin/payment/a/edit', 'id' => $id]);
    }

}
