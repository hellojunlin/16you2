<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%newservice}}".
 *
 * @property integer $id
 * @property integer $gid
 * @property string $service_code
 * @property integer $open_time
 * @property integer $createtime
 * @property integer $state
 */
class Newservice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%newservice}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gid', 'service_code', 'open_time', 'createtime', 'state'], 'required'],
            [['gid', 'open_time', 'createtime', 'state'], 'integer'],
            [['service_code'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '新开服记录表',
            'gid' => '游戏id ',
            'service_code' => '区号',
            'open_time' => '开服时间',
            'createtime' => '创建时间',
            'state' => '启用禁用状态： 0：禁用，1：启用',
        ];
    }
}
