<?

namespace admin\base;

use Yii;

/**
 * Base active record class for admin models
 * @package admin\base
 */
class ActiveRecord extends \yii\db\ActiveRecord {

    /** @var string  */
    public static $SLUG_PATTERN = '/^[_0-9a-z-]{0,256}$/';

    /**
     * Get active query
     * @return ActiveQuery
     */
    public static function find() {
        return new ActiveQuery(get_called_class());
    }

    /**
     * Formats all model errors into a single string
     * @return string
     */
    public function formatErrors() {
        $result = '';
        foreach ($this->getErrors() as $attribute => $errors) {
            $result .= implode(" ", $errors) . " ";
        }
        return $result;
    }

    public static function listAll($keyField = 'id', $valueField = 'title', $asArray = true, $where = [], $firstOption = '') {
        $query = static::find();

        if ($asArray) {
            $query->select([$keyField, $valueField])->orderBy([$valueField => SORT_ASC])->asArray();
        }
        if ($firstOption) {
            return array_replace([0 => $firstOption], yii\helpers\ArrayHelper::map($query->where($where)->all(), $keyField, $valueField));
        } else {
            return yii\helpers\ArrayHelper::map($query->where($where)->all(), $keyField, $valueField);
        }
    }

}
