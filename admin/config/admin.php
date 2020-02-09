<?

$config = [
    'id' => APP_NAME,
    'basePath' => APP_PATH,
    'controllerNamespace' => APP_NAME . '\controllers',
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
        '@runtime' => BASE_PATH . '/runtime',
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
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
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => '',
                'password' => '',
                'port' => '465',
                'encryption' => 'ssl',
            ],
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
            'loginUrl' => ['user/login'],
            'enableAutoLogin' => false,
            'authTimeout' => 86400,
            'identityCookie' => ['name' => '_identity-' . APP_NAME, 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login
            'name' => APP_NAME,
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
