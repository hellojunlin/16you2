<?php
namespace common\redismodel;
/**
 * 用户记录 表
 * @author He
 *
 */
class UserRedis extends \yii\redis\ActiveRecord{
	
	/**
     * @return array 此记录的属性列表
     * pid:平台id
     * gid:游戏id
     * openid:该用户的openid
     * username:用户名称
     * phone:电话号码
     * sex:性别
     * head_url:头型
     * province:省份
     * city:城市
     * integral:积分
     * access_token:
     * createtime:
     * Unique_ID:用户8位数唯一ID
     * password：用户登录pc端密码，MD5加密，默认为123456
     * consult_id 资讯id
     * vip vip等级
     * IDcard 身份证
     * realname 真实姓名
     * alipayname 支付宝姓名
     * alipayaccount 支付宝账号
     * qq
     * wxnumber 微信号
     * is_subecribe 是否关注
     * unionid 微信各平台的唯一标识
     * appopenid 移动端获取的openid
     * logintype 登录类型:1：16游公众号授权登录   2：app登录
     */
    public function attributes()
    {
        return ['id', 'pid', 'gid', 'openid', 'username', 'phone', 'sex', 'head_url', 'province', 'city', 'integral', 'access_token', 'createtime','Unique_ID','password','consult_id','vip','IDcard','realname','alipayname','alipayaccount','qq','wxnumber','is_subecribe','unionid','appopenid','logintype'];
    }
}