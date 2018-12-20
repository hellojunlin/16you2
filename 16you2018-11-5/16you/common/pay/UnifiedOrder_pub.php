<?php
namespace common\pay;
use yii;
use common\pay\Wxpay_client_pub;
use common\pay\SDKRuntimeException;
use yii\log\Logger;
class UnifiedOrder_pub extends Wxpay_client_pub{
	function __construct(){
		//设置接口链接
		$this->url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
		//设置curl超时时间
		$this->curl_timeout = 30;
	}
	
	/**
	 * 生成接口参数xml
	 */
	function createXml(){
		try{
			//检测必填参数
			if($this->parameters["out_trade_no"] == null)
			{
				Yii::getLogger()->log("缺少统一支付接口必填参数out_trade_no！", Logger::LEVEL_WARNING);
				exit();
			//	throw new SDKRuntimeException("缺少统一支付接口必填参数out_trade_no！"."<br>");
			}elseif($this->parameters["body"] == null){
				Yii::getLogger()->log("缺少统一支付接口必填参数body！", Logger::LEVEL_WARNING);
				exit();
			//	throw new SDKRuntimeException("缺少统一支付接口必填参数body！"."<br>");
			}elseif ($this->parameters["total_fee"] == null ) {
				Yii::getLogger()->log("缺少统一支付接口必填参数total_fee！", Logger::LEVEL_WARNING);
				exit();
				//throw new SDKRuntimeException("缺少统一支付接口必填参数total_fee！"."<br>");
			}elseif ($this->parameters["notify_url"] == null) {
				Yii::getLogger()->log("缺少统一支付接口必填参数notify_url！", Logger::LEVEL_WARNING);
				exit();
			//	throw new SDKRuntimeException("缺少统一支付接口必填参数notify_url！"."<br>");
			}elseif ($this->parameters["trade_type"] == null) {
				Yii::getLogger()->log("缺少统一支付接口必填参数trade_type！", Logger::LEVEL_WARNING);
				exit();
			//	throw new SDKRuntimeException("缺少统一支付接口必填参数trade_type！"."<br>");
			}elseif ($this->parameters["trade_type"] == "JSAPI" &&
					$this->parameters["openid"] == NULL){
				Yii::getLogger()->log("统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！", Logger::LEVEL_WARNING);
				exit();
			//	throw new SDKRuntimeException("统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！"."<br>");
			}
			$wxinfo = yii::$app->session->get('wxinfo');
			$this->parameters["appid"] = $wxinfo['appid'];//公众账号ID
			$this->parameters["mch_id"] = $wxinfo['mch_id'];//商户号
			$this->parameters["notify_url"] = $wxinfo['notify_url'];//支付后台通知接口
			$this->parameters["spbill_create_ip"] = $_SERVER['REMOTE_ADDR'];//终端ip
			$this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串
			$this->parameters["sign"] = $this->getSign($this->parameters);//签名
			return  $this->arrayToXml($this->parameters);
		}catch(Exception $e){
			
		}
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