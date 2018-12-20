<?php
namespace common\common;
use yii;
use common\common\Wxinutil;
/**
 * 签名
 * @author lin
 *
 */
class Getuserinfoutil {
	/**
	 * 获取70位随机数access_token
	 */
	public function getaccess_token(){
		$access_token = md5(uniqid().rand(10000,99999).uniqid().rand(10000,99999));
		return $access_token;
	}
	
	//获取sign
	public function getSign($access_token,$partnerid,$key) {
		$access_token = ($access_token) ? $access_token :'';
		$partnerid = ($partnerid) ?$partnerid :'';
		$key = ($key) ?$key :'';
		//初始待签名字符串
		$initstr =  'access_token='.$access_token.'&partnerid='.$partnerid;
		$string = $initstr.$key; //最终待签名
		$sign = sha1($string);
		return $sign;
	}
}
