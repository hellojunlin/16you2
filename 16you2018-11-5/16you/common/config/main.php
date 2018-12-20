<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'zh-CN',
    'bootstrap' => ['gii','log'],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
            //'cachePath' => '@frontend/runtime/cache2',
			//'cachePath' => dirname(yii::$app->basePath) . '/common/cache',
			'cachePath' => dirname(dirname(__DIR__)) . '/common/cache',
        ],

        'authManager' => [
        'class' => 'yii\rbac\DbManager',
        'itemTable' => 'g_auth_item',
        'assignmentTable' => 'g_auth_assignment',
        'itemChildTable' => 'g_auth_item_child',
        ],

		'urlManager' => [
    		'class' => 'yii\web\UrlManager',
    		'enablePrettyUrl' => true,
    		'enableStrictParsing' => false,  //不启用严格解析
    		'showScriptName' => false,
    		'suffix' => '.html',
    		'rules' => [
    		'<controller:\w+>/<action:\w+>/<id:\d+>/'=> '<controller>/<action>',
    		'<controller:\w+>/<action:\w+>!<puid:\w+>/'=> '<controller>/<action>',
    		'<controller:\w+>/<action:\w+>/<id:\d+>/<puid:\w+>'=> '<controller>/<action>',
    		'<controller:\w+>/<action:\w+>!<puid:\w+>/<type:\w+>'=> '<controller>/<action>',
    		// '<controller: \w+>/<action:\w+>/<state:\d+>/'=> '<controller>/<action>'
    		],
		],

        'db' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=rm-wz9a6fcg48yt12124o.mysql.rds.aliyuncs.com;dbname=game_16you',
        'username' => 'yerensystem',
        'password' => 'Yeren_16you_9025',
        'charset' => 'utf8',
        'tablePrefix'=>'g_',
        'enableSchemaCache' => true,   //打开模式缓存来节省解析数据库模式的时间
        ],
        
       'log' => [            
                        'class' => 'yii\log\Dispatcher',           
                        'traceLevel' => YII_DEBUG ? 3 : 0,     
                        'targets' => [
                                 [ 'class' => 'yii\log\FileTarget',
                                   'levels' => ['error','trace'],
                                 ],
                         ],
         ], 

        'request' => [
            'cookieValidationKey' => '83r5HbITBiMfmiYPOZFdL-raVp4O1VV4',
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
        ],

        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '112.74.171.237',
            'port' => 6379, 
            'database' => 0,
            'password'=>'yeren16you'  
        ],   

        'myHelper' => array(
            'class' => 'common\common\Helper',
        ),
    ],
    'defaultRoute' => 'index/index',
];
