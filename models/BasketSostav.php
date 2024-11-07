<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "basketSostav".
 *
 * @property int $id
 * @property int $product_id
 * @property int $quantity
 * @property int $basket_id
 *
 * @property Basket $basket
 * @property Products $product
 */
class BasketSostav extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'basketSostav';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'quantity', 'basket_id'], 'required'],
            [['product_id', 'quantity', 'basket_id'], 'integer'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
            [['basket_id'], 'exist', 'skipOnError' => true, 'targetClass' => Basket::class, 'targetAttribute' => ['basket_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'quantity' => 'Quantity',
            'basket_id' => 'Basket ID',
        ];
    }

    /**
     * Gets query for [[Basket]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBasket()
    {
        return $this->hasOne(Basket::class, ['id' => 'basket_id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::class, ['id' => 'product_id']);
    }

    public static function getProducts($basket_id)
    {
        return Self::find()
            ->select(['products.*', 'quantity'])
            ->innerJoin('products', 'basketSostav.product_id = products.id')
            ->where(['basket_id' => $basket_id])
            ->asArray()
            ->all();
    }

    // public static function getForBasketProducts($basket_id)
    // {
    //     return Self::find()
    //         ->select(['products.*', 'quantity'])
    //         ->innerJoin('products', 'basketSostav.product_id = products.id')
    //         ->where(['basket_id' => $basket_id])
    //         ->asArray()
    //         ->all();
    // }
}
