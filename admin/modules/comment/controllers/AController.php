<?

namespace admin\modules\comment\controllers;

use Yii;
use admin\components\Controller;
use yii\web\NotFoundHttpException;
use admin\modules\comment\models\Comment;

/**
 * Class ManageController
 *
 * @package admin\modules\comment\controllers
 */
class AController extends Controller
{
    /**
     * @var string search class name for searching
     */
    public $searchClass = 'admin\modules\comment\models\search\CommentSearch';

    /**
     * Lists all comment.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = Yii::createObject($this->searchClass);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $commentModel =  Yii::$app->getModule('admin')->activeModules['comment']->settings['commentModelClass'];

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'commentModel' => $commentModel,
        ]);
    }

    /**
     * Updates an existing Comment model.
     *
     * If update is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('admin/comment', 'Комментарий сохранен'));

            return $this->redirect(['index']);
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing comment with children.
     *
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->deleteWithChildren();
        Yii::$app->session->setFlash('success', Yii::t('admin/comment', 'Комментарий удален'));

        return $this->redirect(['index']);
    }

    /**
     * Finds the Comment model based on its primary key value.
     *
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @throws NotFoundHttpException if the model cannot be found
     *
     * @return Comment
     */
    protected function findModel($id)
    {
        $commentModel =  Yii::$app->getModule('admin')->activeModules['comment']->settings['commentModelClass'];

        if (($model = $commentModel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('admin/comment', 'Комментарий не найден'));
        }
    }
}
