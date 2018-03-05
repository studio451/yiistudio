<?

namespace admin\controllers;

use Yii;
use yii\web\NotFoundHttpException;

use admin\models\TranslateMessage;
use admin\models\translate\TranslateMessageSearch;
use admin\helpers\WebConsole;
use yii\helpers\Json;

class TranslateController extends \admin\components\Controller
{
    /**
     * Lists all TranslateMessage models.
     */
    public function actionIndex() {
        $searchModel = new TranslateMessageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TranslateMessage model.
     */
    public function actionCreate() {
        $model = new TranslateMessage();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TranslateMessage model.
     */
    public function actionUpdate($id, $language) {
        $model = $this->findModel($id, $language);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TranslateSourceMessage model.
     */
    public function actionDelete($id) {
        \admin\models\TranslateSourceMessage::findOne(['id' => $id])->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TranslateMessage model based on its primary key value.
     */
    protected function findModel($id, $language) {
        if (($model = TranslateMessage::findOne(['id' => $id, 'language' => $language])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionMessageExtract() {

        $result = WebConsole::messageExtract();

        return $this->formatResponse($result, true, true);
    }

    
    //Editable Fields for KartikGrid
    public function actionUpdateJson() {

        if (Yii::$app->request->post('hasEditable')) {
            $editableKey = Json::decode(Yii::$app->request->post('editableKey'));

            if ($editableKey) {

                $nameModel = 'TranslateMessage';

                $model = TranslateMessage::findOne($editableKey);

                if (Yii::$app->request->post('editableSimple')) {
                    $posted = Yii::$app->request->post($nameModel);
                } else {
                    $posted = current(Yii::$app->request->post($nameModel));
                }
                $post = [$nameModel => $posted];

                if ($model->load($post)) {
                    $model->save();
                }

                echo Json::encode(['output' => '', 'message' => '']);
            } else {
                echo Json::encode(['output' => '', 'message' => Yii::t('admin', 'Ошибка! Попробуйте выполнить операцию позже!')]);
            }
        }
    }
}
