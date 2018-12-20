<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%decontinuorder}}".
 *
 * @property integer $id
 * @property string $gamename
 * @property integer $gid
 * @property integer $pid
 * @property integer $play_user
 * @property integer $pay_user
 * @property integer $second
 * @property integer $third
 * @property integer $fourth
 * @property integer $fifth
 * @property integer $sixth
 * @property integer $seventh
 * @property string $pay_price
 * @property string $secondprice
 * @property string $thirdprice
 * @property string $fourthprice
 * @property string $fifthprice
 * @property string $sixthprice
 * @property string $seventhprice
 * @property integer $count_time
 */
class Decontinuorder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%decontinuorder}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gamename', 'gid', 'pid', 'play_user', 'pay_user'], 'required'],
            [['gid', 'pid', 'play_user', 'pay_user', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'count_time'], 'integer'],
            [['pay_price', 'secondprice', 'thirdprice', 'fourthprice', 'fifthprice', 'sixthprice', 'seventhprice'], 'number'],
            [['gamename'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gamename' => 'Gamename',
            'gid' => 'Gid',
            'pid' => 'Pid',
            'play_user' => 'Play User',
            'pay_user' => 'Pay User',
            'second' => 'Second',
            'third' => 'Third',
            'fourth' => 'Fourth',
            'fifth' => 'Fifth',
            'sixth' => 'Sixth',
            'seventh' => 'Seventh',
            'pay_price' => 'Pay Price',
            'secondprice' => 'Secondprice',
            'thirdprice' => 'Thirdprice',
            'fourthprice' => 'Fourthprice',
            'fifthprice' => 'Fifthprice',
            'sixthprice' => 'Sixthprice',
            'seventhprice' => 'Seventhprice',
            'count_time' => 'Count Time',
        ];
    }
}
