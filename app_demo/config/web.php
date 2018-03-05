<?

$config = [
    'layout' => 'public',
    'components' => [
        'request' => [
            'cookieValidationKey' => 'fSLDMSrhuxvUY36XDQysJ3D02zMbCuTr',
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
