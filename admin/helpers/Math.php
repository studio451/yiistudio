<?
namespace admin\helpers;

use Yii;
use yii\base\Model;

class Math extends Model {

    static function round($arg, $base = 10) {
        //arg - округляемое число, $base - "округлитель"
        $ost = $arg % $base; //вычисляем остаток от деления
        $chast = floor($arg / $base); //находим количество целых округлителей в аргументе
        if ($ost >= $base / 2) {
            $rez = ($chast + 1) * $base; //выбираем направление округления
        } else {
            $rez = $chast * $base;
        }
        return $rez;
    }

}