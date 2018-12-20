<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%daydownload}}".
 *
 * @property integer $id
 * @property integer $createtime
 * @property integer $num
 * @property integer $pay_num
 * @property string $pay_price
 */
class Daydownload extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%daydownload}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['createtime', 'num'], 'required'],
            [['createtime', 'num', 'pay_num'], 'integer'],
            [['pay_price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '每日下载统计',
            'createtime' => 'Createtime',
            'num' => '下载的次数',
            'pay_num' => '付费人数',
            'pay_price' => '付费金额',
        ];
    }
}
