<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%continuorder}}".
 *
 * @property integer $id
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
class Continuorder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%continuorder}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'play_user', 'pay_user'], 'required'],
            [['pid', 'play_user', 'pay_user', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'count_time'], 'integer'],
            [['pay_price', 'secondprice', 'thirdprice', 'fourthprice', 'fifthprice', 'sixthprice', 'seventhprice'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
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
