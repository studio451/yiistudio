<?
namespace admin\modules\page;

use Yii;

class PageModule extends \admin\components\Module
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