<?php
namespace common\redpackpay;
use yii;
/**
 * 微信公用工具类
 * @author HanksGump
 *
 */
class Wxcommonutil{
	function __construct(){
		
	}
	
	function trimString($value){
		$ret = null;
		if(null!=$value){
			$ret = $value;
			if(strlen($ret)==0){
				$ret = null;
			}
		}
		return $ret;
	}
	
	/*
	 * 产生随机字符串，不长于32位
	 */
	public function createNoncestr($length=32){
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789"; 
		$str = "";
		for($i=0;$i<$length;$i++){
			$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1); 
		} 
		return $str;
	}
	
	/*
	 * 格式化参数，签名过程需要使用
	 */
	function formatBizQueryParaMap($paraMap, $urlencode){
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v)
		{
		    if($urlencode)
		    {
			   $v = urlencode($v);
			}
			//$buff .= strtolower($k) . "=" . $v . "&";
			$buff .= $k . "=" . $v . "&";
		}
		$reqPar;
		if (strlen($buff) > 0) 
		{
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}
	
	/**
	 * 生成签名
	 */
	public function getSign($obj){
		foreach ($obj as $k=>$v){
			$Parameters[$k] = $v;
		}
		//签名步骤一：按字典序排序参数
		ksort($Parameters);
		$String = $this->formatBizQueryParaMap($Parameters,false);
		//签名步骤二：在string后面加入KEY
		$redpackwinfo = yii::$app->session->get('redpackwinfo');
		$String = $String."&key=".$redpackwinfo['key'];
		//签名步骤三：MD5加密
		$String = md5($String);
		//签名步骤四：所有字符串转为大写
		$result = strtoupper($String);
		return $result;
	}
	
	/**
	 * 将array转xml
	 */
	function arrayToXml($arr){
		$xml = "<xml>";
        foreach ($arr as $key=>$val){
        	 if (is_numeric($val)){
        	 	$xml.="<".$key.">".$val."</".$key.">"; 

        	 }else{
        	 	$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";  
        	 }
        }
        $xml.="</xml>";
        return $xml; 
	}
	
	/**
	 * 将xml转为array
	 */
	public function xmlToArray($xml){
		 $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);		
		 return $array_data;
	}
	
	/**
	 * 以post方式提交xml到对应的接口url
	 */
	public function postXmlCurl($xml,$url,$second=30){
		//初始化curl
		$ch = curl_init();
		//设置超时
	    //curl_setopt($ch, CURLOP_TIMEOUT, $second);
		//这里设置代理，如果有的话
		//curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
		//curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
		if (class_exists('\CURLFile')) {
			curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
		} else {
			if (defined('CURLOPT_SAFE_UPLOAD')) {
				curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
			}
		}
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		//设置header
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		//post提交方式
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		//运行curl
		$data = curl_exec($ch);
	//	curl_close($ch);
		//返回结果
		if($data)
		{
			curl_close($ch);
			return $data;
		}
		else
		{
			$error = curl_errno($ch);
			echo "curl出错，错误码:$error"."<br>";
			echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
					curl_close($ch);
					return false;
		}
	}
	
	/**
	 * 	作用：使用证书，以post方式提交xml到对应的接口url
	 */
	function postXmlSSLCurl($xml,$url,$second=30)
	{   
		$redpackwinfo = Yii::$app->session->get('redpackwinfo');
		$ch = curl_init();
		//超时时间
		curl_setopt($ch,CURLOPT_TIMEOUT,$second);
		//这里设置代理，如果有的话
		//curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
		//curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		//设置header
		curl_setopt($ch,CURLOPT_HEADER,FALSE);
		//要求结果为字符串且输出到屏幕上
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
		//设置证书
		//使用证书：cert 与 key 分别属于两个.pem文件
		//默认格式为PEM，可以注释
		curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
		//curl_setopt($ch,CURLOPT_SSLCERT, $redpackwinfo['cert_path']);
		curl_setopt($ch,CURLOPT_SSLCERT, $redpackwinfo['cert_path']);
		//默认格式为PEM，可以注释
		curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
		//curl_setopt($ch,CURLOPT_SSLKEY,$redpackwinfo['key_path']);
		curl_setopt($ch,CURLOPT_SSLKEY,$redpackwinfo['key_path']);
		//post提交方式
		curl_setopt($ch,CURLOPT_POST, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);
		$data = curl_exec($ch);
		//返回结果
		if($data){
			curl_close($ch);
			return $data;
		}
		else {
			$error = curl_errno($ch);
			echo "curl出错，错误码:$error"."<br>";
			echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
			curl_close($ch);
			return false;
		}
	}
	
	
	/**
	 * 	作用：打印数组
	 */
	function printErr($wording='',$err='')
	{
		print_r('<pre>');
		echo $wording."</br>";
		var_dump($err);
		print_r('</pre>');
	}
}
