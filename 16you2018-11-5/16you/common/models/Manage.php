<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%manage}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property integer $state
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $role
 * @property integer $g_p_id
 * @property integer $type
 * @property string $remark
 */
class Manage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%manage}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'created_at', 'g_p_id', 'type'], 'required'],
            [['state', 'created_at', 'updated_at', 'g_p_id', 'type'], 'integer'],
            [['username', 'password', 'remark'], 'string', 'max' => 255],
            [['role'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'state' => 'State',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'role' => 'Role',
            'g_p_id' => 'G P ID',
            'type' => 'Type',
            'remark' => 'Remark',
        ];
    }
}
