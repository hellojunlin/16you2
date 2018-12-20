<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%wxkeyword}}".
 *
 * @property integer $id
 * @property string $wxappid
 * @property string $keyword
 * @property string $content
 * @property string $filename
 * @property integer $type
 * @property integer $sort
 * @property integer $createtime
 */
class Wxkeyword extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wxkeyword}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wxappid'], 'required'],
            [['content'], 'string'],
            [['type', 'sort', 'createtime'], 'integer'],
            [['wxappid', 'keyword'], 'string', 'max' => 50],
            [['filename'], 'string', 'max' => 255],
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
            'keyword' => 'Keyword',
            'content' => 'Content',
            'filename' => 'Filename',
            'type' => 'Type',
            'sort' => 'Sort',
            'createtime' => 'Createtime',
        ];
    }
}
