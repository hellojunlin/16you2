<?php
namespace common\common;
use Yii;
use yii\log\Logger;

/**
 * 优赋支付工具类
 * @author HanksGump
 *
 */
class Unpayutil{
	/**
	 * 作用：获取签名
	 */
	public static function getSignMsg($obj,$key){
		$reqPar =   $obj.$key;
		$signature  = strtoupper(MD5($reqPar)); 
		return $signature;
	}
	/**
	 * curl方式获取数据
	 * @param unknown $url
	 * @param string $data
	 * @return mixed
	 */
	public static function https_request($url,$data=null){
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);//用PHP取回的URL地址
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);//禁用后cURL将终止从服务端进行验证
		if (defined('CURLOPT_SAFE_UPLOAD')) {
			curl_setopt($curl, CURLOPT_SAFE_UPLOAD, false);
		}
		if(!empty($data)){
			curl_setopt($curl,CURLOPT_POST,1);//如果你想PHP去做一个正规的HTTP POST，设置这个选项为一个非零值。这个POST是普通的 application/x-www-from-urlencoded 类型，多数被HTML表单使用
			curl_setopt($curl,CURLOPT_POSTFIELDS,$data);//传递一个作为HTTP “POST”操作的所有数据的字符串
		}
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);//设定是否显示头信息
		$output = curl_exec($curl);
		curl_close($curl);
		return $output;
	}
	
	/**
	 * curl get方式 获取数据
	 * @param unknown $url
	 * @return mixed
	 */
	public static function http_get($url){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);
		return $output;
	}
}