<?

namespace admin\console;

class Controller extends \yii\console\Controller {

    const gray = 90;
    const red = 91;
    const haki = 92;
    const yellow = 93;
    const blue = 94;
    const pink = 95;
    const turquoise = 96;

    public function stderr($string) {
        return parent::stderr(mb_convert_encoding($string . "\n", 'cp866', 'UTF-8'), self::red);
    }

    public function stdout($string, $color_format = 0) {
        return parent::stdout(mb_convert_encoding($string . "\n", 'cp866', 'UTF-8'), $color_format);
    }

}
