<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%crecycle}}".
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $play_user
 * @property integer $pay_user
 * @property integer $count_time
 * @property integer $second
 * @property integer $third
 * @property integer $fourth
 * @property integer $fifth
 * @property integer $sixth
 * @property integer $seventh
 * @property string $price
 * @property string $psecond
 * @property string $pthird
 * @property string $pfourth
 * @property string $pfifth
 * @property string $psixth
 * @property string $pseventh
 */
class Crecycle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%crecycle}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'count_time'], 'required'],
            [['pid', 'play_user', 'pay_user', 'count_time', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh'], 'integer'],
            [['price', 'psecond', 'pthird', 'pfourth', 'pfifth', 'psixth', 'pseventh'], 'number'],
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
            'count_time' => 'Count Time',
            'second' => 'Second',
            'third' => 'Third',
            'fourth' => 'Fourth',
            'fifth' => 'Fifth',
            'sixth' => 'Sixth',
            'seventh' => 'Seventh',
            'price' => 'Price',
            'psecond' => 'Psecond',
            'pthird' => 'Pthird',
            'pfourth' => 'Pfourth',
            'pfifth' => 'Pfifth',
            'psixth' => 'Psixth',
            'pseventh' => 'Pseventh',
        ];
    }
}
