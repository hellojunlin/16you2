<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%acquire}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $type
 * @property integer $num
 * @property integer $createtime
 */
class Acquire extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%acquire}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid'], 'required'],
            [['uid', 'type', 'num', 'createtime'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'type' => 'Type',
            'num' => 'Num',
            'createtime' => 'Createtime',
        ];
    }
}
