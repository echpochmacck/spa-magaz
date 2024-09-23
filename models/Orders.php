<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property int $user_id
 * @property string $status
 * @property string $order_date
 * @property float|null $sum
 *
 * @property Sostav[] $sostavs
 * @property User $user
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'status'], 'required'],
            [['user_id'], 'integer'],
            [['order_date'], 'safe'],
            [['sum'], 'number'],
            [['status'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'status' => 'Status',
            'order_date' => 'Order Date',
            'sum' => 'Sum',
        ];
    }

    /**
     * Gets query for [[Sostavs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSostavs()
    {
        return $this->hasMany(Sostav::class, ['order_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function getOrders($id)
    {
        $query = Self::find()
        ->select('*')
        ->where(['user_id' => $id])
        // ->asArray()
        // ->all()
         ;
        // return  new ActiveDataProvider([
        //     'query' => $query
        // ]);
        return $query 
        ->asArray()
        ->all()
        ;
    }
    public function getOrderInfo()
    {
        return Sostav::find()
                    ->select('*')
                    ->where(['order_id' => $this->id])
                    ->asArray()
                    ->all()
                    ;
         
    } 
}
