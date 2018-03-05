<?

namespace admin\helpers;

/**
 * Class BooleanEnum
 *
 */
class BooleanEnum extends BaseEnum
{
    const YES = 1;
    const NO = 0;

    public static $list = [
        self::YES => 'Yes',
        self::NO => 'No',
    ];
}
