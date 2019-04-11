<?

namespace admin\modules\yml\controllers;

use Yii;
use yii\web\UploadedFile;

use admin\modules\yml\models\Import;
use admin\modules\yml\helpers\WebConsole;


class ExcelController extends \admin\base\admin\Controller {

    public function actionIndex() {
        $model = new Import(['scenario' => 'import']);
        return $this->render('index', [ 'model' => $model]);
    }

    public function actionAddItemsFromExcelFile() {

        $model = new Import(['scenario' => 'import']);
        if (Yii::$app->request->post()) {
            $model->importFile = UploadedFile::getInstance($model, 'importFile');
            $fileName = $model->upload();
            if ($fileName) {
                $result = WebConsole::ymlAddItemsFromExcelFile($fileName);
                return $this->formatResponse($result, true, true);
            } else {
                return $this->formatResponse(Yii::t('admin/yml', 'Файл не загружен!'), true, true);
            }
        } else {
            return $this->render('index', [
                        'model' => $model,
            ]);
        }
    }
    
    public function actionUpdateItemsFromExcelFile() {

        $model = new Import(['scenario' => 'import']);
        if (Yii::$app->request->post()) {
            $model->importFile = UploadedFile::getInstance($model, 'importFile');
            $fileName = $model->upload();
            if ($fileName) {
                $result = WebConsole::ymlUpdateItemsFromExcelFile($fileName);
                return $this->formatResponse($result, true, true);
            } else {
                return $this->formatResponse(Yii::t('admin/yml', 'Файл не загружен!'), true, true);
            }
        } else {
            return $this->render('index', [
                        'model' => $model,
            ]);
        }
    }

    public function actionLoadCategoriesFromExcelFile() {

        $model = new Import(['scenario' => 'import']);
        if (Yii::$app->request->post()) {
            $model->importFile = UploadedFile::getInstance($model, 'importFile');
            $fileName = $model->upload();
            if ($fileName) {
                $result = WebConsole::ymlLoadCategoriesFromExcelFile($fileName);
                return $this->formatResponse($result, true, true);
            } else {
                return $this->formatResponse(Yii::t('admin/yml', 'Файл не загружен!'), true, true);
            }
        } else {
            return $this->render('index', [
                        'model' => $model,
            ]);
        }
    }

    public function actionLoadNewsFromExcelFile() {

        $model = new Import(['scenario' => 'import']);
        if (Yii::$app->request->post()) {
            $model->importFile = UploadedFile::getInstance($model, 'importFile');
            $fileName = $model->upload();
            if ($fileName) {
                $result = WebConsole::ymlLoadNewsFromExcelFile($fileName);
                return $this->formatResponse($result, true, true);
            } else {
                return $this->formatResponse(Yii::t('admin/yml', 'Файл не загружен!'), true, true);
            }
        } else {
            return $this->render('index', [
                        'model' => $model,
            ]);
        }
    }

    public function actionLoadUsersFromExcelFile() {

        $model = new Import(['scenario' => 'import']);
        if (Yii::$app->request->post()) {
            $model->importFile = UploadedFile::getInstance($model, 'importFile');
            $fileName = $model->upload();
            if ($fileName) {
                $result = Import::loadUsersFromExcelFile($fileName);
                //$result = WebConsole::ymlLoadUsersFromExcelFile($fileName);
                return $this->formatResponse($result, true, true);
            } else {
                return $this->formatResponse(Yii::t('admin/yml', 'Файл не загружен!'), true, true);
            }
        } else {
            return $this->render('index', [
                        'model' => $model,
            ]);
        }
    }

}
