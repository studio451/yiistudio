<?

namespace admin\modules\comment\controllers\api;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use admin\modules\comment\events\CommentEvent;
use admin\modules\comment\models\Comment;

/**
 * Class DefaultController
 *
 * @package admin\modules\comment\controllers
 */
class CommentController extends Controller
{
    /**
     * Event is triggered before creating a new comment.
     * Triggered with admin\modules\comment\events\CommentEvent
     */
    const EVENT_BEFORE_CREATE = 'beforeCreate';

    /**
     * Event is triggered after creating a new comment.
     * Triggered with admin\modules\comment\events\CommentEvent
     */
    const EVENT_AFTER_CREATE = 'afterCreate';

    /**
     * Event is triggered before deleting the comment.
     * Triggered with admin\modules\comment\events\CommentEvent
     */
    const EVENT_BEFORE_DELETE = 'beforeDelete';

    /**
     * Event is triggered after deleting the comment.
     * Triggered with admin\modules\comment\events\CommentEvent
     */
    const EVENT_AFTER_DELETE = 'afterDelete';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
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

    /**
     * Create a comment.
     *
     * @param $entity string encrypt entity
     *
     * @return array
     */
    public function actionCreate($entity)
    {
        /* @var $commentModel Comment */
        $commentModel = Yii::createObject(Yii::$app->getModule('admin')->activeModules['comment']->settings['commentModelClass']);
        $event = Yii::createObject(['class' => CommentEvent::class, 'commentModel' => $commentModel]);
        $commentModel->setAttributes($this->getCommentAttributesFromEntity($entity));
        $this->trigger(self::EVENT_BEFORE_CREATE, $event);
        if ($commentModel->load(Yii::$app->request->post()) && $commentModel->saveComment()) {
            $this->trigger(self::EVENT_AFTER_CREATE, $event);

            return ['status' => 'success'];
        }

        return [
            'status' => 'error',
            'errors' => ActiveForm::validate($commentModel),
        ];
    }

    /**
     * Delete comment.
     *
     * @param int $id Comment ID
     *
     * @return string Comment text
     */
    public function actionDelete($id)
    {
        $commentModel = $this->findModel($id);
        $event = Yii::createObject(['class' => CommentEvent::class, 'commentModel' => $commentModel]);
        $this->trigger(self::EVENT_BEFORE_DELETE, $event);

        if ($commentModel->markRejected()) {
            $this->trigger(self::EVENT_AFTER_DELETE, $event);

            return Yii::t('admin/comment', 'Комментарий был удалён');
        } else {
            Yii::$app->response->setStatusCode(500);

            return Yii::t('admin/comment', 'Не удалось удалить комментарий. Попробуйте пожалуйста еще раз!');
        }
    }

    /**
     * Find model by ID.
     *
     * @param int|array $id Comment ID
     *
     * @return Comment
     *
     * @throws NotFoundHttpException
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

    /**
     * Get list of attributes from encrypted entity
     *
     * @param $entity string encrypted entity
     *
     * @return array|mixed
     *
     * @throws BadRequestHttpException
     */
    protected function getCommentAttributesFromEntity($entity)
    {
        $decryptEntity = Yii::$app->getSecurity()->decryptByKey(utf8_decode($entity),'comment');
        if ($decryptEntity !== false) {
            return Json::decode($decryptEntity);
        }

        throw new BadRequestHttpException(Yii::t('admin/comment', 'Ошибка! Попробуйте пожалуйста еще раз!'));
    }
}
