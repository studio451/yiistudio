<?

return [
    'id' => APP_NAME,
    'basePath' => APP_PATH,
    'sourceLanguage' => 'ru-RU',
    'language' => 'ru-RU',
    'aliases' =>
        [
        '@basePath' => BASE_PATH,
        '@app' => APP_PATH,
        '@' . APP_NAME => APP_PATH,
        '@admin' => ADMIN_PATH,
        '@webroot' => BASE_PATH . '/public_html',
        '@web' => BASE_PATH . '/public_html',
    ],
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'admin\commands',
    'controllerMap' => [
        'yml' => [
            'class' => 'admin\modules\yml\commands\YmlController'
        ],
    ],
    'modules' => [
        'admin' => [
            'class' => 'admin\AdminModule',
        ],
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => BASE_PATH . '/runtime/cache',
        ],
        'log' => [
            'targets' => [
                    [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'admin' => [
                    'sourceLanguage' => 'ru-RU',
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@admin/messages',
                ],
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'ru-RU',
                    'basePath' => '@app/messages'
                ],
                'yii' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en-US',
                    'basePath' => '@app/messages'
                ],
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'assignmentTable' => 'admin_auth_assignment',
            'itemChildTable' => 'admin_auth_item_child',
            'itemTable' => 'admin_auth_item',
            'ruleTable' => 'admin_auth_rule',
            'defaultRoles' => [
                'User',
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'admin/<controller:\w+>/<action:[\w-]+>/<id:\d+>' => 'admin/<controller>/<action>',
                'admin/<module:\w+>/<controller:\w+>/<action:[\w+]+>/<id:\d+>' => 'admin/<module>/<controller>/<action>'
            ],
        ],
    ],
    'params' => [],
];
