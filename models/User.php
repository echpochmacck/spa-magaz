<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string|null $patronymic
 * @property string $login
 * @property string $email
 * @property string $password
 * @property string $token
 * @property int $role_id
 * @property float $cash
 *  @property string $register_at
 * 
 *
 * @property Basket[] $baskets
 * @property Orders[] $orders
 * @property Role $role
 */
class User extends \yii\db\ActiveRecord  implements IdentityInterface
{

    public string $password_repeat = '';


    const SCENARIO_REGISTER = 'register';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'surname',  'email', 'password'], 'required', 'on' => self::SCENARIO_REGISTER],
            [['role_id'], 'integer'],
            [['cash'], 'number'],
            [['name', 'surname', 'patronymic', 'login', 'email', 'password', 'token'], 'string', 'max' => 255],
            [['email'], 'unique','on' => self::SCENARIO_REGISTER],
            [['login'], 'unique','on' => self::SCENARIO_REGISTER],
            [['login', 'password'], 'required'],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::class, 'targetAttribute' => ['role_id' => 'id']],
            ['email', 'email'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],

            ['password', 'string', 'min' => 4,  'on' => self::SCENARIO_REGISTER],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'patronymic' => 'отчество',
            'login' => 'Login',
            'email' => 'Email',
            'password' => 'Пароль',
            'token' => 'Token',
            'role_id' => 'Role ID',
            'cash' => 'Cash',
        ];
    }

    /**
     * Gets query for [[Baskets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBaskets()
    {
        return $this->hasMany(Basket::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Orders::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::class, ['id' => 'role_id']);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        // return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        // return $this->authKey === $authKey;
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['register_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }


    public function setToken($save = false)
    {
        $this->token = Yii::$app->security->generateRandomString();
        $save && $this->save(false);
    }


    public function register()
    {
        $this->password = Yii::$app->security->generatePasswordHash($this->password);
        $this->role_id = Role::getroleId('avtor');
        $this->setToken();
        $this->cash = 2000;
        return $this->save(false);
    }


    public function valiadtePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }



    public function logout()
    {
       $this->token = null;
       $this->save(false);
    }


    public function getIsAdmin()
    {
        return $this->role_id === Role::getRoleId('admin');
    }


   
}
