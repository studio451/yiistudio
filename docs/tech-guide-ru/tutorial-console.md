Консольные команды
=====================

Настройки по-умолчанию для консольного приложения хранятся в файле `admin\config\console`.

Во входном скрипте консольного приложения определите константу `APP_CONSOLE` в true.

```php
defined('APP_CONSOLE') or define('APP_CONSOLE', false);//Если приложение консольное
```

Помимо стандартных команд Yii 2 в Yii Studio определены дополнительные консольные команды:

```
Создание дампа
yii dump/create [--isArchive --schemaOnly]

Восстановление из дампа
yii dump/restore [--initData --demoData --restoreScript --dumpId]
```

```
Переинициализация ролей
yii rbac/init [--user_id]
```


Также консольные команды модулей:

Модуль YML (Экспорт/импорт)

```
Запуск импорта
yii yml/import [--id]

Запуск полного импорта
yii yml/full-import [--id]

Запуск экспорта
yii yml/export [--id]

Загрузка элементов каталога из excel-файла
yii yml/load-items-from-excel-file [--id -- file_name]

Добавление элементов каталога из excel-файла
yii yml/add-items-from-excel-file [-- file_name]

Обновление элементов каталога из excel-файла
yii yml/update-items-from-excel-file [-- file_name]

Загрузка категорий каталога из excel-файла
yii yml/load-categories-from-excel-file [-- file_name]

Загрузка новостей из excel-файла
yii yml/load-news-from-excel-file [-- file_name]

Загрузка пользователей из excel-файла
yii yml/load-users-from-excel-file [-- file_name]
```

Модуль Sitemap (Карта сайта)

```
Генерация карты сайта
yii sitemap/generate

Удаление карты сайта
yii sitemap/delete
```

Модуль Shopcart (Корзина)

```
Удаление пользователей, у которых нет ни одного заказа
yii shopcart/clear-users-no-order
```

Модуль Catalog (Каталог)

```
Пересоздание групп элементов каталога
yii catalog/recreate-groups

Пересохранение элементов каталога
yii catalog/resave-items
```

Запуск всех команд доступен из Панели управления через Web-консоль `admin\helpers\WebConsole`.