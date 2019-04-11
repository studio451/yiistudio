<?

namespace admin\modules\article\api;

use Yii;
use admin\base\Api;
use admin\models\Photo;
use admin\modules\article\models\Item;
use yii\helpers\Url;
use admin\modules\article\models\Category;

class ArticleObject extends \admin\base\ApiObject {

    /** @var  string */
    public $slug;
    public $image;
    public $views;
    public $time;

    /** @var  int */
    public $category_id;
    
    private $_photos;
    
    private $_category;

    public function getTitle() {
        return LIVE_EDIT ? Api::liveEdit($this->model->title, $this->editLink) : $this->model->title;
    }

    public function getShort() {
        return LIVE_EDIT ? Api::liveEdit($this->model->short, $this->editLink) : $this->model->short;
    }

    public function getText() {
        return LIVE_EDIT ? Api::liveEdit($this->model->text, $this->editLink, 'div') : $this->model->text;
    }

    public function getCategory() {
        if (!$this->_category) {
            $this->_category = Category::flat()[$this->category_id];
        }
        return $this->_category;
    }

    public function getTags() {
        return $this->model->tagsArray;
    }

    public function getDate() {
        return Yii::$app->formatter->asDate($this->time);
    }

    public function getPhotos() {
        if (!$this->_photos) {
            $this->_photos = [];

            foreach (Photo::find()->where(['class' => Item::className(), 'item_id' => $this->id])->sort()->all() as $model) {
                $this->_photos[] = new PhotoObject($model);
            }
        }
        return $this->_photos;
    }

    public function getEditLink() {
        return Url::to(['/admin/article/item/edit', 'id' => $this->id]);
    }

}
