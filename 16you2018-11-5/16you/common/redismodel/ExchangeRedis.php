<?php
namespace common\redismodel;
/**
 * 兑换记录 表
 * @author He
 *
 */
class ExchangeRedis extends \yii\redis\ActiveRecord{
	
	/**
     * @return array 此记录的属性列表
     * product_name:商品名称
     * integral:兑换所需积分
     * area:商品填写的收货地址
     * uid:用户id
     * username:用户名称
     * phone:商品领取人手机
     * createtime:兑换时间
     * username:用户名称
     */
    public function attributes()
    {
        return ['id', 'product_name', 'integral', 'area', 'uid', 'username', 'phone', 'createtime', 'username'];
    }

    /**
     * @return ActiveQuery 定义一个关联到 Order 的记录（可以在其它数据库中，例如 elasticsearch 或者 sql）
     
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['customer_id' => 'id']);
    }
	*/
}