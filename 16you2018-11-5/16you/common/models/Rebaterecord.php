<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%rebaterecord}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $rebatenum
 * @property integer $state
 * @property integer $createtime
 * @property string $remark
 * @property integer $passtime
 * @property string $ordernum
 */
class Rebaterecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rebaterecord}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'createtime'], 'required'],
            [['uid', 'state', 'createtime', 'passtime'], 'integer'],
            [['rebatenum'], 'number'],
            [['remark'], 'string', 'max' => 100],
            [['ordernum'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '返利记录表',
            'uid' => '返利的用户id',
            'rebatenum' => '返利金额',
            'state' => '状态 （0：未审核   1：审核通过）',
            'createtime' => '创建时间',
            'remark' => '备注',
            'passtime' => '审核通过时间',
            'ordernum' => '订单号',
        ];
    }
}
