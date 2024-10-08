<?php

namespace app\controllers;

use app\models\Orders;
use app\models\User;
use Yii;
use yii\filters\auth\HttpBearerAuth;

class AdminController extends \yii\rest\Controller
{
    public  $enableCsrfValidation = false;
    public  $modelClass  = '';


    public function actionIndex()
    {
        return $this->render('index');
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
            'cors' => [
                // restrict access to
                'Origin' => [(isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : 'http://' . $_SERVER['REMOTE_ADDR'])],
                // Allow only POST and PUT methods
                'Access-Control-Request-Method' => ['POST', 'GET', 'OPTIONS'],
                // Allow only headers 'X-Wsse'
                'Access-Control-Request-Headers' => ['Content-type', 'Authorization'],
            ],
            'actions' => [
                'logout' => [
                    'Access-Control-Allow-Credentials' => true,

                ]
            ]
        ];


        $auth = [
            'class' => HttpBearerAuth::class,
            'only' => ['get-orders', 'get-order-info'],
        ];
        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

        // disable the "delete" and "create" actions
        unset($actions['delete'], $actions['create']);

        // customize the data provider preparation with the "prepareDataProvider()" method
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }
    public function actionGetOrders()
    {
        $user = User::findOne(Yii::$app->user->identity->id);
        if ($user->isAdmin) {
            Yii::$app->response->statusCode = 200;
            $result = [
                'code' => 401,
                'data' => [
                    'orders' => Orders::getAllOrders()
                ]
                ];
        } else {

            Yii::$app->response->statusCode = 401;
            $result = [
                'code' => 401,
                'error' => 'Prohibitted for you'
            ];
        }
        return $result;
    }

    public function actionGetOrderInfo($order_id)
    {
        $user = User::findOne(Yii::$app->user->identity->id);
        if ($user->isAdmin) {
            $order = Orders::findOne($order_id);
            if ($order) {
                Yii::$app->response->statusCode = 200;
                $result = [
                    'code' => 401,
                    'data' => [
                        'order' => $order->attributes
                    ]
                    ];
            } else {
                Yii::$app->response->statusCode = 404;
                $result = [
                    'code' => 404,
                    'error' => 'Not Found'
                ];
            }
            
        } else {

            Yii::$app->response->statusCode = 401;
            $result = [
                'code' => 401,
                'error' => 'Prohibitted for you'
            ];
        }
        return $result;
    }

    

}

