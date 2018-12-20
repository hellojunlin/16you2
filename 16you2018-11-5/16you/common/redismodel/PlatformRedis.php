<?php
namespace common\redismodel;
/**
 * 平台记录 表
 * @author HE
 *
 */
class PlatformRedis extends \yii\redis\ActiveRecord{
	
	/**
     * @return array 此记录的属性列表
     * `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
     *`punid` varchar(255) NOT NULL DEFAULT '' COMMENT '平台唯一识别id',
     *`cid` int(11) NOT NULL COMMENT '流量主id',
     *`pname` varchar(50) NOT NULL COMMENT '平台名称',
     *`state` tinyint(2) NOT NULL COMMENT '状态 0：禁用 1：启用',
     *`createtime` int(10) NOT NULL,
     *`start_img` varchar(50) DEFAULT NULL COMMENT '开始游戏的图片',
     *`code_img` varchar(50) DEFAULT NULL COMMENT '公众号图片',
     */
    public function attributes()
    {
        return ['id', 'punid', 'cid', 'pname','state', 'createtime', 'start_img','code_img'];
    }
}