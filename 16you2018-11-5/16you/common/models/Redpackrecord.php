<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%redpackrecord}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $uid
 * @property string $money
 * @property integer $type
 * @property string $openid
 * @property integer $createtime
 */
class Redpackrecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%redpackrecord}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'uid', 'money', 'type', 'openid', 'createtime'], 'required'],
            [['uid', 'type', 'createtime'], 'integer'],
            [['money'], 'number'],
            [['title', 'openid'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '抢红包记录',
            'title' => '活动名称',
            'uid' => '抢红包用户id',
            'money' => '红包金额',
            'type' => '类型：1：10点红包  2:12点红包  3：19点红包  4：21点红包',
            'openid' => '用户openid',
            'createtime' => '创建时间',
        ];
    }
}
