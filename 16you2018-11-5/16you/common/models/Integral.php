<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "g_integral".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $integral
 * @property integer $uid
 * @property integer $createtime
 */
class Integral extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'g_integral';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'integral', 'uid'], 'required'],
            [['type', 'integral', 'uid', 'createtime'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'integral' => 'Integral',
            'uid' => 'Uid',
            'createtime' => 'Createtime',
        ];
    }
}
