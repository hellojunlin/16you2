<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%share}}".
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $uid
 * @property integer $createtime
 */
class Share extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%share}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'uid'], 'required'],
            [['pid', 'uid', 'createtime'], 'integer'],
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
            'createtime' => 'Createtime',
        ];
    }
}
