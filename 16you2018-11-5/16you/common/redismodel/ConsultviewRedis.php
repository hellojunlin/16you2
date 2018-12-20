<?php
namespace common\redismodel;
/**
 * 公告点击记录 表
 * @author HE
 *
 */
class ConsultviewRedis extends \yii\redis\ActiveRecord{
	
	/**
     * @return array 此记录的属性列表
     * uid:用户id
     * createtime:创建时间
     */
    public function attributes()
    {
        return ['id', 'uid', 'createtime'];
    }

}