<?

namespace admin\modules\sitemap\helpers;

use Yii;

class WebConsole extends \admin\helpers\WebConsole {

    public static function sitemapGenerate() {
        ob_start();

        self::console('admin\modules\sitemap\commands')->runAction('sitemap/generate', ['interactive' => false]);

        $result = file_get_contents(self::$logFile) . "\n" . ob_get_clean();

        Yii::$app->cache->flush();

        return $result;
    }
}
