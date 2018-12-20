<?php
namespace common\redismodel;
/**
 * 游戏记录 表
 * @author Junlin
 *
 */
class PlaygameuserRedis extends \yii\redis\ActiveRecord{
	
	/**
     * @return array 此记录的属性列表
     * id:游戏id
     * pid:游戏平台id
     * uid:用户id
     * gid:游戏id
     * state：1：当天第一次玩，2：当天已玩过
     * createtime:创建时间
     */
    public function attributes()
    {
        return ['id', 'pid', 'uid', 'gid','type', 'first_time', 'first_playtime','state', 'createtime'];
    }
}