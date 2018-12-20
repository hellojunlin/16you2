<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%consult}}".
 *
 * @property integer $id
 * @property string $label
 * @property string $title
 * @property string $content
 * @property integer $state
 * @property integer $createtime
 * @property integer $gid
 * @property integer $sort
 * @property integer $starttime
 * @property integer $type
 */
class Consult extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%consult}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label', 'title', 'content', 'gid'], 'required'],
            [['content'], 'string'],
            [['state', 'createtime', 'gid', 'sort', 'starttime', 'type'], 'integer'],
            [['label', 'title'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => 'Label',
            'title' => 'Title',
            'content' => 'Content',
            'state' => 'State',
            'createtime' => 'Createtime',
            'gid' => 'Gid',
            'sort' => 'Sort',
            'starttime' => 'Starttime',
            'type' => 'Type',
        ];
    }
}
