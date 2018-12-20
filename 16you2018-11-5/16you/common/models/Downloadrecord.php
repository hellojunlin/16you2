<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%downloadrecord}}".
 *
 * @property integer $id
 * @property integer $createtime
 */
class Downloadrecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%downloadrecord}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['createtime'], 'required'],
            [['createtime'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '下载记录表',
            'createtime' => '下载记录',
        ];
    }
}
