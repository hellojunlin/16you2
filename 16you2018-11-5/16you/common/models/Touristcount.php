<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%touristcount}}".
 *
 * @property integer $id
 * @property integer $gid
 * @property integer $pid
 * @property integer $count_time
 * @property string $gamename
 * @property integer $play_user
 * @property integer $pay_user
 * @property string $pay_probability
 * @property string $pay_sum
 * @property string $ARPU
 * @property string $ARPPU
 * @property integer $pay_num
 */
class Touristcount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%touristcount}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gid', 'pid', 'count_time', 'gamename', 'pay_probability'], 'required'],
            [['gid', 'pid', 'count_time', 'play_user', 'pay_user', 'pay_num'], 'integer'],
            [['pay_probability', 'pay_sum', 'ARPU', 'ARPPU'], 'number'],
            [['gamename'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '游客汇总统计表',
            'gid' => '游戏id',
            'pid' => '平台id',
            'count_time' => '统计日期',
            'gamename' => '游戏名称',
            'play_user' => '日活跃用户数 (激活数)',
            'pay_user' => '总付费人数',
            'pay_probability' => '付费率 （百分比的形式 %）',
            'pay_sum' => '总付费金额',
            'ARPU' => 'ARPU=充值总流水/日活跃用户数 (元)',
            'ARPPU' => 'ARPPU=即充值流水/付费用户数 （元）',
            'pay_num' => '付费次数',
        ];
    }
}
