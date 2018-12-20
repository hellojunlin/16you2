<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%carousel}}".
 *
 * @property integer $id
 * @property string $url
 * @property string $image
 * @property integer $state
 * @property integer $sort
 * @property string $remark
 * @property integer $createtime
 */
class Carousel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%carousel}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'image'], 'required'],
            [['state', 'sort', 'createtime'], 'integer'],
            [['url', 'image'], 'string', 'max' => 200],
            [['remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'image' => 'Image',
            'state' => 'State',
            'sort' => 'Sort',
            'remark' => 'Remark',
            'createtime' => 'Createtime',
        ];
    }
}
