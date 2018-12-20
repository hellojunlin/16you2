<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%configuration}}".
 *
 * @property integer $id
 * @property integer $gid
 * @property string $key
 * @property string $type_url
 * @property string $partnerid
 * @property string $api_url
 * @property integer $createtime
 */
class Configuration extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%configuration}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gid', 'key', 'type_url', 'partnerid', 'api_url'], 'required'],
            [['gid', 'createtime'], 'integer'],
            [['type_url', 'api_url'], 'string'],
            [['key'], 'string', 'max' => 50],
            [['partnerid'], 'string', 'max' => 100],
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
            'key' => 'Key',
            'type_url' => 'Type Url',
            'partnerid' => 'Partnerid',
            'api_url' => 'Api Url',
            'createtime' => 'Createtime',
        ];
    }
}
