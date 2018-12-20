<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%count}}".
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
class Count extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%count}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gid', 'pid', 'count_time', 'gamename', 'pay_probability'], 'required'],
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
            'id' => 'ID',
            'gid' => 'Gid',
            'pid' => 'Pid',
            'count_time' => 'Count Time',
            'gamename' => 'Gamename',
            'new_user' => 'New User',
            'play_user' => 'Play User',
            'pay_user' => 'Pay User',
            'pay_probability' => 'Pay Probability',
            'pay_sum' => 'Pay Sum',
            'ARPU' => 'Arpu',
            'ARPPU' => 'Arppu',
            'cuser' => 'Cuser',
            'cprice' => 'Cprice',
            'old_user' => 'Old User',
            'pay_num' => 'Pay Num',
        ];
    }
}
