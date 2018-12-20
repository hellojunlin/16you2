<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%game_plate}}".
 *
 * @property integer $id
 * @property integer $gid
 * @property integer $pid
 */
class GamePlate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%game_plate}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gid', 'pid'], 'required'],
            [['gid', 'pid'], 'integer'],
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
        ];
    }
}
