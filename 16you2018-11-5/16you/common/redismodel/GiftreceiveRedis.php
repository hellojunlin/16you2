<?php
namespace common\redismodel;
/**
 * 礼包领取记录 表
 * @author He
 *
 */
class GiftreceiveRedis extends \yii\redis\ActiveRecord{
	
	/**
     * @return array 此记录的属性列表
     * gid:游戏id
     * uid:用户id
     * username:用户名称
     * gift_name:礼包名称
     * game_name：所属游戏名称
     * CDKEY:礼包激活码
     * number:批次 （每批礼包的唯一标识）
     * receive_time:领取时间
     * game_image:游戏图片
     * content:礼包详情
     * payment:领取方式
     */
    public function attributes()
    {
        return ['id', 'gid', 'uid', 'username', 'CDKEY', 'gift_name', 'game_name', 'number', 'receive_time', 'game_image', 'content','payment'];
    }

    /**
     * @return ActiveQuery 定义一个关联到 Order 的记录（可以在其它数据库中，例如 elasticsearch 或者 sql）
     
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['customer_id' => 'id']);
    }
	*/
}