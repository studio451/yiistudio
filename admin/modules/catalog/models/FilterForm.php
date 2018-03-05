<?

namespace admin\modules\catalog\models;

use Yii;
use yii\base\Model;

class FilterForm extends Model {

    public $brand_id;
    public $category_id;
    public $price_from;
    public $price_to;
    public $color;
    public $status;

    public function formName() {
        return '';
    }

    public function rules() {
        return [
            [['price_from', 'price_to'], 'number', 'min' => 0],
            [['status'], 'integer'],
            [['brand_id','category_id'], 'safe'],            
        ];
    }

    public function attributeLabels() {
        return [
            'brand_id' => Yii::t('admin/catalog', 'Бренд'),
            'category_id' => Yii::t('admin/catalog', 'Категория'),
            'price_from' => Yii::t('admin/catalog', 'Мин. цена'),
            'price_to' => Yii::t('admin/catalog', 'Макс. цена'),
            'status' => Yii::t('admin/catalog', 'Статус'),
        ];
    }

    public function parse($filters = []) {

        if ($this->category_id) {
            $filters['category_id'] = $this->category_id;
        }
        
        if ($this->brand_id) {
            $filters['brand_id'] = $this->brand_id;
        }

        if ($this->price_from > 0 || $this->price_to > 0) {
            $filters['price'] = [$this->price_from, $this->price_to];
        }

        if ($this->status != '') {
            $filters['status'] = $this->status;
        }
        
        return $filters;
    }

}
