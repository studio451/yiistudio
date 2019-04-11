<?

namespace admin\modules\subscribe\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use admin\modules\subscribe\models\Subscriber;
use admin\modules\subscribe\models\History;
use admin\helpers\Mail;
use admin\behaviors\StatusController;

class AController extends \admin\base\admin\Controller {

    public function behaviors() {
        return [
                [
                'class' => StatusController::className(),
                'model' => History::className()
            ]
        ];
    }

    public function actionIndex() {
        $data = new ActiveDataProvider([
            'query' => Subscriber::find()->desc(),
        ]);
        return $this->render('index', [
                    'data' => $data
        ]);
    }

    public function actionHistory() {
        $data = new ActiveDataProvider([
            'query' => History::find()->desc(),
        ]);
        return $this->render('history', [
                    'data' => $data
        ]);
    }

    public function actionEdit($id) {

        $model = History::findOne($id);

        if ($model === null) {
            $this->flash('error', Yii::t('admin', 'Запись не найдена'));
            return $this->redirect(['/admin/' . $this->module->id . '/history']);
        }

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                if ($model->save()) {
                    $this->flash('success', Yii::t('admin/subscribe', 'Рассылка обновлена'));
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

    public function actionCreate() {
        $model = new History;

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {


                foreach (Subscriber::find()->all() as $subscriber) {

                    if ($model->mailing_list == "") {
                        $model->mailing_list = $subscriber->email;
                    } else {
                        $model->mailing_list .= ',' . $subscriber->email;
                    }
                }

                if ($model->save()) {
                    $this->flash('success', Yii::t('admin/subscribe', 'Рассылка успешно создана'));
                    return $this->redirect(['/admin/' . $this->module->id . '/a/history']);
                } else {
                    $this->flash('error', Yii::t('admin', 'Ошибка. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        } else {
            return $this->render('create', [
                        'model' => $model
            ]);
        }
    }

    public function actionSend($id) {
        $model = History::findOne($id);

        if ($model === null) {
            $this->flash('error', Yii::t('admin', 'Запись не найдена'));
            return $this->redirect(['/admin/' . $this->module->id . '/history']);
        }

        if ($this->send($model)) {
            $this->flash('success', Yii::t('admin/subscribe', 'Рассылка разослана подписчикам'));
            return $this->redirect(['/admin/' . $this->module->id . '/a/history']);
        } else {
            $this->flash('error', Yii::t('admin', 'Ошибка. {0}', $model->formatErrors()));
            return $this->refresh();
        }
    }

    public function actionDelete($id) {
        if (($model = History::findOne($id))) {
            $model->delete();
        } else {
            $this->error = Yii::t('admin', 'Запись не найдена');
        }
        return $this->formatResponse(Yii::t('admin/subscribe', 'Рассылка удалена'));
    }

    public function actionDeleteSubscriber($id) {
        if (($model = Subscriber::findOne($id))) {
            $model->delete();
        } else {
            $this->error = Yii::t('admin', 'Запись не найдена');
        }
        return $this->formatResponse(Yii::t('admin/subscribe', 'Подписчик удален'));
    }

    private function send($model) {
        $settings = Yii::$app->getModule('admin')->activeModules['subscribe']->settings;


        $emails = split(",", $model->mailing_list);

        foreach ($emails as $email) {
            if (Mail::send(
                            $email, $model->subject, $settings['templateNotifyUser'], [
                        'body' => $model->body,
                        'link' => Url::to(['/subscribe/unsubscribe', 'email' => $email], true),
                            ]
                    )) {
                $model->sent++;
            }
        }
        $model->status = History::STATUS_ON;
        return $model->save();
    }

    public function actionOn($id) {
        return $this->changeStatus($id, History::STATUS_ON);
    }

    public function actionOff($id) {
        return $this->changeStatus($id, History::STATUS_OFF);
    }

}
