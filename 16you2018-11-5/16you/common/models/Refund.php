<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%refund}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $Unique_ID
 * @property string $username
 * @property integer $refund_time
 * @property string $transaction_id
 * @property string $gamename
 * @property integer $gid
 * @property integer $createtime
 * @property integer $num
 * @property string $price
 * @property integer $state
 * @property string $refundorderno
 */
class Refund extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%refund}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'username', 'refund_time', 'transaction_id'], 'required'],
            [['uid', 'Unique_ID', 'refund_time', 'gid', 'createtime', 'num', 'state'], 'integer'],
            [['price'], 'number'],
            [['username', 'transaction_id', 'refundorderno'], 'string', 'max' => 50],
            [['gamename'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '微信支付退款',
            'uid' => '用户id',
            'Unique_ID' => '用户id',
            'username' => '用户名',
            'refund_time' => '退款时间',
            'transaction_id' => '交易编号',
            'gamename' => '游戏名称',
            'gid' => '游戏id',
            'createtime' => '下单时间',
            'num' => '数量',
            'price' => '价格',
            'state' => '退款状态 1:正退款中，2：退款成功并回调',
            'refundorderno' => '退款订单号',
        ];
    }
}
