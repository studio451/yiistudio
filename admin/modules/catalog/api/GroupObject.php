<?

namespace admin\modules\catalog\api;

use Yii;
use admin\components\API;
use admin\modules\catalog\models\Item;
use yii\helpers\Url;

class GroupObject extends \admin\components\ApiObject {

    public $category_id;
    public $time;
    private $_item;
    private $_items;

    public function getTitle() {
        return LIVE_EDIT ? API::liveEdit($this->model->title, $this->editLink) : $this->model->title;
    }

    public function getDescription() {
        return LIVE_EDIT ? API::liveEdit($this->model->description, $this->editLink, 'div') : $this->model->description;
    }

    public function getCategory() {
        return Category::flat()[$this->category_id];
    }

    public function getDate() {
        return Yii::$app->formatter->asDate($this->time);
    }

    public function getItems() {
        if (!$this->_items) {

            $this->_items = [];

            $query = Item::find()->where(['group_id' => $this->id])->status(Item::STATUS_ON);

            if (!empty($this->options['where'])) {
                $query->andFilterWhere($this->options['where']);
            }
            if (!empty($this->options['orderBy'])) {
                $query->orderBy($this->options['orderBy']);
            } else {
                $query->sortDate();
            }
            
            $filters = $this->options['filters'];
            unset($filters['brand_id']);
            unset($filters['category_id']);
            if (!empty($filters)) {
                $query = Catalog::applyFiltersForItems($filters, $query);
            }

            if (!$this->_items) {
                $items = $query->all();
                foreach ($items as $item) {
                    $this->_items[] = new ItemObject($item);
                }
            }
        }
        return $this->_items;
    }

    public function getItem() {
        if (!$this->_item) {
            if (count($this->items)) {
                $this->_item = $this->items[0];
            }
        }
        return $this->_item;
    }

    public function getEditLink() {
        return Url::to(['/admin/catalog/item/edit', 'id' => $this->id]);
    }

}
