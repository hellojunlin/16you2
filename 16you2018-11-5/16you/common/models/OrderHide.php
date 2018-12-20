<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_hide}}".
 *
 * @property integer $id
 * @property integer $gid
 * @property integer $pid
 * @property string $price
 * @property integer $createtime
 * @property string $transaction_id
 */
class OrderHide extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_hide}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gid', 'pid', 'createtime'], 'integer'],
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
            'id' => '隐藏的订单',
            'gid' => '游戏id',
            'pid' => '平台id',
            'price' => '隐藏的金额',
            'createtime' => '时间',
            'transaction_id' => '交易编号',
        ];
    }
}
