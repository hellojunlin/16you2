<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_test}}".
 *
 * @property integer $id
 * @property integer $gid
 * @property integer $uid
 * @property integer $pid
 * @property integer $oid
 * @property string $price
 * @property string $transaction_id
 * @property integer $hidetime
 * @property integer $createtime
 */
class OrderTest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_test}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gid', 'uid', 'pid', 'oid', 'price', 'transaction_id', 'hidetime', 'createtime'], 'required'],
            [['gid', 'uid', 'pid', 'oid', 'hidetime', 'createtime'], 'integer'],
            [['price'], 'number'],
            [['transaction_id'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '订单隐藏表',
            'gid' => '游戏ID',
            'uid' => '用户ID',
            'pid' => 'Pid',
            'oid' => 'Oid',
            'price' => 'Price',
            'transaction_id' => 'Transaction ID',
            'hidetime' => 'Hidetime',
            'createtime' => 'Createtime',
        ];
    }
}
