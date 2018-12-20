<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%auth_menu}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent
 * @property string $route
 * @property string $icon
 * @property string $param
 * @property integer $weight
 */
class AuthMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'parent'], 'required'],
            [['parent', 'weight'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['route', 'icon', 'param'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'parent' => 'Parent',
            'route' => 'Route',
            'icon' => 'Icon',
            'param' => 'Param',
            'weight' => 'Weight',
        ];
    }
}
