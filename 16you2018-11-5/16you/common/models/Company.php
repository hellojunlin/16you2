<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%company}}".
 *
 * @property string $id
 * @property string $compname
 * @property string $linkman
 * @property string $phone
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $address
 * @property integer $state
 * @property string $role
 * @property integer $createtime
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%company}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['compname', 'linkman', 'phone', 'role', 'createtime'], 'required'],
            [['state', 'createtime'], 'integer'],
            [['compname'], 'string', 'max' => 100],
            [['linkman', 'province', 'city', 'area'], 'string', 'max' => 50],
            [['phone'], 'string', 'max' => 11],
            [['address'], 'string', 'max' => 255],
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
            'compname' => 'Compname',
            'linkman' => 'Linkman',
            'phone' => 'Phone',
            'province' => 'Province',
            'city' => 'City',
            'area' => 'Area',
            'address' => 'Address',
            'state' => 'State',
            'role' => 'Role',
            'createtime' => 'Createtime',
        ];
    }
}
