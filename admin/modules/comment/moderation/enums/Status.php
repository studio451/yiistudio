<?

namespace admin\modules\comment\moderation\enums;

use admin\helpers\BaseEnum;

/**
 * Class Status
 *
 */
class Status extends BaseEnum
{
    const PENDING = 0;
    const APPROVED = 1;
    const REJECTED = 2;
    const POSTPONED = 3;

    /**
     * @var array
     */
    public static $list = [
        self::PENDING => 'В ожидании',
        self::APPROVED => 'Одобрено',
        self::REJECTED => 'Отклонено',
        self::POSTPONED => 'Опубликовано',
    ];
}
