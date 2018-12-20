<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%refund}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $Unique_ID
 * @property string $username
 * @property integer $refund_time
 * @property string $transaction_id
 * @property string $gamename
 * @property integer $gid
 * @property integer $createtime
 * @property integer $num
 * @property string $price
 */
class Refund extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%refund}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'username', 'refund_time', 'transaction_id'], 'required'],
            [['uid', 'Unique_ID', 'refund_time', 'gid', 'createtime', 'num'], 'integer'],
            [['price'], 'number'],
            [['username', 'transaction_id'], 'string', 'max' => 50],
            [['gamename'], 'string', 'max' => 200],
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
            'Unique_ID' => 'Unique  ID',
            'username' => 'Username',
            'refund_time' => 'Refund Time',
            'transaction_id' => 'Transaction ID',
            'gamename' => 'Gamename',
            'gid' => 'Gid',
            'createtime' => 'Createtime',
            'num' => 'Num',
            'price' => 'Price',
        ];
    }
}
