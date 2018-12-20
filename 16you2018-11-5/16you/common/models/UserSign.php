<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user_sign}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $continuous
 * @property integer $endtime
 * @property integer $createtime
 */
class UserSign extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_sign}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid'], 'required'],
            [['uid', 'continuous', 'endtime', 'createtime'], 'integer'],
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
            'continuous' => 'Continuous',
            'endtime' => 'Endtime',
            'createtime' => 'Createtime',
        ];
    }
}
