<?


//Select application
defined('APP_NAME') or define('APP_NAME', 'app_demo');
//defined('APP_NAME') or define('APP_NAME', 'app_1');
//defined('APP_NAME') or define('APP_NAME', 'app_2');
//...
//defined('APP_NAME') or define('APP_NAME', 'app_n');

defined('DEMO') or define('DEMO', false);
defined('APP_CONSOLE') or define('APP_CONSOLE', false);
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');


defined('ADMIN_PATH') or define('ADMIN_PATH', dirname(__DIR__) . '/admin');
defined('BASE_PATH') or define('BASE_PATH', dirname(__DIR__));
defined('APP_PATH') or define('APP_PATH', dirname(__DIR__) .'/'.APP_NAME);


require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');


$config = require(__DIR__ . '/../admin/config/admin.php');

$config = array_replace_recursive($config,require(__DIR__ . '/../' . APP_NAME . '/config/web.php'));
if (YII_ENV_PROD) {
    $config = array_replace_recursive($config, require(__DIR__ . '/../' . APP_NAME . '/config/db.php'));
} else {
    $config = array_replace_recursive($config, require(__DIR__ . '/../' . APP_NAME . '/config/db_dev.php'));
}

$application = new yii\web\Application($config);

require(__DIR__ . '/../' . APP_NAME . '/params.php');
require(__DIR__ . '/../' . APP_NAME . '/events.php');

$application->run();
