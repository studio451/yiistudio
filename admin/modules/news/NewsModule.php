<?
namespace admin\modules\news;

class NewsModule extends \admin\components\Module
{
    public $settings = [
        'enableThumb' => true,
        'enableShort' => true,
        'shortMaxLength' => 256,
        'enableTags' => true
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'News',
            'ru' => 'Новости',
        ],
        'icon' => 'bullhorn',
        'order_num' => 208,
    ];
}