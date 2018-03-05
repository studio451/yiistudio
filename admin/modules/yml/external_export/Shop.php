<?

namespace admin\modules\yml\external_export;

use Yii;
use admin\modules\yml\models\Item;
use yii\helpers\Url;

class Shop extends \admin\modules\yml\models\Export {

    public function getItem($_item) {

        $item = new Item();
        // ID
        $item->id = $_item->id;
        // Тип элемента
        $item->type = $_item->type;
        // Бренд
        $item->brand = $_item->brand->title;
        // Модель
        $item->name = $_item->name;
        // Артикул
        $item->article = $_item->article;
        // Закупочная цена
        $item->base_price = $_item->base_price;
        // Цена со скидкой
        $item->price = $_item->discount ? round($_item->price * (1 - $_item->discount / 100)) : $_item->price;
        // Реальная цена
        $item->old_price = $_item->price;
        // Активность    
        $item->status = $_item->status;
        // Описание
        $item->description = $_item->description;
        //Дата
        $item->time = Yii::$app->formatter->asDate($_item->time);
        
        // Фото     
        $count_photos = 0;
        foreach ($_item->photos as $photo) {
            $item->photos[] =  Url::to([$photo->image], true);
            $count_photos++;
        }


        $item->category = $_item->category;
        $item->url = Url::to(['/catalog/item', 'category' => $_item->category->slug, 'slug' => $_item->slug], true);

        foreach ($_item->category->fields as $field) {
            if ($field->name == 'marketcategory' && $field->type == 'data') {
                $item->marketcategory = $field->options;
                break;
            }
        }


        // Кол-во картинок
        $item->count_photos = $count_photos;
        // Цвет для фильтра
        $item->data['color'] = $_item->data->color;
        // Страна
        $item->data['country'] = $_item->data->country;
        // Вес
        $item->data['weight'] = $_item->data->weight;
        // Размеры
        $item->data['dimensions'] = $_item->data->dimensions;
        // Материал
        $item->data['material'] = $_item->data->material;
        // Объем
        $item->data['volume'] = $_item->data->volume;


        return $item;
    }

}
