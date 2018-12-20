<?php
namespace common\redismodel;
/**
 * 咨询记录 表
 * @author He
 *
 */
class ConsultRedis extends \yii\redis\ActiveRecord{
	
	/**
     * @return array 此记录的属性列表
     * cid:数据库id
     * label:标签
     * title:标题
     * content:内容
     * state:状态 1启用 0禁用
     * createtime:兑换时间
     * type:置顶 0不置顶 1置顶
     * starttime:开始显示的时间 
     * sort:排序 从大到小排序
     */
    public function attributes()
    {
        return ['id', 'cid', 'gid', 'game_name', 'label', 'content', 'title', 'state', 'createtime','starttime','type','sort'];
    }
}