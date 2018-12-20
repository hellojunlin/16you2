<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%discount}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $discount
 * @property integer $mondaytime
 * @property integer $createtime
 */
class Discount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%discount}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'discount', 'mondaytime', 'createtime'], 'required'],
            [['uid', 'discount', 'mondaytime', 'createtime'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '折扣表',
            'uid' => '用户id',
            'discount' => '当周折扣',
            'mondaytime' => '当周星期一的时间戳',
            'createtime' => '创建时间',
        ];
    }
}
