<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%cretain}}".
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $count_time
 * @property integer $play_user
 * @property integer $new_user
 * @property string $second
 * @property string $third
 * @property string $fourth
 * @property string $fifth
 * @property string $sixth
 * @property string $seventh
 */
class Cretain extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cretain}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'count_time', 'play_user', 'new_user'], 'required'],
            [['pid', 'count_time', 'play_user', 'new_user'], 'integer'],
            [['second', 'third', 'fourth', 'fifth', 'sixth', 'seventh'], 'string', 'max' => 50],
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
            'count_time' => 'Count Time',
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
