<?php
namespace common\pay;
use common\pay\Wxpay_client_pub;
use yii\log\Logger;
use Yii;
/**
 * 对账单接口
 */
class DownloadBill_pub extends Wxpay_client_pub
{

	function __construct()
	{
		//设置接口链接
		$this->url = "https://api.mch.weixin.qq.com/pay/downloadbill";
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
			if($this->parameters["bill_date"] == null )
			{
				Yii::getLogger()->log("对账单接口中，缺少必填参数bill_date", Logger::LEVEL_WARNING);
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

	/**
	 * 	作用：获取结果，默认不使用证书
	 */
	function getResult()
	{
		$this->postXml();
		$this->result = $this->xmlToArray($this->result_xml);
		return $this->result;
	}



}