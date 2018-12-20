<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property string $id
 * @property integer $gid
 * @property integer $uid
 * @property string $propname
 * @property string $price
 * @property integer $state
 * @property integer $num
 * @property integer $createtime
 * @property integer $pid
 * @property string $orderID
 * @property string $detail
 * @property string $attach
 * @property integer $type
 * @property string $product_id
 * @property string $transaction_id
 * @property integer $utype
 * @property integer $gtype
 * @property integer $first_time
 * @property integer $gfirst_time
 * @property string $districtID
 * @property integer $is_hide
 * @property string $realname
 * @property string $phone
 * @property integer $ptype
 * @property string $instcode
 * @property integer $logintype
 * @property integer $payclient
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gid', 'uid', 'propname', 'price', 'createtime', 'pid', 'orderID', 'product_id', 'transaction_id'], 'required'],
            [['gid', 'uid', 'state', 'num', 'createtime', 'pid', 'type', 'utype', 'gtype', 'first_time', 'gfirst_time', 'is_hide', 'ptype', 'logintype', 'payclient'], 'integer'],
            [['price'], 'number'],
            [['detail', 'attach'], 'string'],
            [['propname'], 'string', 'max' => 255],
            [['orderID', 'product_id'], 'string', 'max' => 200],
            [['transaction_id', 'realname', 'instcode'], 'string', 'max' => 50],
            [['districtID'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gid' => '游戏id',
            'uid' => '用户id',
            'propname' => '道具名',
            'price' => '价格',
            'state' => '付款状态 1待付款 2付款成功 3退款中 4已退款 5付款失败',
            'num' => 'Num',
            'createtime' => 'Createtime',
            'pid' => '平台id',
            'orderID' => '厂商订单号',
            'detail' => '订单后商品的详情',
            'attach' => '附加数据，后台通知时原样返回',
            'type' => '支付后游戏方返回 0未处理 1通知成功 2通知失败',
            'product_id' => '商品id',
            'transaction_id' => '交易编号',
            'utype' => '判断是否为新付款用户 1否 2是',
            'gtype' => '判断该用户是否为游戏新付款用户 1否 2是',
            'first_time' => '用户第一次付款时间',
            'gfirst_time' => '该游戏用户第一次付款时间',
            'districtID' => '区服ID',
            'is_hide' => '是否隐藏 1不隐藏 2隐藏',
            'realname' => '真实姓名',
            'phone' => '手机号码',
            'ptype' => '支付类型：1:微信支付  2：盛付通微信支付 3：盛付通支付宝支付 4：盛付通网银支付  5：盛付通H5快捷支付  6：微信扫码支付',
            'instcode' => '银行编码',
            'logintype' => '登录方式:1：会员 2：游客  ',
            'payclient' => '支付端：1微信  2：pc端  3 app端',
        ];
    }
}
