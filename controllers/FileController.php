<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;

class FileController extends \yii\rest\ActiveController
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
                // "get-file" => [
                //     "Access-Control-Allow-Credentials" => true,
                // ],
            ]
        ];

        // $auth = [
        //     "class" => HttpBearerAuth::class,
        //     // "only" => ["get-file"]
        // ];

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
