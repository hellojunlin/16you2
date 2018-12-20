<?php
namespace common\redismodel;
/**
 * 游戏记录 表
 * @author Junlin
 *
 */
class SgameRedis extends \yii\redis\ActiveRecord{
	
	/**
     * @return array 此记录的属性列表
     * id:游戏id
     * unique:游戏唯一标识
     * name:游戏名称
     * descript:游戏描述
     * cid：所属的游戏公司
     * state:状态 0：禁用 1：启用
     * gamenum:游戏人数
     * intro:简介
     * game_url:游戏链接
     * type:游戏类别
     * image：游戏图片
     * sort：排序
     * game_type：游戏类型
     * head_img：游戏logo
     * createtime:创建时间
     * is_newgame 是否为新游
     */
    public function attributes()
    {
        return ['id', 'unique', 'name', 'descript', 'cid', 'state', 'gamenum', 'intro', 'game_url', 'type', 'image','sort','game_type','head_img','createtime','is_newgame'];
    }

}