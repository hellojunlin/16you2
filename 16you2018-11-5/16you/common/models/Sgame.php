<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%sgame}}".
 *
 * @property string $id
 * @property string $unique
 * @property string $name
 * @property string $descript
 * @property integer $state
 * @property integer $gamenum
 * @property string $game_url
 * @property integer $sort
 * @property string $head_img
 * @property integer $createtime
 */
class Sgame extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sgame}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unique', 'name', 'state', 'game_url', 'head_img', 'createtime'], 'required'],
            [['descript'], 'string'],
            [['state', 'gamenum', 'sort', 'createtime'], 'integer'],
            [['unique'], 'string', 'max' => 200],
            [['name'], 'string', 'max' => 100],
            [['game_url', 'head_img'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'unique' => '游戏唯一标识',
            'name' => 'Name',
            'descript' => 'Descript',
            'state' => '状态 0：禁用 1：启用',
            'gamenum' => '游戏人数',
            'game_url' => '游戏链接',
            'sort' => '排序，数值越大越靠前',
            'head_img' => '游戏logo',
            'createtime' => 'Createtime',
        ];
    }
}
