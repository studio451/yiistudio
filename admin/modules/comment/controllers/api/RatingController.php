<?

namespace admin\modules\comment\controllers\api;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

class RatingController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['post'],
                    'delete' => ['post', 'delete'],
                ],
            ],
            'contentNegotiator' => [
                'class' => 'yii\filters\ContentNegotiator',
                'only' => ['create'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    public function actionCreate($entity) {
        $ratingModel = Yii::createObject(Yii::$app->getModule('admin')->activeModules['comment']->settings['ratingModelClass']);
        $ratingModel->setAttributes($this->getRatingAttributesFromEntity($entity));

        if (Yii::$app->getSession()->has('rating_session')) {
            $session = Yii::$app->getSession()->get('rating_session');
        } else {
            $session = uniqid(md5(rand()), true);
            Yii::$app->getSession()->set('rating_session', $session);
        }
        $ratingModel->session = $session;

        $oldRatingModel = $this->findModel($ratingModel->entity, $ratingModel->entityId, $ratingModel->session);
        if ($oldRatingModel) {
            if ($oldRatingModel->load(Yii::$app->request->post()) && $oldRatingModel->update()) {
                return ['status' => 'success'];
            }
        } else {
            if ($ratingModel->load(Yii::$app->request->post()) && $ratingModel->save()) {
                return ['status' => 'success'];
            }
        }

        return [
            'status' => 'error',
            'errors' => ActiveForm::validate($ratingModel),
        ];
    }

    protected function findModel($entity, $entityId, $session) {
        $ratingModel = Yii::$app->getModule('admin')->activeModules['comment']->settings['ratingModelClass'];
        if (($model = $ratingModel::find()->where(['entity' => $entity, 'entityId' => $entityId, 'session' => $session])->one()) !== null) {
            return $model;
        } else {
            return null;
        }
    }

    protected function getRatingAttributesFromEntity($entity) {
        $decryptEntity = Yii::$app->getSecurity()->decryptByKey(utf8_decode($entity), 'rating');
        if ($decryptEntity !== false) {
            return Json::decode($decryptEntity);
        }

        throw new BadRequestHttpException(Yii::t('admin/comment', 'Ошибка! Попробуйте пожалуйста еще раз!'));
    }

}
