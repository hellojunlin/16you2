<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%gamecurrency}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $state
 * @property integer $currencynum
 * @property integer $createtime
 * @property integer $checkcreatetime
 * @property string $remark
 * @property integer $source
 */
class Gamecurrency extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gamecurrency}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'createtime'], 'required'],
            [['uid', 'state', 'currencynum', 'createtime', 'checkcreatetime', 'source'], 'integer'],
            [['remark'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '游币记录表',
            'uid' => '接收游币用户',
            'state' => '状态 （0：未审核   1：审核通过）',
            'currencynum' => '币值',
            'createtime' => '创建时间',
            'checkcreatetime' => '审核通过时间',
            'remark' => '备注',
            'source' => '来源：0:后台返利   1：五一活动',
        ];
    }
}
