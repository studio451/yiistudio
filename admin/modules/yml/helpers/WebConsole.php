<?

namespace admin\modules\yml\helpers;

use Yii;

class WebConsole extends \admin\helpers\WebConsole {

    public static function ymlImport($id) {
        ob_start();

        self::console('admin\modules\yml\commands')->runAction('yml/import', ['id' => $id, 'interactive' => false]);

        $result = file_get_contents(self::$logFile) . "\n" . ob_get_clean();

        Yii::$app->cache->flush();

        return $result;
    }
    
    public static function ymlFullImport($id) {
        ob_start();

        self::console('admin\modules\yml\commands')->runAction('yml/full-import', ['id' => $id, 'interactive' => false]);

        $result = file_get_contents(self::$logFile) . "\n" . ob_get_clean();

        Yii::$app->cache->flush();

        return $result;
    }
    
    public static function ymlExport($id) {
        ob_start();

        self::console('admin\modules\yml\commands')->runAction('yml/export', ['id' => $id, 'interactive' => false]);

        $result = file_get_contents(self::$logFile) . "\n" . ob_get_clean();

        Yii::$app->cache->flush();

        return $result;
    }

    public static function ymlLoadItemsFromExcelFile($id, $fileName) {
        ob_start();

        self::console('admin\modules\yml\commands')->runAction('yml/load-items-from-excel-file', ['id' => $id, 'file_name' => $fileName, 'interactive' => false]);

        $result = file_get_contents(self::$logFile) . "\n" . ob_get_clean();

        Yii::$app->cache->flush();

        return $result;
    }

    public static function ymlAddItemsFromExcelFile($fileName) {
        ob_start();

        self::console('admin\modules\yml\commands')->runAction('yml/add-items-from-excel-file', ['file_name' => $fileName, 'interactive' => false]);

        $result = file_get_contents(self::$logFile) . "\n" . ob_get_clean();

        Yii::$app->cache->flush();

        return $result;
    }
    
    public static function ymlUpdateItemsFromExcelFile($fileName) {
        ob_start();

        self::console('admin\modules\yml\commands')->runAction('yml/update-items-from-excel-file', ['file_name' => $fileName, 'interactive' => false]);

        $result = file_get_contents(self::$logFile) . "\n" . ob_get_clean();

        Yii::$app->cache->flush();

        return $result;
    }

    public static function ymlLoadCategoriesFromExcelFile($fileName) {
        ob_start();

        self::console('admin\modules\yml\commands')->runAction('yml/load-categories-from-excel-file', ['file_name' => $fileName, 'interactive' => false]);

        $result = file_get_contents(self::$logFile) . "\n" . ob_get_clean();

        Yii::$app->cache->flush();

        return $result;
    }

    public static function ymlLoadNewsFromExcelFile($fileName) {
        ob_start();

        self::console('admin\modules\yml\commands')->runAction('yml/load-news-from-excel-file', ['file_name' => $fileName, 'interactive' => false]);

        $result = file_get_contents(self::$logFile) . "\n" . ob_get_clean();

        Yii::$app->cache->flush();

        return $result;
    }

    public static function ymlLoadUsersFromExcelFile($fileName) {
        ob_start();

        self::console('admin\modules\yml\commands')->runAction('yml/load-users-from-excel-file', ['file_name' => $fileName, 'interactive' => false]);

        $result = file_get_contents(self::$logFile) . "\n" . ob_get_clean();

        Yii::$app->cache->flush();

        return $result;
    }

}
