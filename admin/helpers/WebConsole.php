<?

namespace admin\helpers;

use Yii;
use yii\helpers\FileHelper;

class WebConsole {

    private static $_console;
    public static $logFile;
    public static $logFileHandler;

    public static function console($controllerNamespace = '') {
        if (!self::$_console) {
            $logsPath = Yii::getAlias('@runtime/logs');
            if (!FileHelper::createDirectory($logsPath, 0777)) {
                throw new \yii\web\ServerErrorHttpException('Cannot create `' . $logsPath . '`. Please check write permissions.');
            }

            self::$logFile = $logsPath . DIRECTORY_SEPARATOR . 'console.log';
            self::$logFileHandler = fopen(self::$logFile, 'w+');

            defined('STDIN') or define('STDIN', self::$logFileHandler);
            defined('STDOUT') or define('STDOUT', self::$logFileHandler);

            $oldApp = Yii::$app;

            $consoleConfigFile = Yii::getAlias('@admin/config/console.php');

            if (!file_exists($consoleConfigFile) || !is_array(($consoleConfig = require($consoleConfigFile)))) {
                throw new \yii\web\ServerErrorHttpException('Cannot find `' . Yii::getAlias('@admin/config/console.php') . '`. Please create and configure console config.');
            }

            if ($controllerNamespace) {
                $consoleConfig['controllerNamespace'] = $controllerNamespace;
            }
            self::$_console = new \yii\console\Application($consoleConfig);

            Yii::$app = $oldApp;
        } else {
            ftruncate(self::$logFileHandler, 0);
        }

        return self::$_console;
    }

    public static function migrate($moduleName) {
        ob_start();


        $erer = Yii::getAlias('@' . APP_NAME . '/migrations/');
        $erer1 = Yii::getAlias('@app/migrations/');
        $eeqweqwe1 = Yii::getAlias('@admin/migrations/');
        if ($moduleName == 'admin') {
            self::console()->runAction('migrate', ['migrationPath' => '@admin/migrations/', 'migrationTable' => 'admin_migration', 'interactive' => false]);
        } elseif ($moduleName == 'app') {
            self::console()->runAction('migrate', ['migrationPath' => '@app/migrations/', 'migrationTable' => 'admin_migration_app', 'interactive' => false]);
        } else {
            self::console()->runAction('migrate', ['migrationPath' => '@admin/modules/' . $moduleName . '/migrations/', 'migrationTable' => 'admin_migration_' . $moduleName, 'interactive' => false]);
        }
        $result = file_get_contents(self::$logFile) . "\n" . ob_get_clean();

        Yii::$app->cache->flush();

        return $result;
    }

    public static function migrateDown($moduleName) {
        ob_start();

        if ($moduleName == 'admin') {
            self::console()->runAction('migrate/down', ['migrationPath' => '@admin/migrations/', 'migrationTable' => 'admin_migration', 'interactive' => false]);
        } elseif ($moduleName == 'app') {
            self::console()->runAction('migrate/down', ['migrationPath' => '@app/migrations/', 'migrationTable' => 'admin_migration_app', 'interactive' => false]);
        } else {
            self::console()->runAction('migrate/down', ['migrationPath' => '@admin/modules/' . $moduleName . '/migrations/', 'migrationTable' => 'admin_migration_' . $moduleName, 'interactive' => false]);
        }
        $result = file_get_contents(self::$logFile) . "\n" . ob_get_clean();

        Yii::$app->cache->flush();

        return $result;
    }

    public static function MessageExtractAdmin() {
        ob_start();
        self::console()->runAction('message/extract', ['@admin/messages/config.php', 'interactive' => false]);
        $result = file_get_contents(self::$logFile) . "\n" . ob_get_clean();
        foreach (Yii::$app->getModule('admin')->activeModules as $name => $module) {
            $message_config = '@admin/modules/' . $name . '/messages/config.php';
            if (file_exists(Yii::getAlias($message_config))) {
                ob_start();
                self::console()->runAction('message/extract', [$message_config, 'interactive' => false]);
                $result .= file_get_contents(self::$logFile) . "\n" . ob_get_clean();
            }
        }

        Yii::$app->cache->flush();

        return $result;
    }

    public static function MessageExtractApp() {
        ob_start();

        self::console()->runAction('message/extract', ['@app/messages/config.php', 'interactive' => false]);

        $result = file_get_contents(self::$logFile) . "\n" . ob_get_clean();

        Yii::$app->cache->flush();

        return $result;
    }

    public static function rbacMigrate() {
        ob_start();

        self::console()->runAction('migrate', ['migrationPath' => '@yii/rbac/migrations', 'migrationTable' => 'admin_migration', 'interactive' => false]);

        $result = file_get_contents(self::$logFile) . "\n" . ob_get_clean();

        Yii::$app->cache->flush();

        return $result;
    }

    public static function rbacMigrateDown() {
        ob_start();

        self::console()->runAction('migrate/down', ['migrationPath' => '@yii/rbac/migrations', 'migrationTable' => 'admin_migration', 'interactive' => false]);

        $result = file_get_contents(self::$logFile) . "\n" . ob_get_clean();

        Yii::$app->cache->flush();

        return $result;
    }

    public static function rbacInit($user_id) {
        ob_start();

        self::console()->runAction('rbac/init', ['user_id' => $user_id, 'interactive' => false]);

        $result = file_get_contents(self::$logFile) . "\n" . ob_get_clean();

        Yii::$app->cache->flush();

        return $result;
    }

    public static function dumpCreate($isArchive, $schemaOnly) {
        ob_start();

        self::console()->runAction('dump/create', ['isArchive' => $isArchive, 'schemaOnly' => $schemaOnly, 'interactive' => false]);

        $result = file_get_contents(self::$logFile) . "\n" . ob_get_clean();

        Yii::$app->cache->flush();

        return $result;
    }

    public static function dumpRestore($initData, $demoData, $restoreScript, $dumpId) {
        ob_start();

        self::console()->runAction('dump/restore', ['initData' => $initData, 'demoData' => $demoData, 'restoreScript' => $restoreScript, 'dumpId' => $dumpId, 'interactive' => false]);

        $result = file_get_contents(self::$logFile) . "\n" . ob_get_clean();

        Yii::$app->cache->flush();

        return $result;
    }

}
