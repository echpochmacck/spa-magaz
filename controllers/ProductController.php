<?php

namespace app\controllers;

use app\models\Products;
use app\models\User;
use Yii;
use yii\filters\auth\HttpBearerAuth;


class ProductController extends \yii\rest\Controller
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
            'only' => ['get-product-list'],
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
}
