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
 * @property integer $is_subecribe
 * @property string $unionid
 * @property string $appopenid
 * @property integer $logintype
 * @property integer $currencynum
 * @property string $signrecode
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
            [['pid', 'username', 'sex', 'access_token'], 'required'],
            [['pid', 'sex', 'integral', 'createtime', 'Unique_ID', 'consult_id', 'vip', 'is_subecribe', 'logintype', 'currencynum'], 'integer'],
            [['gid', 'username', 'head_url', 'signrecode'], 'string'],
            [['openid', 'province', 'city', 'password', 'realname', 'IDcard', 'alipayname', 'alipayaccount', 'qq', 'birthday', 'unionid', 'appopenid'], 'string', 'max' => 50],
            [['phone'], 'string', 'max' => 11],
            [['access_token'], 'string', 'max' => 100],
            [['wxnumber'], 'string', 'max' => 200],
            [['Unique_ID'], 'unique'],
            [['IDcard'], 'unique'],
            [['alipayaccount'], 'unique'],
            [['unionid'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => '平台id',
            'gid' => '用户最近玩过的游戏[id:time]格式',
            'openid' => 'opneid',
            'username' => 'Username',
            'phone' => 'Phone',
            'sex' => '1男 2女 3未知',
            'head_url' => 'Head Url',
            'province' => 'Province',
            'city' => 'City',
            'integral' => '积分',
            'createtime' => 'Createtime',
            'access_token' => 'Access Token',
            'password' => '用户密码',
            'Unique_ID' => '用户的唯一ID',
            'consult_id' => '资讯最新id',
            'vip' => 'VIP等级',
            'realname' => '真实姓名',
            'IDcard' => '身份证',
            'alipayname' => '支付宝姓名',
            'alipayaccount' => '支付宝账号',
            'qq' => 'qq',
            'wxnumber' => '微信号',
            'birthday' => '生日',
            'is_subecribe' => '是否关注  0:未关注 1：已关注',
            'unionid' => 'unionid   微信各平台的唯一标识',
            'appopenid' => '移动端获取的openid',
            'logintype' => '登录类型:1：16游公众号授权登录   2：app登录',
            'currencynum' => '币值',
            'signrecode' => '签到记录',
        ];
    }
}
