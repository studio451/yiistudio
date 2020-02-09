<?

namespace admin\helpers;

use Yii;

class Sort {

    public static function str_sort_by_alphabet($array) {
        
        $result = [];
        usort($array, function($val1, $val2) {
            return strcmp(
                    mb_strtolower($val1['label']),
                    mb_strtolower($val2['label'])
            );
        });

        foreach ($array as $element) {
            $result[mb_substr(mb_strtolower($element['label']), 0, 1)][] = $element;
        }
        return $result;
    }

}
