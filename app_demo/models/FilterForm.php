<?

namespace app_demo\models;

use Yii;
use yii\base\Model;

class FilterForm extends Model {

    public $brand_id = [];
    public $category_id = [];
    public $price_from;
    public $price_to;
    public $color;

    public function formName() {
        return '';
    }

    public function rules() {
        return [
            [['price_from', 'price_to'], 'number', 'min' => 0],
            [['brand_id','category_id'], 'safe'],
            ['color', 'string']
        ];
    }

    public function attributeLabels() {
        return [
            'brand_id' => Yii::t('app', 'Бренд'),
            'category_id' => Yii::t('app', 'Категория'),
            'price_from' => Yii::t('app', 'Мин. цена'),
            'price_to' => Yii::t('app', 'Макс. цена'),
            'color' => Yii::t('app', 'Основной цвет'),
        ];
    }

    public function parse() {
        $filters = [];

        if ($this->category_id) {
            $filters['category_id'] = $this->category_id;
        }
        
        if ($this->brand_id) {
            $filters['brand_id'] = $this->brand_id;
        }

        if ($this->price_from > 0 || $this->price_to > 0) {
            $filters['price'] = [$this->price_from, $this->price_to];
        }

        if ($this->color) {
            $filters['color'] = $this->color;
        }
        
        return $filters;
    }

}
