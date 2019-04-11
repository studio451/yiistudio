<?

namespace admin\modules\comment;

use Yii;

/**
 * Class Module
 *
 * @package admin\modules\comment
 */
class CommentModule extends \admin\base\Module
{
    public $settings = [
        'commentModelClass' => 'admin\modules\comment\models\Comment',
        'enableInlineEdit' => false,
        'ratingModelClass' => 'admin\modules\comment\models\Rating',
        'userIdentityClass' => 'admin\models\User'
    ];
    public static $installConfig = [
        'title' => [
            'en' => 'Comments and ratings',
            'ru' => 'Комментарии и оценки',
        ],
        'icon' => 'comments',
        'order_num' => 100,
    ];
    
}
