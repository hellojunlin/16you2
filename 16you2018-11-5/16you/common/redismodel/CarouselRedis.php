<?php
namespace common\redismodel;
/**
 * 轮播记录 表
 * @author He
 *
 */
class CarouselRedis extends \yii\redis\ActiveRecord{
	
	/**
     * @return array 此记录的属性列表
     * url:轮播链接
     * label:轮播图片 (/backend/web/images/carousel/)
     * createtime:
     * state:状态 1首页 2商城 3弃用
     * sort:排序
     */
    public function attributes()
    {
        return ['id', 'url', 'image', 'createtime', 'state', 'sort'];
    }

    /**
     * @return ActiveQuery 定义一个关联到 Order 的记录（可以在其它数据库中，例如 elasticsearch 或者 sql）
     
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['customer_id' => 'id']);
    }
	*/
}