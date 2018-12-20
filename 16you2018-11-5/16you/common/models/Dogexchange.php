<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%dogexchange}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $price
 * @property integer $num
 * @property integer $state
 * @property integer $createtime
 */
class Dogexchange extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dogexchange}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'price', 'num'], 'required'],
            [['uid', 'num', 'state', 'createtime'], 'integer'],
            [['price'], 'number'],
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
            'price' => 'Price',
            'num' => 'Num',
            'state' => 'State',
            'createtime' => 'Createtime',
        ];
    }
}
