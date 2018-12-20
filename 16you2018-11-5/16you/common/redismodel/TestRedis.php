<?php
namespace common\redismodel;
/**
 * 测试记录 表
 * @author He
 *
 */
class TestRedis extends \yii\redis\ActiveRecord{
	
	/**
     * @return array 此记录的属性列表
     * username:用户名称
     * phone:电话号码
     * sex:性别
     * createtime:
     */
    public function attributes()
    {
        return ['id', 'username', 'phone', 'sex','createtime'];
    }

    /**
     * @return ActiveQuery 定义一个关联到 Order 的记录（可以在其它数据库中，例如 elasticsearch 或者 sql）
     
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['customer_id' => 'id']);
    }
	*/
}