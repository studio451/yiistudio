Приложение
==========

Структура приложения Yii Studio
---------------------

Приложение Yii Studio имеет несколько отличную от классического Yii2-приложения структуру.
Ниже приведен список основных директорий и файлов демо-приложения:

```
admin/                      каталог системы Yii Studio (Панель управления, модули и т.д.)
app_demo/                   каталог демо-приложения (структура вашего приложения будет аналогичной)
    assets/                 содержит классы для подключения ресурсов приложения 
    config/                 конфигурационные файлы
        db.php              конфигурация для подключения к БД (prod) YII_ENV = 'prod'
        db_dev.php          конфигурация для подключения к БД (dev)  YII_ENV = 'dev'
        web.php             конфигурация приложения (заменяет, при совпадении, настройки из файла admin/config/admin.php)    
    controllers/            контроллеры
    demo_data/              демо-данные
    dumps/                  дампы приложения
    mail/                   содержит шаблон и виды для почтовых отправлений приложения
    media/                  содержит css, js, png, ... подключаемые приложением
        css/
            main.css        файл css подключаемый ко всему приложению
        img/                картинки
        js/
            catalog/
                item/
                    main.js файл js автоматически подключаемый для действия item контроллера catalog
            main.js         файл js автоматически подключаемый ко всему приложению
    messages/               файлы переводов
    models/                 модели
    views/                  виды приложения    
public_html/                корневая директория Web приложения. Содержит файлы, доступные через Web
    assets/                 опубликованные ресурсы, используемые приложением (js, css, ...)
    index.php               точка входа в приложение. С него начинается выполнение приложения
runtime/                    файлы, которые генерирует Yii 2 во время выполнения приложения (логи, кэш и т.п.)
vendor/                     содержит подключаемые пакеты, фреймворк Yii 2 и т.д.
yii                         скрипт выполнения консольного приложения Yii 2
```

Подробнее о каталоге admin/ и структуре системы [здесь](structure-overview.md).

> Note:  Файлы ресурсов css, js автоматически подключаются в приложение, если имеют название `main` и находятся в соответствующей текущему роутингу иерархии папок. 
Например, файл catalog/item/main.js автоматически подключится в вид для действия item контроллера catalog.

## Конфигурации приложения

Когда [входной скрипт](structure-entry-script.md) создаёт приложение, он загружает конфиги
и применяет их к приложению, например:

```php
$config = require(__DIR__ . '/../admin/config/admin.php'); //Подключение общего для всех приложений конфига

$config = array_replace_recursive($config,require(__DIR__ . '/../' .  APP_NAME . '/config/web.php'));//Подключение конфига для приложения
if (YII_ENV_PROD) {
    $config = array_replace_recursive($config, require(__DIR__ . '/../' . APP_NAME . '/config/db.php'));//Параметры подключения к БД прод
} else {
    $config = array_replace_recursive($config, require(__DIR__ . '/../' . APP_NAME . '/config/db_dev.php'));//Параметры подключения к БД разработка
}
```


Конфигурационный файл демо-приложения:

```php
$config = [
    'layout' => 'public',
    'components' => [
        'request' => [
            'cookieValidationKey' => '..............',
        ],
        'urlManager' => [
            'rules' => [
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                'catalog/search' => 'catalog/search',
                'catalog/<slug:[\w-]+>' => 'catalog',
                'catalog/<category:[\w-]+>/<slug:[\w-]+>' => 'catalog/item',
                'brand/<slug:[\w-]+>' => 'brand',
                'news/<slug:[\w-]+>' => 'news',                
                'gallery/<slug:[\w-]+>' => 'gallery',
                 [
                    'pattern' => 'blog',
                    'route' => 'article',
                    'defaults' => ['slug' => 'blog']
                ],
                [
                    'pattern' => 'blog/<slug:[\w-]+>',
                    'route' => 'article/item',
                    'defaults' => ['category' => 'blog']
                ],
                [
                    'pattern' => 'help',
                    'route' => 'article',
                    'defaults' => ['slug' => 'help']
                ],
                [
                    'pattern' => 'help/<slug:[\w-]+>',
                    'route' => 'article/item',
                    'defaults' => ['category' => 'help']
                ],
                [
                    'pattern' => 'about',
                    'route' => 'article',
                    'defaults' => ['slug' => 'about']
                ],
                [
                    'pattern' => 'about/<slug:[\w-]+>',
                    'route' => 'article/item',
                    'defaults' => ['category' => 'about']
                ],
                'article/<slug:[\w-]+>' => 'article',
                'article/<category:[\w-]+>/<slug:[\w-]+>' => 'article/item',
            ],
        ],
    ],
    'params' => [],
];
return $config;
```

Остальные настройки входной скрипт получит в конфиге системы `admin\config\admin.php` (для консольного приложения `admin\config\console.php`).
Для приложения также настривается псевдоним вида `@APP_NAME`.
Подробнее про конфигурационные файлы системы [здесь](settings-overview.md).