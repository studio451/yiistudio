<?

$config = [
    'id' => APP_NAME,
    'basePath' => APP_PATH,
    'controllerNamespace' => APP_NAME . '\controllers',
    'language' => 'ru-RU',
    'aliases' => 
    [
        '@basePath' => BASE_PATH,
        '@app' => APP_PATH,
        '@' . APP_NAME => APP_PATH,
        '@admin' => ADMIN_PATH,        
        '@webroot' => BASE_PATH . '/public_html',
    ],
    'modules' => [
        'admin' => [
            'class' => 'admin\AdminModule',
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module'
        ],        
        'gii' => 'yii\gii\Module',
        'debug' => 'yii\debug\Module'
    ],
    'bootstrap' => ['log'],
    'runtimePath' => BASE_PATH . '/runtime',
    'vendorPath' => BASE_PATH . '/vendor',
    'defaultRoute' => 'public',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-' . APP_NAME,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => BASE_PATH . '/runtime/cache',
        ],
        'errorHandler' => [
            'errorAction' => 'public/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                    [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'assetManager' => [
            // uncomment the following line if you want to auto update your assets (unix hosting only)
            //'linkAssets' => true,
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [YII_DEBUG ? 'jquery.js' : 'jquery.min.js'],
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [YII_DEBUG ? 'css/bootstrap.css' : 'css/bootstrap.min.css'],
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [YII_DEBUG ? 'js/bootstrap.js' : 'js/bootstrap.min.js'],
                ],
            ],
            'baseUrl' => '/assets',
            'appendTimestamp' => true,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'admin/<controller:\w+>/<action:[\w-]+>/<id:\d+>' => 'admin/<controller>/<action>',
                'admin/<module:\w+>/<controller:\w+>/<action:[\w+]+>/<id:\d+>' => 'admin/<module>/<controller>/<action>'
            ],
        ],
        'user' => [
            'identityClass' => 'admin\models\User',
            'enableAutoLogin' => true,
            'authTimeout' => 86400,
            'identityCookie' => ['name' => '_identity-' . APP_NAME, 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login
            'name' => APP_NAME,
        ],
        'i18n' => [
            'translations' => [
                'admin/install' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'ru-RU',
                    'basePath' => '@admin/messages',
                ],
                '*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'sourceMessageTable' => 'admin_translate_source_message',
                    'messageTable' => 'admin_translate_message',
                    'enableCaching' => true,
                ],
            ],
        ],
        'formatter' => [
            'sizeFormatBase' => 1000,
            'dateFormat' => 'dd.MM.yyyy',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'RUB',
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
    ],
    'bootstrap' => ['admin']
];

if (YII_DEBUG) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['bootstrap'][] = 'gii';
    $config['components']['db']['enableSchemaCache'] = false;
    $config['components']['mailer']['useFileTransport'] = true;
}
return $config;
