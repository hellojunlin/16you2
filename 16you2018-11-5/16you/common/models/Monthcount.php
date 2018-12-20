<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%monthcount}}".
 *
 * @property integer $id
 * @property integer $gid
 * @property integer $pid
 * @property integer $count_time
 * @property string $gamename
 * @property integer $new_user
 * @property integer $play_user
 * @property integer $pay_user
 * @property string $pay_probability
 * @property string $pay_sum
 * @property string $ARPU
 * @property string $ARPPU
 * @property integer $cuser
 * @property string $cprice
 * @property integer $old_user
 * @property integer $pay_num
 */
class Monthcount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%monthcount}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gid', 'pid', 'count_time', 'gamename', 'new_user', 'pay_user', 'pay_probability', 'pay_sum', 'ARPU', 'ARPPU', 'cuser', 'cprice', 'old_user'], 'required'],
            [['gid', 'pid', 'count_time', 'new_user', 'play_user', 'pay_user', 'cuser', 'old_user', 'pay_num'], 'integer'],
            [['pay_probability', 'pay_sum', 'ARPU', 'ARPPU', 'cprice'], 'number'],
            [['gamename'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '月统计表',
            'gid' => '游戏id',
            'pid' => '平台id',
            'count_time' => '统计的日期',
            'gamename' => '游戏名称',
            'new_user' => '新增用户数',
            'play_user' => '日活跃用户数',
            'pay_user' => '付费用户数',
            'pay_probability' => '付费率 （百分比的形式 %）',
            'pay_sum' => '充值流水',
            'ARPU' => 'ARPU=充值总流水/日活跃用户数 (元)',
            'ARPPU' => 'ARPPU=即充值流水/付费用户数 （元）',
            'cuser' => '新增付费人数',
            'cprice' => '新增付费金额',
            'old_user' => '老用户活跃数',
            'pay_num' => '付费次数',
        ];
    }
}
