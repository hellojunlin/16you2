<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%recycle}}".
 *
 * @property integer $id
 * @property integer $gid
 * @property integer $pid
 * @property string $gamename
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
class Recycle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%recycle}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gid', 'pid', 'count_time'], 'required'],
            [['gid', 'pid', 'play_user', 'pay_user', 'count_time', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh'], 'integer'],
            [['price', 'psecond', 'pthird', 'pfourth', 'pfifth', 'psixth', 'pseventh'], 'number'],
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
            'gid' => 'Gid',
            'pid' => 'Pid',
            'gamename' => 'Gamename',
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
