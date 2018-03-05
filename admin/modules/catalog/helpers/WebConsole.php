<?

namespace admin\modules\catalog\helpers;

use Yii;

class WebConsole extends \admin\helpers\WebConsole {

    public static function catalogRecreateGroups() {
        ob_start();

        self::console('admin\modules\catalog\commands')->runAction('catalog/recreate-groups', []);

        $result = file_get_contents(self::$logFile) . "\n" . ob_get_clean();

        Yii::$app->cache->flush();

        return $result;
    }
    
    public static function catalogResaveItems() {
        ob_start();

        self::console('admin\modules\catalog\commands')->runAction('catalog/resave-items', []);

        $result = file_get_contents(self::$logFile) . "\n" . ob_get_clean();

        Yii::$app->cache->flush();

        return $result;
    }
}
