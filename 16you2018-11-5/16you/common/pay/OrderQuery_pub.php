<?php
namespace common\pay;
use common\pay\SDKRuntimeException;
use yii;
use yii\log\Logger;
/**
 * 订单查询接口
 * @author HanksGump
 *
 */
class OrderQuery_pub extends Wxpay_client_pub{
	function __construct()
	{
		//设置接口链接
		$this->url = "https://api.mch.weixin.qq.com/pay/orderquery";
		//设置curl超时时间
		$this->curl_timeout = 30;
	}
	
	/**
	 * 生成接口参数xml
	 */
	function createXml()
	{
		try
		{
			//检测必填参数
			if($this->parameters["out_trade_no"] == null &&
					$this->parameters["transaction_id"] == null)
			{  
				Yii::getLogger()->log("订单查询接口中，out_trade_no、transaction_id至少填一个！", Logger::LEVEL_WARNING);
				exit();
			}
			$wxinfo = Yii::$app->session->get('wxinfo');
			$this->parameters["appid"] = $wxinfo['appid'];//公众账号ID
			$this->parameters["mch_id"] = $wxinfo['mch_id'];//商户号
			$this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串
			$this->parameters["sign"] = $this->getSign($this->parameters);//签名
			return  $this->arrayToXml($this->parameters);
		}catch (SDKRuntimeException $e)
		{
			die($e->errorMessage());
		}
	}
}