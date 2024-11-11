<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'qwe',
            'baseUrl' => '',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'multipart/form-data' => 'yii\web\MultipartFormDataParser'

            ]
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG, // use "pretty" output in debug mode
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                    // ...
                ],
            ],
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if ($response->statusCode == 404) {
                    $response->data = [
                        'message' => 'Not Found',
                        'code' => 404,
                    ];
                }
            },

        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'enableSession' => false,

        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
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
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'user'],
                'OPTIONS api/user/register' => 'user/options',
                'POST api/user/register' => 'user/register',

                // авторизация
                'OPTIONS api/user/login' => 'user/options',
                'POST api/user/login' => 'user/login',

                'OPTIONS api/user/info' => 'user/options',
                'GET api/user/info' => 'user/user-info',

                'GET api/file/<file_name>' => 'file/get-file',



                'OPTIONS api/user/logout' => 'user/options',
                'GET api/user/logout' => 'user/logout',

                // получение товаров для главной страницы
                'OPTIONS api/products' => 'product/options',
                'GET api/products' => 'product/get-product-list',

                'GET api/product' => 'product/get-product-info',


                // получение списка заказов для пользователя
                'GET api/orders' => 'orders/get-order-list',
                'OPTIONS api/orders' => 'orders/options',

                'GET api/orders/<order_id>' => 'orders/get-order',
                'OPTIONS api/orders/<order_id>' => 'orders/options',
                'DELETE api/orders/<order_id>' => 'orders/delete-order',


                // добавление товара в карзину
                'OPTIONS api/order/basket' => 'orders/options',
                'POST api/order/basket' => 'orders/basket',
                'OPTIONS api/order/basket' => 'orders/options',

                // получение корзинф
                'OPTIONS api/order/getBasket' => 'orders/options',
                'GET api/order/getBasket' => 'orders/get-basket',

                'OPTIONS api/order/basket/remove' => 'orders/options',
                'POST api/order/basket/remove' => 'orders/remove-basket',


                'OPTIONS api/order/basket/removePosition' => 'orders/options',
                'POST api/order/basket/removePosition' => 'orders/remove-position',

                'OPTIONS api/order/make' => 'orders/options',
                'GET api/order/make' => 'orders/make-order',


                // админка
                'GET api/admin/orders' => 'admin/get-orders',
                'OPTIONS api/admin/orders' => 'admin/options',

                'GET api/admin/statuses' => 'admin/get-statuses',
                'OPTIONS api/admin/statuses' => 'admin/options',

                // 'GET api/admin/order' => 'admin/get-order-info',
                'OPTIONS api/admin/order/<order_id>' => 'admin/options',
                'PATCH api/admin/order/<order_id>' => 'admin/change-status',


            ],
        ]

    ],
    'params' => $params,


];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['*'],
    ];
}

return $config;
