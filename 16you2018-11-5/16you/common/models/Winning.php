<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%winning}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $content
 * @property string $openid
 * @property integer $type
 * @property integer $createtime
 */
class Winning extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%winning}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'content'], 'required'],
            [['uid', 'type', 'createtime'], 'integer'],
            [['content','openid'], 'string', 'max' => 200],
        ];
    }
    

    public function getUser(){
    	return $this->hasOne(User::className(), ['id'=>'uid']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'content' => 'Content',
            'openid' => 'Openid',
            'type' => 'Type',
            'createtime' => 'Createtime',
        ];
    }
}
