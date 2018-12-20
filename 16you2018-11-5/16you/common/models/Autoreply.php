<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%autoreply}}".
 *
 * @property integer $id
 * @property string $wxappid
 * @property string $content
 * @property string $filename
 * @property integer $type
 * @property integer $sort
 * @property integer $state
 * @property integer $createtime
 */
class Autoreply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%autoreply}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wxappid'], 'required'],
            [['content'], 'string'],
            [['type', 'sort', 'state', 'createtime'], 'integer'],
            [['wxappid', 'filename'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'wxappid' => 'Wxappid',
            'content' => 'Content',
            'filename' => 'Filename',
            'type' => 'Type',
            'sort' => 'Sort',
            'state' => 'State',
            'createtime' => 'Createtime',
        ];
    }
}
