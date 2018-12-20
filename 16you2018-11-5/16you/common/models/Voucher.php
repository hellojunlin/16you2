<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%voucher}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $price
 * @property integer $discount
 * @property integer $currencynum
 * @property integer $state
 * @property integer $pid
 * @property integer $type
 * @property string $transaction_id
 * @property integer $ptype
 * @property integer $logintype
 * @property integer $payclient
 * @property integer $vtype
 * @property integer $createtime
 */
class Voucher extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%voucher}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'price', 'discount', 'currencynum', 'pid', 'transaction_id', 'createtime'], 'required'],
            [['uid', 'discount', 'currencynum', 'state', 'pid', 'type', 'ptype', 'logintype', 'payclient', 'vtype', 'createtime'], 'integer'],
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
            'id' => '代金券',
            'uid' => '用户id',
            'price' => '支付金额',
            'discount' => '折扣',
            'currencynum' => '游币数量',
            'state' => '付款状态 1待付款 2付款成功 3退款中 4已退款 5付款失败',
            'pid' => '平台id',
            'type' => '支付后游戏方返回 0未处理 1通知成功 2通知失败',
            'transaction_id' => '交易编号',
            'ptype' => '支付类型：1:微信支付  2：盛付通微信支付 3：盛付通支付宝支付 4：盛付通网银支付  5：盛付通H5快捷支付  6：微信扫码支付',
            'logintype' => '登录方式:1：会员 2：游客  ',
            'payclient' => '支付端：1微信  2：pc端  3 app端',
            'vtype' => '代金券类型：1:5元   2:15元  3:25元  4:35元   5:40元  6:50元',
            'createtime' => 'Createtime',
        ];
    }
}
