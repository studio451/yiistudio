<?

namespace admin\modules\yml\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use admin\components\Controller;
use admin\modules\yml\models\Export;
use admin\modules\yml\helpers\WebConsole;
use admin\models\Setting;

class ExportController extends Controller {

    public function actionIndex() {
        $data = new ActiveDataProvider([
            'query' => Export::find(),
        ]);
        return $this->render('index', [
                    'data' => $data
        ]);
    }

    public function actionCreate($slug = null) {
        $model = new Export();

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                if ($model->save()) {
                    $this->flash('success', Yii::t('admin/yml', 'Экспорт создан'));
                    return $this->redirect(['/admin/' . $this->module->id . '/export']);
                } else {
                    $this->flash('error', Yii::t('admin', 'Ошибка. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        } else {
            if ($slug) {
                $model->slug = $slug;
            }

            $model->shop_name = Setting::get('contact_name');
            $model->shop_company = Setting::get('contact_name');
            $model->shop_url = Setting::get('contact_url');

            return $this->render('create', [
                        'model' => $model
            ]);
        }
    }

    public function actionEdit($id) {
        $model = Export::findOne($id);

        if ($model === null) {
            $this->flash('error', Yii::t('admin', 'Запись не найдена'));
            return $this->redirect(['/admin/' . $this->module->id . '/export']);
        }

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                if ($model->save()) {
                    $this->flash('success', Yii::t('admin/yml', 'Экспорт обновлен'));
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
        if (($model = Export::findOne($id))) {
            $model->delete();
        } else {
            $this->error = Yii::t('admin', 'Запись не найдена');
        }
        return $this->formatResponse(Yii::t('admin/yml', 'Экспорт удален'));
    }

    public function actionExecute($id) {

        if (($model = Export::findOne($id))) {
            $result = WebConsole::ymlExport($id);
        } else {
            $this->error = Yii::t('admin', 'Запись не найдена');
        }
        return $this->formatResponse($result, true, true);
    }

    public function actionDefaultExcelExecute() {

        $model = new \admin\modules\yml\external_export\Shop();
        $model->title = Setting::get('contact_name');
        $model->to_excel = true;
        $model->asAttachment = true;

        $model->saveToExcelFile();
    }

}
