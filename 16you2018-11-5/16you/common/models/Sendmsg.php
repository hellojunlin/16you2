<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%sendmsg}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $t_id
 * @property string $data
 * @property string $url
 * @property integer $createtime
 */
class Sendmsg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sendmsg}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['t_id'], 'required'],
            [['data'], 'string'],
            [['createtime'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['t_id'], 'string', 'max' => 200],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '模板消息',
            'title' => '标题（不传给微信，只做识别）',
            't_id' => '模板id',
            'data' => '传给微信数据',
            'url' => '跳转链接',
            'createtime' => 'Createtime',
        ];
    }
}
