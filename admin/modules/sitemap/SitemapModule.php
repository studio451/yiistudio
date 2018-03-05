<?
namespace admin\modules\sitemap;

use Yii;

class SitemapModule extends \admin\components\Module
{      
    public static $installConfig = [
        'title' => [
            'en' => 'Sitemap',
            'ru' => 'Карта сайта',
        ],
        'icon' => 'sitemap',
        'order_num' => 104,
    ];
    
    public $settings = [
        'perPage' => 2000,   
        'mainFile'=>'sitemap.xml',
        'pageDir' => 'sitemap_files',
    ];
   
}