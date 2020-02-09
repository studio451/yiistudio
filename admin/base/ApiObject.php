<?

namespace admin\base;

use Yii;
use admin\helpers\Image;
use admin\modules\seo\models\SeoTemplate;

/**
 * Class ApiObject
 * @package admin\base
 */
class ApiObject extends \yii\base\BaseObject {

    /** @var \yii\base\Model  */
    public $model;
    public $options;

    /**
     * Generates ApiObject, attaching all settable properties to the child object
     * @param \yii\base\Model $model
     */
    public function __construct($model, $options = []) {
        $this->model = $model;
        $this->options = $options;

        foreach ($model->attributes as $attribute => $value) {
            if ($this->canSetProperty($attribute)) {
                $this->{$attribute} = $value;
            }
        }

        $this->init();
    }

    /**
     * calls after __construct
     */
    public function init() {
        
    }

    /**
     * Returns object id
     * @return int
     */
    public function getId() {
        return $this->model->primaryKey;
    }

    /**
     * Creates thumb from model->image attribute with specified width and height.
     * @param int|null $width
     * @param int|null $height
     * @param bool $crop if false image will be resize instead of cropping
     * @return string
     */
    public function thumb($width = null, $height = null, $crop = false) {
        if ($this->image && ($width || $height)) {
            return Image::thumb($this->image, $width, $height, $crop);
        }
        return '';
    }

    public function getEditLink() {
        return '';
    }

    public function getCreateLink() {
        return '';
    }

    /**
     * Get seo text or parse seo template attached to object
     * @param string $attribute name of seo attribute can be h1, title, description, keywords
     * @return string
     */
    public function seo($attribute, $value = '') {

        $title = $this->model->title;
        if ($value) {
            $title = $value;
        }

        //Если установлен SeoText
        if ($this->model->getBehavior('seoText')) {
            if (!empty($this->model->seoText->{$attribute})) {
                $result = $this->model->seoText->{$attribute};
            }
        }

        if (empty($result)) {
            //Если установлен SeoTemplate
            if ($this->model->getBehavior('seoTemplate')) {
                if ($this->model->seoTemplate->template_id) {
                    $result = SeoTemplate::parse($this->model->seoTemplate->template_id, $attribute, ['title' => $title]);
                }
            } else {
                //Если объект является элементом категории, у которой установлен SeoTemplate
                if (in_array('category_id', $this->model->attributes())) {
                    if ($this->model->category->getBehavior('seoTemplate')) {
                        if ($this->model->category->seoTemplate->item_template_id) {
                            $result = SeoTemplate::parse($this->model->category->seoTemplate->item_template_id, $attribute, ['title' => $title]);
                        }
                    }
                }
            }
        }

        if (empty($result)) {
            $result = $title;
        }

        if ($attribute == 'h1') {
            if (empty($result)) {
                $result = $this->createLink;
            } else {
                if (LIVE_EDIT) {
                    $result = Api::liveEdit($result, $this->editLink);
                }
            }
        }
        return $result;
    }

}
