Входной скрипт
===============

Входной скрипт является точкой входа в приложение. 

Обычно доступен по адресу: `http://hostname/index.php`.
Входной скрипт Yii Studio имеет некоторые отличия от стандарного входного скрипта Yii 2.

Ниже представлен код входного скрипта для демо-приложения.

```php
<?
//Yii Studio 
//https://yiistudio.ru

//ВНИМАНИЕ!
//Для установки измените на false
//После установки измените на true
defined('INSTALLED') or define('INSTALLED', true);

//Выбор приложения при многосайтовости
//задаем переменной APP_NAME название папки с кодом приложения 
defined('APP_NAME') or define('APP_NAME', 'app_demo');
//defined('APP_NAME') or define('APP_NAME', 'app_1');
//defined('APP_NAME') or define('APP_NAME', 'app_2');
//...
//defined('APP_NAME') or define('APP_NAME', 'app_n');

defined('APP_CONSOLE') or define('APP_CONSOLE', false);//Если приложение консольное
defined('YII_DEBUG') or define('YII_DEBUG', true);//Включить/выключить отладку
defined('YII_ENV') or define('YII_ENV', 'dev');//Переключение окружения (разработка - 'dev', прод - 'prod')

defined('ADMIN_PATH') or define('ADMIN_PATH', dirname(__DIR__) . '/admin');
defined('BASE_PATH') or define('BASE_PATH', dirname(__DIR__));
defined('APP_PATH') or define('APP_PATH', dirname(__DIR__) .'/'.APP_NAME);

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../admin/config/admin.php'); //Подключение общего для всех приложений конфига

$config = array_replace_recursive($config,require(__DIR__ . '/../' .  APP_NAME . '/config/web.php'));//Подключение конфига для приложения
if (YII_ENV_PROD) {
    $config = array_replace_recursive($config, require(__DIR__ . '/../' . APP_NAME . '/config/db.php'));//Параметры подключения к БД прод
} else {
    $config = array_replace_recursive($config, require(__DIR__ . '/../' . APP_NAME . '/config/db_dev.php'));//Параметры подключения к БД разработка
}

//Создание приложения
$application = new yii\web\Application($config);

//Доп. параметры
require(__DIR__ . '/../' . APP_NAME . '/params.php');
//Доп. события
require(__DIR__ . '/../' . APP_NAME . '/events.php');

//Запуск приложения
$application->run();
```


Входной скрипт выполняет следующие действия:

* Объявляет глобальные константы. Константа APP_NAME - названии папки с кодом приложения (используется при многосайтовости). Константа APP_CONSOLE опрделяется, если приложение консольное.
* Регистрирует загрузчик классов [Composer](https://getcomposer.org/doc/01-basic-usage.md#autoloading).
* Подключает класс Yii.
* Загружает конфиги. Общий для всех приложений конфиг. Конфиг для конкретного приложения (переопределяет настройки из общего конфига). Конфиг с параметрами подключения к БД в зависимоти от окружения.
* Создает объект приложения и передет ему настройки.
* Подключяет доп. параметры для конкретного приложения `/params.php`.
* Подключяет события для конкретного приложения `/events.php`.
* Запускает объект приложения.
