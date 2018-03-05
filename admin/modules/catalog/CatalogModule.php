<?

namespace admin\modules\catalog;

class CatalogModule extends \admin\components\Module {

    public $settings = [
        'categoryThumb' => true,
        'itemsInFolder' => true,
        'itemThumb' => true,
        'itemPhotos' => true,
        'itemDescription' => true,
        'itemSale' => true,
        'enableFast' => true,
        'enableShare' => true,
        'enableComment' => true,
        'enableLastViewed' => true,
        'enableRating' => true,
        'viewFileCatalogIndex' => '@admin/modules/catalog/views/api/catalog/index',
        'viewFileCatalogItem' => '@admin/modules/catalog/views/api/catalog/item',
        'viewFileCatalogSearch' => '@admin/modules/catalog/views/api/catalog/search',
        'generateComplexTitle' => true,
        '__submenu_module' => [
                ['id' => 'a', 'url' => '/admin/catalog/a', 'label' => 'Категории'],
                ['id' => 'brand', 'url' => '/admin/catalog/brand', 'label' => 'Бренды'],
        ]
    ];
    public static $installConfig = [
        'title' => [
            'en' => 'Catalog',
            'ru' => 'Каталог',
        ],
        'icon' => 'list-alt',
        'order_num' => 100,
    ];

}
