<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%product}}".
 *
 * @property integer $id
 * @property string $product_name
 * @property string $prdouct_details
 * @property integer $type
 * @property integer $state
 * @property string $image_url
 * @property string $integral
 * @property integer $number
 * @property integer $sort
 * @property integer $createtime
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_name', 'prdouct_details', 'image_url', 'integral'], 'required'],
            [['prdouct_details'], 'string'],
            [['type', 'state', 'number', 'sort', 'createtime'], 'integer'],
            [['product_name', 'integral'], 'string', 'max' => 100],
            [['image_url'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '商品表',
            'product_name' => '商品名称',
            'prdouct_details' => '商品详情',
            'type' => '0虚拟物品 1实物',
            'state' => '状态 0禁用 1启用',
            'image_url' => '商品图片',
            'integral' => '兑换所需积分',
            'number' => '剩余数量',
            'sort' => '排序   数值越大越靠前',
            'createtime' => 'Createtime',
        ];
    }
}
