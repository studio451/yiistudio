<?
namespace admin\modules\faq;

use Yii;

class FaqModule extends \admin\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'FAQ',
            'ru' => 'Вопросы и ответы',
        ],
        'icon' => 'question',
        'order_num' => 203,
    ];
}