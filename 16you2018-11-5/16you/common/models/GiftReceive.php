<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%gift_receive}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $gift_name
 * @property string $game_name
 * @property string $CDKEY
 * @property string $number
 * @property string $username
 * @property integer $receive_time
 */
class GiftReceive extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gift_receive}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'gift_name', 'game_name', 'CDKEY', 'number', 'username', 'receive_time'], 'required'],
            [['uid', 'receive_time'], 'integer'],
            [['gift_name'], 'string', 'max' => 200],
            [['game_name', 'CDKEY'], 'string', 'max' => 100],
            [['number', 'username'], 'string', 'max' => 50],
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
            'gift_name' => 'Gift Name',
            'game_name' => 'Game Name',
            'CDKEY' => 'Cdkey',
            'number' => 'Number',
            'username' => 'Username',
            'receive_time' => 'Receive Time',
        ];
    }
}
