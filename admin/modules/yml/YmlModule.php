<?

namespace admin\modules\yml;

use Yii;

class YmlModule extends \admin\components\Module {

    public $settings = [
        'additionalFields' => 'color:Цвет:15::cdf9fb,country:Страна:10::cdf9fb,weight:Вес:8::cdf9fb,dimensions:Размеры (ШхВхГ):15::cdf9fb,material:Материал:11::cdf9fb,volume:Объем:7::cdf9fb',
        'exportYandexMarketId' => 0,
        '__submenu_module' => [
            ['id' => 'import', 'url' => '/admin/yml/import', 'label' => 'Импорт'],
            ['id' => 'export', 'url' => '/admin/yml/export', 'label' => 'Экспорт'],
            ['id' => 'excel', 'url' => '/admin/yml/excel', 'label' => 'Excel'],
        ]
    ];
    public static $installConfig = [
        'title' => [
            'en' => 'Export/import',
            'ru' => 'Экспорт/импорт',
        ],
        'icon' => 'upload',
        'order_num' => 104,
    ];

    //Обязательные поля  для экспорта и импорта
    public static function getRequiredFields() {

        return [
            'id:ID:8::ffd5b4',
            'type:Наименование:25:text',
            'brand:Бренд:16:text',
            'name:Модель:16:text',
            'article:Артикул:16:text',
            'time:Дата:10',
            [
                'attribute' => 'photos',
                'header' => 'Фото',
                'width' => 4,
                'value' => function($model) {
                    return json_encode($model->photos);
                },
                'color' => 'ededed',     
            ],
            'count_photos:Кол-во фото:4::ededed',
            'base_price:Закупочная цена:10::fffff4',
            'price:Цена:10::ffffc4',
            'status:Статус:4::eef9fb',            
            'description:Описание:10::cdf9fb',
            
        ];
    }

    //Дополнительные поля  для экспорта и импорта
    public static function getAdditionalFields($to_array = false) {
        $additionalFields = Yii::$app->getModule('admin')->activeModules['yml']->settings['additionalFields'];
        $array = explode(',', $additionalFields);
        $_columns = [];
        if ($to_array) {
            foreach ($array as $value) {
                if (is_string($value)) {
                    $value_log = explode(':', $value);
                    $key = $value_log[0];
                    $_columns[$key] = ['attribute' => $value_log[0]];

                    if (isset($value_log[1]) && $value_log[1] !== null) {
                        $_columns[$key]['header'] = $value_log[1];
                    }
                    if (isset($value_log[2]) && $value_log[2] !== null) {
                        $_columns[$key]['width'] = $value_log[2];
                    }
                    if (isset($value_log[3]) && $value_log[3] !== null) {
                        $_columns[$key]['format'] = $value_log[3];
                    }
                    if (isset($value_log[4]) && $value_log[4] !== null) {
                        $_columns[$key]['color'] = $value_log[4];
                    }
                }
            }
            return $_columns;
        } else {
            return explode(',', $additionalFields);
        }
    }

    public static function getFields() {
        return array_merge(self::getRequiredFields(), self::getAdditionalFields());
    }

}
