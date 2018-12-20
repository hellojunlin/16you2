<?php
namespace common\pay;
use yii;
use common\pay\Wxpay_client_pub;
use common\pay\SDKRuntimeException;
use yii\log\Logger;
class Sendredpack_pub extends Wxpay_client_pub{
	function __construct(){
		//设置接口链接
		$this->url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
		//设置curl超时时间
		$this->curl_timeout = 30;
	}
	
	/**
	 * 生成接口参数xml
	 */
	function createXml(){
		try{
		//检测必填参数
			if ($this->parameters["partner_trade_no"] == null ){
				Yii::getLogger()->log("缺少统一发红包接口必填参数partner_trade_no！", Logger::LEVEL_WARNING);
				exit();
			//	throw new SDKRuntimeException("统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！"."<br>");
			}
			elseif ($this->parameters["openid"] == null ){
				Yii::getLogger()->log("缺少统一发红包接口必填参数openid！", Logger::LEVEL_WARNING);
				exit();
				//	throw new SDKRuntimeException("统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！"."<br>");
			}
			elseif ($this->parameters["amount"] == null ){
				Yii::getLogger()->log("缺少统一发红包接口必填参数amount！", Logger::LEVEL_WARNING);
				exit();
				//	throw new SDKRuntimeException("统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！"."<br>");
			}
			elseif ($this->parameters["desc"] == null ){
				Yii::getLogger()->log("缺少统一发红包接口必填参数desc！", Logger::LEVEL_WARNING);
				exit();
				//	throw new SDKRuntimeException("统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！"."<br>");
			}
			elseif ($this->parameters["check_name"] == null ){
				Yii::getLogger()->log("缺少统一发红包接口必填参数check_name！", Logger::LEVEL_WARNING);
				exit();
				//	throw new SDKRuntimeException("统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！"."<br>");
			}
			$wxinfo = yii::$app->session->get('wxinfo');
			$this->parameters["mch_appid"] = $wxinfo['appid'];//公众账号ID
			$this->parameters["mchid"] = $wxinfo['mch_id'];//商户号
			$this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串，不长于32位
			$this->parameters["spbill_create_ip"] = $_SERVER['REMOTE_ADDR'];//终端ip
			$this->parameters["sign"] = $this->getSign($this->parameters);//签名
			$xml = $this->arrayToXml($this->parameters);
			return $xml;
		}catch(Exception $e){ 
			
		}
	}
	
	/**
	 * 	作用：post请求xml
	 */
	function postXml()
	{
		$xml = $this->createXml();
		$this->response = $this->postXmlSSLCurl($xml,$this->url,$this->curl_timeout);
		return $this->response;
	}
	
	/**
	 * 获取prepay_id
	 */
	function getPrepayId()
	{
		$this->postXml();
		$this->result = $this->xmlToArray($this->response);
		return $this->result;
	//	$prepay_id = $this->result["prepay_id"];
	//	return $prepay_id;
	}
}