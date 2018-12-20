<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property integer $pid
 * @property string $gid
 * @property string $openid
 * @property string $username
 * @property string $phone
 * @property integer $sex
 * @property string $head_url
 * @property string $province
 * @property string $city
 * @property integer $integral
 * @property integer $createtime
 * @property string $access_token
 * @property string $password
 * @property integer $Unique_ID
 * @property integer $consult_id
 * @property integer $vip
 * @property string $realname
 * @property string $IDcard
 * @property string $alipayname
 * @property string $alipayaccount
 * @property string $qq
 * @property string $wxnumber
 * @property string $birthday
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'openid', 'username', 'sex', 'access_token'], 'required'],
            [['pid', 'sex', 'integral', 'createtime', 'Unique_ID', 'consult_id', 'vip'], 'integer'],
            [['gid', 'username', 'head_url'], 'string'],
            [['openid', 'province', 'city', 'password', 'realname', 'IDcard', 'alipayname', 'alipayaccount', 'qq', 'birthday'], 'string', 'max' => 50],
            [['phone'], 'string', 'max' => 11],
            [['access_token'], 'string', 'max' => 100],
            [['wxnumber'], 'string', 'max' => 200],
            [['Unique_ID'], 'unique'],
            [['IDcard'], 'unique'],
            [['alipayaccount'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'gid' => 'Gid',
            'openid' => 'Openid',
            'username' => 'Username',
            'phone' => 'Phone',
            'sex' => 'Sex',
            'head_url' => 'Head Url',
            'province' => 'Province',
            'city' => 'City',
            'integral' => 'Integral',
            'createtime' => 'Createtime',
            'access_token' => 'Access Token',
            'password' => 'Password',
            'Unique_ID' => 'Unique  ID',
            'consult_id' => 'Consult ID',
            'vip' => 'Vip',
            'realname' => 'Realname',
            'IDcard' => 'Idcard',
            'alipayname' => 'Alipayname',
            'alipayaccount' => 'Alipayaccount',
            'qq' => 'Qq',
            'wxnumber' => 'Wxnumber',
            'birthday' => 'Birthday',
        ];
    }
}
