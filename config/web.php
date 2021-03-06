<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$db_presensi = require __DIR__ . '/db_presensi.php';
$db_logbook_test = require __DIR__ . '/db_logbook_test.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
            'base' => [
                'class' => 'app\modules\base\base',
            ],
            'pegawai' => [
                'class' => 'app\modules\pegawai\pegawai',
            ],
            'app' => [
                'class' => 'app\modules\app\app',
            ],
            'logbook' => [
                'class' => 'app\modules\logbook\logbook',
            ],
        ],
    'components' => [
        'jwt' => [
            'class' => \sizeg\jwt\Jwt::class,
            'key' => 'secret',
            // You have to configure ValidationData informing all claims you want to validate the token.
            'jwtValidationData' => \app\components\JwtValidationData::class,
        ],
        /*'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',                                
                '<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
            ],
        ],*/
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'e9YOpKOhsRXSOkwHHy0AQWYU6Q_gAz8L',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
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
        'db_presensi' => $db_presensi,
        'db_logbook_test' => $db_logbook_test,
        'assetManager' => [
            'bundles' => [
                // 'yii\web\JqueryAsset' => [
                //     'js'=>[]
                // ],
                // 'yii\bootstrap\BootstrapPluginAsset' => [
                //     'js'=>[]
                // ],
                /*'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ],*/

            ],
        ],
        /*'view' => [
             'theme' => [
                 'pathMap' => [
                    '@app/views' => '@vendor/dmstr/yii2-adminlte-asset/example-views/yiisoft/yii2-app'
                 ],
             ],
        ],*/
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
    'defaultRoute'=>'site/default',
    'timeZone' => 'Asia/Jakarta',
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
