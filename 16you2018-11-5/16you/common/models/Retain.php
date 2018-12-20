<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%retain}}".
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $gid
 * @property integer $count_time
 * @property string $gamename
 * @property integer $play_user
 * @property integer $new_user
 * @property string $second
 * @property string $third
 * @property string $fourth
 * @property string $fifth
 * @property string $sixth
 * @property string $seventh
 */
class Retain extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%retain}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'gid', 'count_time', 'gamename', 'play_user', 'new_user'], 'required'],
            [['pid', 'gid', 'count_time', 'play_user', 'new_user'], 'integer'],
            [['gamename', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh'], 'string', 'max' => 50],
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
            'gid' => 'Gid',
            'count_time' => 'Count Time',
            'gamename' => 'Gamename',
            'play_user' => 'Play User',
            'new_user' => 'New User',
            'second' => 'Second',
            'third' => 'Third',
            'fourth' => 'Fourth',
            'fifth' => 'Fifth',
            'sixth' => 'Sixth',
            'seventh' => 'Seventh',
        ];
    }
}
