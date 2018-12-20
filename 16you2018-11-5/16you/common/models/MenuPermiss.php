<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%menu_permiss}}".
 *
 * @property integer $id
 * @property integer $m_id
 * @property string $permiss
 */
class MenuPermiss extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu_permiss}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['m_id', 'permiss'], 'required'],
            [['m_id'], 'integer'],
            [['permiss'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'm_id' => '菜单id',
            'permiss' => '权限名称',
        ];
    }
}
