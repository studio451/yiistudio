<?

namespace admin\helpers;

use Yii;

class Month {

    public static function years() {

        $y = date("Y");

        return [
            $y => $y,
            $y - 1 => $y - 1,
            $y - 2 => $y - 2,
            $y - 3 => $y - 3,
            $y - 4 => $y - 4,
            $y - 5 => $y - 5,
            $y - 6 => $y - 6,
            $y - 7 => $y - 7,
            $y - 8 => $y - 8,
            $y - 9 => $y - 9,
            $y - 10 => $y - 10,
        ];
    }

    public static function listAll() {
        return [
            1 => Yii::t('admin', 'Январь'),
            2 => Yii::t('admin', 'Февраль'),
            3 => Yii::t('admin', 'Март'),
            4 => Yii::t('admin', 'Апрель'),
            5 => Yii::t('admin', 'Май'),
            6 => Yii::t('admin', 'Июнь'),
            7 => Yii::t('admin', 'Июль'),
            8 => Yii::t('admin', 'Август'),
            9 => Yii::t('admin', 'Сентябрь'),
            10 => Yii::t('admin', 'Октябрь'),
            11 => Yii::t('admin', 'Ноябрь'),
            12 => Yii::t('admin', 'Декабрь'),
        ];
    }

    public static function byNumber($number) {
        $month = [
            1 => Yii::t('admin', 'Январь'),
            2 => Yii::t('admin', 'Февраль'),
            3 => Yii::t('admin', 'Март'),
            4 => Yii::t('admin', 'Апрель'),
            5 => Yii::t('admin', 'Май'),
            6 => Yii::t('admin', 'Июнь'),
            7 => Yii::t('admin', 'Июль'),
            8 => Yii::t('admin', 'Август'),
            9 => Yii::t('admin', 'Сентябрь'),
            10 => Yii::t('admin', 'Октябрь'),
            11 => Yii::t('admin', 'Ноябрь'),
            12 => Yii::t('admin', 'Декабрь'),
        ];

        return $month[$number];
    }

}
