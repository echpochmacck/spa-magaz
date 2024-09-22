<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\filters\auth\HttpBearerAuth;

class UserController extends \yii\rest\Controller
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
            'only' => ['logout'],
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

    public function actionRegister()
    {
        $model = new User();
        $model->load(Yii::$app->request->post(), '');
        $model->scenario = 'register';
        $result = [];
        if ($model->validate()) {
            $model->register();
            Yii::$app->response->statusCode = 200;
            $result[] = [
                'code' => 200,
                'data' => [
                    'token' => $model->token
                ]
            ];
        } else {
            Yii::$app->response->statusCode = 422;
            $result[] = [
                'code' => 422,
                'errors' => $model->errors
            ];
        }
        return $result;
    }

    public function actionLogin()
    {
        $model = new User();
        $model->load(Yii::$app->request->post(), '');
        $result = [];
        if ($model->validate()) {
            $user = User::findOne(['login' => $model->login]);
            if ($user && $user->valiadtePassword($model->password)) {
                $user->setToken(true);
                Yii::$app->response->statusCode = 200;
                $result[] = [
                    'code' => 200,
                    'data' => [
                        'token' => $user->token
                    ]
                ];
            } else {
                Yii::$app->response->statusCode = 401;
                $result[] = [
                    'code' => 422,
                    'errors' => 'Неправильный логин или пароль'
                ];
            }
        } else {
            Yii::$app->response->statusCode = 422;
            $result[] = [
                'code' => 422,
                'errors' => $model->errors
            ];
        }
        return $result;
    }
}
