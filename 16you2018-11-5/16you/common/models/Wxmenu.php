<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%wxmenu}}".
 *
 * @property integer $id
 * @property string $wxappid
 * @property string $content
 * @property integer $createtime
 */
class Wxmenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wxmenu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wxappid', 'content'], 'required'],
            [['content'], 'string'],
            [['createtime'], 'integer'],
            [['wxappid'], 'string', 'max' => 50],
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
            'createtime' => 'Createtime',
        ];
    }
}
