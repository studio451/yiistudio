<?

namespace admin\modules\yml\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

use admin\modules\yml\models\Import;
use admin\modules\yml\helpers\WebConsole;

class ImportController extends \admin\base\admin\Controller {

    public function actionIndex() {
        $data = new ActiveDataProvider([
            'query' => Import::find(),
        ]);
        return $this->render('index', [
                    'data' => $data
        ]);
    }

    public function actionCreate($slug = null) {
        $model = new Import();

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                if ($model->save()) {
                    $this->flash('success', Yii::t('admin/yml', 'Импорт создан'));
                    return $this->redirect(['/admin/' . $this->module->id . '/import']);
                } else {
                    $this->flash('error', Yii::t('admin', 'Ошибка. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        } else {
            if ($slug) {
                $model->slug = $slug;
            }
            return $this->render('create', [
                        'model' => $model
            ]);
        }
    }

    public function actionEdit($id) {
        $model = Import::findOne($id);


        if ($model === null) {
            $this->flash('error', Yii::t('admin', 'Запись не найдена'));
            return $this->redirect(['/admin/' . $this->module->id . '/import']);
        }

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                if ($model->save()) {
                    $this->flash('success', Yii::t('admin/yml', 'Импорт обновлен'));
                } else {
                    $this->flash('error', Yii::t('admin', 'Ошибка при обновлении записи. {0}', $model->formatErrors()));
                }
                return $this->refresh();
            }
        } else {
            return $this->render('edit', [
                        'model' => $model,
            ]);
        }
    }

    public function actionDelete($id) {
        if (($model = Import::findOne($id))) {
            $model->delete();
        } else {
            $this->error = Yii::t('admin', 'Запись не найдена');
        }
        return $this->formatResponse(Yii::t('admin/yml', 'Импорт удален'));
    }

    public function actionExecute($id, $full = null) {

        if (($model = Import::findOne($id))) {
            if ($full) {
                $result = WebConsole::ymlFullImport($id);
            } else {
                $result = WebConsole::ymlImport($id);
            }
        } else {
            $this->error = Yii::t('admin', 'Запись не найдена');
        }
        return $this->formatResponse($result, true, true);

    }

    public function actionLoadItemsFromExcelFile($id) {

        if (Yii::$app->request->post()) {
            if (($model = Import::findOne($id))) {
                $model->scenario = 'import';
                $model->importFile = UploadedFile::getInstance($model, 'importFile');
                $fileName = $model->upload();
                if ($fileName) {
                    $result = WebConsole::ymlLoadItemsFromExcelFile($id, $fileName);
                    return $this->formatResponse($result, true, true);
                } else {
                    return $this->formatResponse(Yii::t('admin/yml', 'Файл не загружен!'), true, true);
                }
            } else {
                $this->error = Yii::t('admin', 'Запись не найдена');
            }
        } else {
            return $this->render('edit', [
                        'model' => $model,
            ]);
        }
    }

}
