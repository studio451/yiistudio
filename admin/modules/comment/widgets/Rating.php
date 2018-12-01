<?

namespace admin\modules\comment\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Json;
use admin\modules\comment\assets\RatingAsset;

/**
 * Class Rating
 *
 * @package admin\modules\comment\widgets
 */
class Rating extends Widget
{
    /**
     * @var \yii\db\ActiveRecord|null Widget model
     */
    public $model;

    /**
     * @var string the view file that will render the rating
     */
    public $ratingView = '@admin/modules/comment/widgets/views/rating/index';

    /**
     * @var string rating form id
     */
    public $formId = 'rating-form';

    /**
     * @var string pjax container id
     */
    public $pjaxContainerId;

    /**
     * @var string entity id attribute
     */
    public $entityIdAttribute = 'id';

    /**
     * @var array rating widget client options
     */
    public $clientOptions = [];

    /**
     * @var string hash(crc32) from class name of the widget model
     */
    protected $entity;

    /**
     * @var int primary key value of the widget model
     */
    protected $entityId;
    
    /**
     * @var string encrypted entity
     */
    protected $encryptedEntity;

    /**
     * @var string rating wrapper tag id
     */
    protected $ratingWrapperId;

    /**
     * Initializes the widget params.
     */
    public function init()
    {
        parent::init();

        if (empty($this->model)) {
            throw new InvalidConfigException(Yii::t('admin/comment', 'Свойство "model" должно быть установлено'));
        }

        if (empty($this->pjaxContainerId)) {
            $this->pjaxContainerId = 'rating-pjax-container-' . $this->getId();
        }

        if (empty($this->model->{$this->entityIdAttribute})) {
            throw new InvalidConfigException(Yii::t('admin/comment', 'Свойство "entityIdAttribute" должно быть установлено'));
        }

        $this->entity = hash('crc32', get_class($this->model));
        $this->entityId = $this->model->{$this->entityIdAttribute};

        $this->encryptedEntity = $this->getEncryptedEntity();
        $this->ratingWrapperId = 'rating-'.$this->entity . $this->entityId;       
                
        $this->registerAssets();
    }

    /**
     * Executes the widget.
     *
     * @return string the result of widget execution to be outputted
     */
    public function run()
    {  
        $ratingClass = Yii::$app->getModule('admin')->activeModules['comment']->settings['ratingModelClass'];
        $ratingModel = Yii::createObject([
            'class' => $ratingClass,
            'entity' => $this->entity,
            'entityId' => $this->entityId,
        ]);       

        return $this->render($this->ratingView, [
            'rating' => $ratingClass::getRating($this->entity, $this->entityId),
            'ratingModel' => $ratingModel,
            'encryptedEntity' => $this->encryptedEntity,
            'pjaxContainerId' => $this->pjaxContainerId,
            'formId' => $this->formId,
            'ratingWrapperId' => $this->ratingWrapperId,
        ]);
    }

    /**
     * Get encrypted entity
     *
     * @return string
     */
    protected function getEncryptedEntity()
    {
        return utf8_encode(Yii::$app->getSecurity()->encryptByKey(Json::encode([
            'entity' => $this->entity,
            'entityId' => $this->entityId,
        ]), 'rating'));
    }

    /**
     * Register assets.
     */
    protected function registerAssets()
    {
        $view = $this->getView();
        RatingAsset::register($view);
        $view->registerJs("jQuery('#{$this->ratingWrapperId}').rating({$this->getClientOptions()});");
    }

    /**
     * @return string
     */
    protected function getClientOptions()
    {
        $this->clientOptions['pjaxContainerId'] = '#' . $this->pjaxContainerId;
        $this->clientOptions['formSelector'] = '#' . $this->formId;

        return Json::encode($this->clientOptions);
    }

}
