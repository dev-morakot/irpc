<?php

use yii\helpers\VarDumper;

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'irpct_group',
    'name' => 'IRPCT GROUP',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'session',
        'log', 
    ],
    'language'=>'th_TH',
    'timeZone'=>'UTC', // ต้อง set timezone DB เป็น Asia/Bangkok
    'aliases'=>require(__DIR__ . '/aliases.php'),
    'components' => [

        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages', // if advanced application, set @frontend/messages
                    'sourceLanguage' => 'en',
                    'fileMap' => [
                    //'main' => 'main.php',
                    ],
                ],
            ],
        ],

        'formatter'=>[
            'class'=>'yii\i18n\Formatter',
            'dateFormat'=>'php:d/m/Y',
            'datetimeFormat'=>'php:d/m/Y H:i:s',
            'timeFormat'=>'php:H:i:s',
            'currencyCode'=>'฿',
            'decimalSeparator'=>'.',
            'thousandSeparator'=>',',
        ],
        'thaiFormatter'=>[
            'class'=>'app\components\ThaiYearFormatter',
            //'nullDisplay'=>'-'
            'dateFormat'=>'php:d/m/Y',
        ],

        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'gk4L_YfiFVXngDvdSpMDUoM7Nr2cLHuw',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'enableSession'=>true,
            'identityCookie' => [
                'name'=>'_identity',
                'httpOnly'=>true,
                //The timestamp defaults to 0, meaning "until the browser is closed"
                //'expire'=> 5
            ],
            'loginUrl'=>['/site/login'],
            'on afterLogin'=>function($event){
                Yii::$app->userlog->info("Login");
            }
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        
        'mailer' => require(__DIR__ . '/mailer.php'),
        'log' => require(__DIR__.'/log.php'),
        'db' => require(__DIR__ . '/db.php'),
       // 'pdf'=> require(__DIR__ . '/mpdf.php'),
        
        /*'urlManager' => [
            'enablePrettyUrl' => true,
            //'enableStrictParsing'=>true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],*/

        'authManager' => [
            'class' => '\yii\rbac\DbManager',
            'db'=>require(__DIR__.'/db.php'),
        ],
        
        'userlog'=>[
            'class'=>'app\components\UserLogComponent',
            'db'=>  require(__DIR__.'/db.php'),
        ],
        'docmsg'=>[
            'class'=>'app\components\DocMsgComponent',
            'db'=>  require(__DIR__.'/db.php'),
        ],
        'docAttach'=>[
            'class'=>'app\components\DocAttachComponent',
            'db'=>  require(__DIR__.'/db.php'),
        ],
        'assetManager'=>[
            //'appendTimestamp'=>true, เปิดแล้วมีปัญหากับ pjax 
            //'bundles'=>require(__DIR__.'/'.'assets-prod.php')
        ]
    ],
    'params' => $params,

    'as access' => [
        'class' => \yii\filters\AccessControl::className(),
        'rules' => [
            // allow guest users
            [
                'allow' => true,
                'actions' => ['login','reset-password','error'],
            ],
            // allow authenticated users
            [
                'allow' => true,
                'roles' => ['@'],
            ],
            // everything else is denied
        ],
//        'denyCallback' => function () {
//            return Yii::$app->response->redirect(['site/login']);
//        },
    ],

    'on afterRequest'=>function($event){
        $time_config = 60*60*3; //log user online at 3 hour
        $session = Yii::$app->session;
        if(!$session->has('_last_checkin')){
            $session['_last_checkin'] = time();
        }
        $last_checkin = $session['_last_checkin'];
        $current_checkin = time();
        $diff_time = $current_checkin - $last_checkin;
        //Yii::info('diff time (sec) '.$diff_time);
        if($diff_time>$time_config && !Yii::$app->user->isGuest){
            Yii::$app->userlog->info("Online");
            $session['_last_checkin'] = $current_checkin;
        }
    },


    'modules' => [
        // Module for rbac role management
        'rbac' => [
            'class' => 'app\modules\rbac\src\Module'//'johnitvn\rbacplus\Module',
        ],
        'asset' => [
            'class' => 'app\modules\asset\Asset'
        ],
        'resource'=>[
            'class' => 'app\modules\resource\Resource'
        ],
        'helpdesk'=>[
            'class' => 'app\modules\helpdesk\HelpDesk'
        ],
        'admin'=>[
            'class'=>'app\modules\admin\AdminModule'
        ],
        'gridview' =>  [
            'class' => 'app\modules\grid\Module'//'\kartik\grid\Module'
        // enter optional module parameters below - only if you need to  
        // use your own export download action or custom translation 
        // message source
        // 'downloadAction' => 'gridview/export/download',
        // 'i18n' => []
        ]
    ]
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
        //'allowedIPs' => ['127.0.0.1', '::1','http://iam7.dyndns.org/.*'],
        'allowedIPs' => ['*']
    ];
    $config['components']['assetManager']['forceCopy'] = true;
}

return $config;
