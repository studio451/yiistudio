<?
namespace admin\base;

use creocoder\nestedsets\NestedSetsQueryBehavior;

/**
 * Extended active query class for models
 * @package admin\base
 */
class ActiveQueryNS extends ActiveQuery
{
    public function behaviors()
    {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }

    public function sort()
    {
        $this->orderBy('order_num DESC, lft ASC');
        return $this;
    }
}