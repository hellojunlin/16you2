<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%manage}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $head_img
 * @property integer $state
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $role
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
            [['username', 'password', 'created_at'], 'required'],
            [['state', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password'], 'string', 'max' => 255],
            [['head_img'], 'string', 'max' => 100],
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
            'head_img' => 'Head Img',
            'state' => 'State',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'role' => 'Role',
        ];
    }
}
