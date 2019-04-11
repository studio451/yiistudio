<?
namespace admin\modules\sale;

class SaleModule extends \admin\base\Module
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