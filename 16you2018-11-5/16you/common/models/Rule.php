<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%rule}}".
 *
 * @property integer $id
 * @property integer $state
 * @property integer $type
 * @property string $content
 * @property integer $createtime
 * @property integer $starttime
 * @property integer $endtime
 */
class Rule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rule}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['state', 'type', 'createtime', 'starttime', 'endtime'], 'integer'],
            [['content'], 'required'],
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'state' => 'State',
            'type' => 'Type',
            'content' => 'Content',
            'createtime' => 'Createtime',
            'starttime' => 'Starttime',
            'endtime' => 'Endtime',
        ];
    }
}
