<?php

namespace app\controllers;

use app\models\Products;
use app\models\User;
use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;


class ProductController extends  ActiveController
{

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
                'Access-Control-Request-Headers' => ['Content-Type', 'Authorization'],
            ],
            'actions' => [
                'get-product-list' => [
                    'Access-Control-Allow-Credentials' => true,

                ]
            ]
        ];


        $auth = [
            'class' => HttpBearerAuth::class,
            'only' => ['get-product-list'],
        ];
        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)

        return $behaviors;
    }


    // public function actionOptions()
    // {
    //     Yii::$app->response->headers->set('Access-Control-Allow-Origin', '*');
    //     Yii::$app->response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS');
    //     Yii::$app->response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    //     Yii::$app->response->headers->set('Access-Control-Allow-Credentials', 'true'); // если нужно отправлять куки или токен
    //     Yii::$app->response->statusCode = 200;
    // }


    public function actions()
    {
        $actions = parent::actions();

        // disable the "delete" and "create" actions
        unset($actions['delete'], $actions['create']);

        // customize the data provider preparation with the "prepareDataProvider()" method
        // $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGetProductList()
    {

        Yii::$app->response->statusCode = 200;
        $result = [
            'data' => Products::getProducts(),
            'code' => 200
        ];
        return $result;
    }


    public function actionGetProductInfo($product_id)
    {
        // var_dump($product_id);die;  
        $product = Products::findOne($product_id);
        if ($product) {
            Yii::$app->response->statusCode = 200;
            $result = [
                'data' => $product->attributes,
                'code' => 200
            ];
        } else {
            Yii::$app->response->statusCode = 404;
            $result = [
                'code' => 404,
                'error' => 'Not Found'
            ];
        }
        return $result;
    }
}
