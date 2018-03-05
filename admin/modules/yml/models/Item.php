<?

namespace admin\modules\yml\models;

use Yii;

class Item extends \yii\base\Model {

    const STATUS_OFF = 0;
    const STATUS_ON = 1;
    //Обязательные поля
    public $id;
    public $type;
    public $brand;
    public $name;
    public $article;
    public $category;
    public $base_price;
    public $price;
    public $old_price;
    public $status;
    public $time;
    public $description;
    public $photos;
    public $count_photos;
    public $available= 'true';
    public $url;
    public $marketcategory;
    //Доп.поля
    public $data = [];

}
