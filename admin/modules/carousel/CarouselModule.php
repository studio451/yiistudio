<?
namespace admin\modules\carousel;

class CarouselModule extends \admin\components\Module
{
    public $settings = [
        'enableTitle' => true,
        'enableText' => true,
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Carousel',
            'ru' => 'Карусель',
        ],
        'icon' => 'image',
        'order_num' => 201,
    ];
}