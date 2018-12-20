<?php
namespace common\redismodel;
/**
 * 微信分享记录 表
 * @author He
 *
 */
class WxshareRedis extends \yii\redis\ActiveRecord{
	
	/**
     * @return array 此记录的属性列表
     `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '微信分享',
  `gid` int(11) NOT NULL COMMENT '游戏id',
  `gamename` varchar(100) DEFAULT NULL COMMENT '游戏名称',
  `title` varchar(200) DEFAULT NULL COMMENT '分享的标题',
  `desc` varchar(200) DEFAULT NULL COMMENT '分享的描述',
  `link` text COMMENT '分享的链接',
  `createtime` int(10) DEFAULT NULL,
     */
    public function attributes()
    {
        return ['id', 'gid', 'gamename', 'title', 'desc', 'link','createtime'];
    }

    /**
     * @return ActiveQuery 定义一个关联到 Order 的记录（可以在其它数据库中，例如 elasticsearch 或者 sql）
     
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['customer_id' => 'id']);
    }
	*/
}