<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%rebatecurrencytemp}}".
 *
 * @property integer $id
 * @property integer $oid
 * @property integer $aid
 * @property integer $uid
 * @property string $price
 * @property integer $rebatecurrency
 * @property integer $isdraw
 * @property integer $type
 * @property integer $createtime
 * @property integer $drawtime
 */
class Rebatecurrencytemp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rebatecurrencytemp}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['oid', 'uid', 'price', 'rebatecurrency', 'isdraw', 'type', 'createtime'], 'required'],
            [['oid', 'aid', 'uid', 'rebatecurrency', 'isdraw', 'type', 'createtime', 'drawtime'], 'integer'],
            [['price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '充值返利表',
            'oid' => 'Oid',
            'aid' => '活动名称： 1：五一活动',
            'uid' => '用户id',
            'price' => '充值金额',
            'rebatecurrency' => '返利游币值',
            'isdraw' => '是否领取 0：未领取  1：已领取',
            'type' => '福袋类型： 1：白银  2：黄金  3：铂金  4：砖石',
            'createtime' => '创建时间',
            'drawtime' => '领取时间',
        ];
    }
}
