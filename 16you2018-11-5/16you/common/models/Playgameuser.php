<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%playgameuser}}".
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $uid
 * @property integer $gid
 * @property integer $state
 * @property integer $type
 * @property integer $createtime
 * @property integer $first_time
 */
class Playgameuser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%playgameuser}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'uid', 'gid', 'createtime'], 'required'],
            [['pid', 'uid', 'gid', 'state', 'type', 'createtime', 'first_time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'uid' => 'Uid',
            'gid' => 'Gid',
            'state' => 'State',
            'type' => 'Type',
            'createtime' => 'Createtime',
            'first_time' => 'First Time',
        ];
    }
}
