<?php
namespace common\common;
use Yii;
use yii\log\Logger;

/**
 * 盛付通支付工具类
 * @author HanksGump
 *
 */
class Sftpayutil{
	/**
	 * curl post 数据
	 * $requesturl  请求的链接
	 * $data    发送的数据
	 * $headers   header信息
	 * 
	 */
	public function curlPost($requesturl,$data,$headers){
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL,$requesturl);//用PHP取回的URL地址
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);//禁用后cURL将终止从服务端进行验证
		if (defined('CURLOPT_SAFE_UPLOAD')) {
			curl_setopt($curl, CURLOPT_SAFE_UPLOAD, false);
		}
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);//设定是否显示头信息
		if(!empty($data)){
			curl_setopt($curl,CURLOPT_POST,1);//如果你想PHP去做一个正规的HTTP POST，设置这个选项为一个非零值。这个POST是普通的 application/x-www-from-urlencoded 类型，多数被HTML表单使用
			curl_setopt($curl,CURLOPT_POSTFIELDS,$data);//传递一个作为HTTP “POST”操作的所有数据的字符串
		}
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_HTTPHEADER,$headers);
		$output = curl_exec($curl);
		curl_close($curl);
		if($output){
			$res = json_decode($output,true);
			if(isset($res['returnCode'])&& $res['returnCode']=='00'){
				return  json_encode([
						'errorcode'=>'0',
						'payUrl'=>$res['payUrl'],
						]);
			}else{
				//var_dump($res);exit;
				return  json_encode([
						'errorcode'=>'1001'
						]);
			}
		}else{
			return  json_encode([
					'errorcode'=>'1001'
			]);
		}
	}
	
	/**
	 * 作用：获取微信扫码扩展
	 */
	public function getExt(){
		$extarr = array();
		$extarr['requestFrom'] = 'WAP';
		$extarr['app_name'] = '';
		$extarr['bundle_id'] = '';
		$extarr['package_name'] = '';
		$extarr['wap_url'] = 'pc.16you.com';
		$extarr['wap_name'] = '16游';
		$extarr['note'] = '';
		$extarr['attach'] = '';
		return json_encode($extarr);
	}
	
	/**
	 * 作用：获取扩展
	 */
	public function getExt2(){
		$extarr = array();
		$extarr['requestFrom'] = 'WAP';
		$extarr['wap_url'] = 'pc.16you.com';
		$extarr['wap_name'] = '16游';
		$extarr['note'] = '';
		$extarr['attach'] = '';
		return json_encode($extarr);
	}
	
	/**
	 * 作用：获取序列号
	 */
	function getTraceno(){
		return  md5(uniqid().'-'.rand(10000,99999).'-'.uniqid().'-'.rand(10000,99999));
	}
	
	/**
	 * 作用：获取微信支付宝签名
	 */
	function getSignMsg($obj,$key){
		$stringobj = '';
		foreach ($obj as $k=>$v){
			if($v!=null){
				$stringobj .= $v;
			}
		}
		$reqPar;
		if (strlen($stringobj) > 0)
		{
			$reqPar =   $stringobj.$key;  // substr($stringobj, 0, strlen($stringobj)-1);
		}
		$signature  = MD5($reqPar); 
		return $signature;
	}
	
	/**
	 * 作用：获取微信扫码签名
	 */
	function getSignMsg2($obj,$key){
		$stringobj = '';
		foreach ($obj as $k=>$v){
			if($v!=null){
				$stringobj .= $v;
			}
		}
		$reqPar;
		if (strlen($stringobj) > 0)
		{
			$reqPar =   $stringobj.$key;  // substr($stringobj, 0, strlen($stringobj)-1);
		}
		echo '-----待签字符串：----'.$reqPar.'-----';
		$signature  = strtoupper(MD5($reqPar));
		echo '-----签名字符串：--'.$signature.'------';
		return $signature;
	}
	
	/**
	 * 作用：获取网银MD5签名
	 */
	function getSignBankMsg($obj,$key){
		$stringobj = '';
		foreach ($obj as $k=>$v){
			if($v!=null){
				$stringobj .= $v.'|';
			}
		}
		$reqPar;
		if (strlen($stringobj) > 0)
		{
			$stringobj = substr($stringobj, 0, strlen($stringobj)-1);
			$reqPar =   $stringobj.'|'.$key;  // substr($stringobj, 0, strlen($stringobj)-1);
		}
		$signature  = MD5($reqPar);
		return $signature;
	}
	
	/**
	 * 作用：获取RSA签名
	 * @param unknown $obj
	 * @param unknown $signature  返回的结果
	 * $signType 签名算法,php 默认使用 OPENSSL_ALGO_SHA1,Java 中MD5withRSA 对应 OPENSSL_ALGO_MD5
	 */
	function getRsaSign($obj,$type){
		$stringobj = '';
		foreach ($obj as $k=>$v){
			if($v!=null){
				$stringobj .= $v.'|';
			}
		}
		$sft_pubfile = yii::$app->params['sftpay']['private_key_path']; //用于验签的公钥文件
		$public_key= file_get_contents($sft_pubfile);
		$pkeyid = openssl_get_privatekey($public_key);
		openssl_sign($stringobj, $sign, $pkeyid,$type);//OPENSSL_ALGO_MD5
		openssl_free_key($pkeyid);
		$signature = base64_encode($sign);
		return $signature;
	}
	  
	/** 
	 * 验签
	 * @param unknown $data   数据
	 * @param unknown $sign   RSA签名
	 * @param unknown $pkeyid    用于验签的公钥文件
	 * $signType 签名算法,php 默认使用 OPENSSL_ALGO_SHA1,Java 中MD5withRSA 对应 OPENSSL_ALGO_MD5
	 * @return number
	 */
	public function rsaVerify($data, $sign,$type){
		$sign = base64_decode($sign);
		$sft_pubfile =  yii::$app->params['sftpay']['public_key_path'];//用于验签的公钥文件
		$public_key= file_get_contents($sft_pubfile);
		$pkeyid = openssl_get_publickey($public_key);
		$verify = '';
		if ($pkeyid) {
			// 	   writeLog("RSA2");
			$verify = openssl_verify($data, $sign, $pkeyid, $type);
			if($verify!=1){
				yii::trace("--------------------verify=$verify---------");
			}
			openssl_free_key($pkeyid);
		}
		if($verify == 1){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * curl方式获取数据
	 * @param unknown $url
	 * @param string $data
	 * @return mixed
	 */
	public static function https_request($url,$data=null,$second){
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_TIMEOUT,$second);
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
	
}