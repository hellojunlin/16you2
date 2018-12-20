<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%email}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $title
 * @property string $content
 * @property integer $state
 * @property string $gift_content
 * @property string $gift_number
 * @property string $uniqid
 * @property integer $type
 * @property integer $createtime
 */
class Email extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%email}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'title'], 'required'],
            [['uid', 'state', 'type', 'createtime'], 'integer'],
            [['content'], 'string'],
            [['title', 'uniqid'], 'string', 'max' => 50],
            [['gift_content'], 'string', 'max' => 200],
            [['gift_number'], 'string', 'max' => 100],
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
            'title' => 'Title',
            'content' => 'Content',
            'state' => 'State',
            'gift_content' => 'Gift Content',
            'gift_number' => 'Gift Number',
            'uniqid' => 'Uniqid',
            'type' => 'Type',
            'createtime' => 'Createtime',
        ];
    }
}
