<?

namespace admin\modules\shopcart\helpers;

use Yii;

class WebConsole extends \admin\helpers\WebConsole {

    public static function catalogClearUsersNoOrder() {
        ob_start();

        self::console('admin\modules\shopcart\commands')->runAction('shopcart/clear-users-no-order', []);

        $result = file_get_contents(self::$logFile) . "\n" . ob_get_clean();

        Yii::$app->cache->flush();

        return $result;
    }   
    
}
