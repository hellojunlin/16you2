<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-app',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['gii','log'],
    'modules' => [
        'gii'=>'yii\gii\Module'
        ],
    'controllerNamespace' => 'app\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
    	'errorHandler' => [
    			'errorAction' => 'index/index',
    	],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning','trace'],
                ],
            ],
        ],
    ],
    'params' => $params,
];
