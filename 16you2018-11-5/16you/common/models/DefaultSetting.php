<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "g_default_setting".
 *
 * @property integer $id
 * @property string $gid
 * @property integer $cid
 * @property string $proportion
 * @property integer $createtime
 */
class DefaultSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'g_default_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gid'], 'string'],
            [['cid'], 'required'],
            [['cid', 'createtime'], 'integer'],
            [['proportion'], 'string', 'max' => 200],
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
            'cid' => 'Cid',
            'proportion' => 'Proportion',
            'createtime' => 'Createtime',
        ];
    }
}
