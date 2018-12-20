<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%plateform}}".
 *
 * @property string $id
 * @property string $punid
 * @property integer $cid
 * @property string $pname
 * @property integer $state
 * @property string $start_img
 * @property string $remark
 * @property integer $createtime
 * @property string $code_img
 * @property integer $sort
 */
class Plateform extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%plateform}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cid', 'pname', 'state', 'createtime'], 'required'],
            [['cid', 'state', 'createtime', 'sort'], 'integer'],
            [['punid'], 'string', 'max' => 255],
            [['pname', 'start_img', 'code_img'], 'string', 'max' => 50],
            [['remark'], 'string', 'max' => 200],
            [['punid'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'punid' => '平台唯一识别id',
            'cid' => '流量主id',
            'pname' => '平台名称',
            'state' => '状态 0：禁用 1：启用',
            'start_img' => '开始游戏的图片',
            'remark' => '备注',
            'createtime' => 'Createtime',
            'code_img' => '公众号图片',
            'sort' => '排序（数值越大越靠前）',
        ];
    }
}
