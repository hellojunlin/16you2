<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%touristplaygameuser}}".
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $uid
 * @property integer $gid
 * @property integer $state
 * @property integer $type
 * @property integer $createtime
 * @property integer $first_time
 * @property integer $first_playtime
 * @property integer $client_type
 */
class Touristplaygameuser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%touristplaygameuser}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'uid', 'gid'], 'required'],
            [['pid', 'uid', 'gid', 'state', 'type', 'createtime', 'first_time', 'first_playtime', 'client_type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '游客玩游戏记录表',
            'pid' => '游戏平台id',
            'uid' => '用户id',
            'gid' => '游戏id',
            'state' => '1:该用户第一次玩  2：该用户已玩过',
            'type' => '1:该款游戏第一次玩 2：该款游戏已玩过',
            'createtime' => '第一次玩游戏的时间',
            'first_time' => '第一次玩游戏的时间',
            'first_playtime' => '一个用户玩某款游戏',
            'client_type' => '访问类型：1：pc端  2：app端',
        ];
    }
}
