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
            1 => Yii::t('app/month', 'Январь'),
            2 => Yii::t('app/month', 'Февраль'),
            3 => Yii::t('app/month', 'Март'),
            4 => Yii::t('app/month', 'Апрель'),
            5 => Yii::t('app/month', 'Май'),
            6 => Yii::t('app/month', 'Июнь'),
            7 => Yii::t('app/month', 'Июль'),
            8 => Yii::t('app/month', 'Август'),
            9 => Yii::t('app/month', 'Сентябрь'),
            10 => Yii::t('app/month', 'Октябрь'),
            11 => Yii::t('app/month', 'Ноябрь'),
            12 => Yii::t('app/month', 'Декабрь'),
        ];
    }

    public static function byNumber($number) {
        $month = [
            1 => Yii::t('app/month', 'Январь'),
            2 => Yii::t('app/month', 'Февраль'),
            3 => Yii::t('app/month', 'Март'),
            4 => Yii::t('app/month', 'Апрель'),
            5 => Yii::t('app/month', 'Май'),
            6 => Yii::t('app/month', 'Июнь'),
            7 => Yii::t('app/month', 'Июль'),
            8 => Yii::t('app/month', 'Август'),
            9 => Yii::t('app/month', 'Сентябрь'),
            10 => Yii::t('app/month', 'Октябрь'),
            11 => Yii::t('app/month', 'Ноябрь'),
            12 => Yii::t('app/month', 'Декабрь'),
        ];

        return $month[$number];
    }

}
