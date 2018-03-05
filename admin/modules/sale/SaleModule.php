<?
namespace admin\modules\sale;

class SaleModule extends \admin\components\Module
{
    public $settings = [
        'enableThumb' => true,
        'enableTags' => true
    ];
    
    public static $installConfig = [
        'title' => [
            'en' => 'Sale',
            'ru' => 'Акции',
        ],
        'icon' => 'tags',
        'order_num' =>209,
    ];
}