<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;

class UserController extends \yii\rest\ActiveController
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

        $auth = $behaviors["authenticator"];
        unset($behaviors["authenticator"]);

        $behaviors["corsFilter"] = [
            "class" => Cors::class,
            "cors" => [
                // restrict access to
                "Origin" => [(isset($_SERVER["HTTP_ORIGIN"]) ? $_SERVER["HTTP_ORIGIN"] : "http://" . $_SERVER["REMOTE_ADDR"])],
                "Access-Control-Request-Method" => ["OPTIONS", "POST", "GET"],
                "Access-Control-Request-Headers" => ["Content-Type", "Authorization"],
            ],
            "actions" => [
                "logout" => [
                    "Access-Control-Allow-Credentials" => true,
                ],
                'user-info' => [
                    "Access-Control-Allow-Credentials" => true,
                ]
            ]
        ];

        $auth = [
            "class" => HttpBearerAuth::class,
            "only" => ["logout", 'user-info']
        ];

        $behaviors["authenticator"] = $auth;

        return $behaviors;
    }


    // public function actionOptions()
    // {
    //     Yii::$app->response->headers->set('Access-Control-Allow-Origin', '*');
    //     Yii::$app->response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS');
    //     Yii::$app->response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    //     Yii::$app->response->statusCode = 200;
    // }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions["delete"], $actions["create"], $actions["index"], $actions["view"], $actions["update"]);
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
            Yii::$app->response->statusCode = 201;
            $result = [
                'code' => 201,
                'data' => [
                    'token' => $model->token,
                    'isAdmin' => $model->isAdmin

                ]
            ];
        } else {
            Yii::$app->response->statusCode = 422;
            $result = [
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
        if ($model->validate()) {
            $user = User::findOne(['login' => $model->login]);

            if ($user && $user->valiadtePassword($model->password)) {
                $user->setToken(true);
                Yii::$app->response->statusCode = 201;
                $result = [
                    'code' => 201,
                    'data' => [
                        'token' => $user->token,
                        'isAdmin' => $user->isAdmin

                    ]
                ];
            } else {
                Yii::$app->response->statusCode = 401;
                $result = [
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


    public function actionLogout()
    {
        $user = User::findOne(['token' => Yii::$app->user->identity->token]);
        $user->logout();
        Yii::$app->response->statusCode = 204;
    }



    public function actionUserInfo()
    {
        $user = User::findOne(['token' => Yii::$app->user->identity->token]);
        // var_dump('asd');die;
        $result = [
            'code' => 200,
            'data' =>  $user->attributes
        ];
        return $result;
    }
    public function actionGetFile($file_name)
    {
        // var_dump('dfdf');die;
        if (file_exists("../src/$file_name")) {
            Yii::$app->response->statusCode = 200;
            Yii::$app->response->sendFile("../src/$file_name")->send();
        } else {
            Yii::$app->response->statusCode = 404;
            Yii::$app->response->send();
        }
    }
}
