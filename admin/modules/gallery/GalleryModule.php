<?
namespace admin\modules\gallery;

class GalleryModule extends \admin\components\Module
{
    public $settings = [
        'categoryThumb' => true,
        'itemsInFolder' => false,
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Photo Gallery',
            'ru' => 'Фотогалерея',
        ],
        'icon' => 'camera',
        'order_num' => 206,
    ];
}