<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%exchange}}".
 *
 * @property integer $id
 * @property integer $pid
 * @property string $product_name
 * @property string $integral
 * @property string $area
 * @property integer $uid
 * @property integer $createtime
 * @property string $phone
 * @property string $username
 * @property string $province
 * @property string $city
 * @property string $detaaddress
 * @property string $getcode
 * @property integer $isdispose
 */
class Exchange extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%exchange}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'product_name', 'integral', 'uid'], 'required'],
            [['pid', 'uid', 'createtime', 'isdispose'], 'integer'],
            [['area'], 'string'],
            [['product_name', 'integral'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 11],
            [['username', 'province', 'city'], 'string', 'max' => 50],
            [['detaaddress'], 'string', 'max' => 255],
            [['getcode'], 'string', 'max' => 25],
            [['getcode'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '兑换表',
            'pid' => '商品id',
            'product_name' => '商品名称',
            'integral' => '兑换所需积分',
            'area' => '商品收获区',
            'uid' => '用户id',
            'createtime' => 'Createtime',
            'phone' => '商品领取人手机',
            'username' => '收货人',
            'province' => '收货省份',
            'city' => '收货城市',
            'detaaddress' => '收货详细地址',
            'getcode' => '领取码',
            'isdispose' => '是否处理   0：未处理   1：已处理',
        ];
    }
}
