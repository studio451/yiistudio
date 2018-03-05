<?
namespace admin\modules\file;

class FileModule extends \admin\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'Files',
            'ru' => 'Файлы',
        ],
        'icon' => 'files-o',
        'order_num' => 205,
    ];
}