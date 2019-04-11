<?
namespace admin\modules\page;

use Yii;

class PageModule extends \admin\base\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'Pages',
            'ru' => 'Страницы',
        ],
        'icon' => 'file',
        'order_num' => 209,
    ];
}