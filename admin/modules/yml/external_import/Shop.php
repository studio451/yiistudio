<?

namespace admin\modules\yml\external_import;

use admin\modules\yml\models\Item;

//Пример файла импорта из внешних источников
class Shop extends \admin\modules\yml\models\Import {

    public function getOffer($offer) {

        $item = new Item();

        if ((string) $offer->count == "0") {
            return null;
        }
        if ($offer->brand != "Shop") {
            return null;
        }
        // Фото  
        $count_photos = 0;
        foreach ($offer->picture as $picture) {
            if ($this->max_number_of_uploaded_photos > 0 && $count_photos >= $this->max_number_of_uploaded_photos) {
                break;
            }
            $count_photos++;
            $item->photos[] = (string) $picture;
        }
        if ($count_photos == 0) {
            //Нет картинок
            return null;
        }
        // Кол-во картинок
        $item->count_photos = $count_photos;



        foreach ($offer->param as $param) {
            switch ((string) $param['name']) {
                case 'Цвет':
                    $color = mb_strtolower((string) $param);
                    break;
                case 'Материал':
                    $material = mb_strtolower((string) $param);
                    break;
                case 'Размер (ШхВхГ)':
                    $dimensions = mb_strtolower((string) $param);
                    $dimensions = str_replace(" x ", "х", $dimensions);  
                    $dimensions = str_replace(" х ", "х", $dimensions);
                    break;
                case 'Объем':
                    $volume = mb_strtolower(iconv('utf-8', 'utf-8', (string) $param));
                    break;
                case 'Вес':
                    $weight = mb_strtolower((string) $param);
                    break;
            }
        }

        $item->id = (int) $offer['id'];

        $item->type = mb_strtolower(trim(str_replace((string) $offer->article, '', (string) $offer->name)));
        

        // Бренд
        $item->brand = (string) $offer->brand;
        // Модель
        $item->name = str_replace('"', '', (string) $offer->article);
        // Артикул
        $item->article = mb_strtolower(trim((string) $offer->color_name));
        // Закупочная цена
        $item->base_price = (int) \admin\helpers\Math::round($offer->price_opt / 100 * 85);
        // Цена
        $item->price = (int) \admin\helpers\Math::round($offer->price / 100 * $this->mult);
        // Активность   
        $item->status = 1;
        // Описание
        $item->description = (string) $offer->description;
        // Цвет для фильтра
        $item->data['color'] = $color;
        // Страна
        $item->data['country'] = "Россия";
        // Вес
        $item->data['weight'] = $weight;
        // Размеры
        $item->data['dimensions'] = $dimensions;
        // Материал
        $item->data['material'] = $material;
        // Объем
        $item->data['volume'] = $volume;

        return $item;
    }

}
