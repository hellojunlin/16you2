<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "g_setting_proportion".
 *
 * @property integer $id
 * @property string $gid
 * @property integer $cid
 * @property string $proportion
 * @property integer $effective_time
 * @property integer $createtime
 */
class SettingProportion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'g_setting_proportion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gid'], 'string'],
            [['cid', 'effective_time'], 'required'],
            [['cid', 'effective_time', 'createtime'], 'integer'],
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
            'effective_time' => 'Effective Time',
            'createtime' => 'Createtime',
        ];
    }
}
