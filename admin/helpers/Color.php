<?

namespace admin\helpers;

use Yii;
use yii\base\Model;

class Color extends Model {

    public static function ymlColors() {
        return $array = [
            'бежевый',
            'белый',
            'бирюзовый',
            'бордовый',
            'голубой',
            'желтый',
            'зеленый',
            'золотистый',
            'коричневый',
            'красный',
            'оливковый',
            'оранжевый',
            'розовый',
            'рыжий',
            'салатовый',
            'серебристый',
            'серый',
            'синий',
            'сиреневый',
            'фиолетовый',
            'хаки',
            'черный',
        ];
    }
    
    public static function ymlColorsOptions($firstOption = '') {
        $options = [];
        if ($firstOption) {
            $options[''] = $firstOption;
        }

        foreach (self::ymlColors() as $color) {
            $options[$color] = $color;
        }
        return $options;
    }

    public static function ymlColorsStringCommaSeparated() {
        return 'бежевый,белый,бирюзовый,бордовый,голубой,желтый,зеленый,золотистый,коричневый,красный,оливковый,оранжевый,розовый,рыжий,салатовый,серебристый,серый,синий,сиреневый,фиолетовый,хаки,черный';
    }

    public static function ymlColor($color) {
        if (isset(self::ymlColors()[$color])) {
            return $color;
        } else {
            return ''; 
        }
    }

}

?>