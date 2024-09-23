<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property float $price
 *
 * @property BasketSostav[] $basketSostavs
 * @property Sostav[] $sostavs
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'price'], 'required'],
            [['description'], 'string'],
            [['price'], 'number'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'price' => 'Price',
        ];
    }

    /**
     * Gets query for [[BasketSostavs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBasketSostavs()
    {
        return $this->hasMany(BasketSostav::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[Sostavs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSostavs()
    {
        return $this->hasMany(Sostav::class, ['product_id' => 'id']);
    }

    public static function getProducts()
    {
        $product = Products::find()
            ->select('*')
            ->asArray()
            ->all();
        return $product;
    }
}
