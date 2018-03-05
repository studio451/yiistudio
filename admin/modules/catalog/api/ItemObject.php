<?

namespace admin\modules\catalog\api;

use Yii;
use admin\components\API;
use admin\models\Photo;
use admin\modules\catalog\models\Item;
use admin\modules\catalog\models\Category;
use yii\helpers\Url;

class ItemObject extends \admin\components\ApiObject {

    public $type;
    public $name;
    public $article;
    public $slug;
    public $data;
    public $category_id;
    public $available;
    public $discount;
    public $gift;
    public $new;
    public $time;
    public $image;
    private $_photos;
    private $_group;
    private $_category;
    

    public function getKey() {
        return $this->model->key;
    }

    public function getTitle() {
        return $this->model->title;
    }

    public function getBrand() {
        return $this->model->brand;
    }

    public function getDescription() {
        return LIVE_EDIT ? API::liveEdit($this->model->description, $this->editLink, 'div') : $this->model->description;
    }

    public function getCategory() {
        if (!$this->_category) {
            $this->_category = Category::flat()[$this->category_id];
        }
        return $this->_category;
    }

    public function getGroup() {
        if (!$this->_group) {
            $this->_group = new GroupObject($this->model->group);
        }
        return $this->_group;
    }

    public function getPrice() {
        return $this->discount ? round($this->model->price * (1 - $this->discount / 100)) : $this->model->price;
    }

    public function getOldPrice() {
        return $this->model->price;
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
   
    public function getTags() {
        return $this->model->tagsArray;
    }
    
    public function getEditLink() {
        return Url::to(['/admin/catalog/item/edit', 'id' => $this->model->primaryKey]);
    }
}
