<?php

namespace app\controllers;

use app\models\Basket;
use app\models\Orders;
use app\models\Products;
use app\models\User;
use Yii;
use yii\filters\auth\HttpBearerAuth;

class OrdersController extends \yii\rest\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public  $enableCsrfValidation = false;
    public  $modelClass  = '';


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
            'only' => ['get-order-list', 'get-order'],
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
        // $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }


    public function actionGetOrderList()
    {
        Yii::$app->response->statusCode = 200;
        $result = [
            'data' => Orders::getOrders(Yii::$app->user->identity->id),
            'code' => 200
        ];
        return $result;
    }


    public function actionGetOrder($order_id)
    {
        $order = Orders::findOne([$order_id])
         ;
         $result = [];
         if ($order) {

             if (Yii::$app->user->identity->id === $order->user_id) {
                $products = $order->getOrderInfo();
                Yii::$app->response->statusCode = 200;
                $result = [
                    'code' => 200,
                    'data' => [
                        'order' => $order->attributes,
                        'products' => $products
                    ]
                    ];
             } else {
            Yii::$app->response->statusCode = 401;
                $result = [
                    'error' => 'Prohibitted for you',
                    'code' => 401
                ];
             }
         } else {
            Yii::$app->response->statusCode = 404;
         }
         return $result;
    }


    // public function actionBasket()
    // {
    //     $data= Yii::$app->request->post();
    //     $product = Products::findOne($data['product_id']);
    //     if ($product) {
    //         $basket = Basket::findOne(['user_id' => Yii::$app->user->identity->id]);
    //     }
    // }
}
