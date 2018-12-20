<?php
namespace common\common;
use yii;
use common\common\Wxinutil;
/**
 * 微信分享的操作
 * @author He
 *
 */
class Shareutil {
	//获取signPackage
	public function getSignPackage($appid,$appsecret,$url='') {
		$jsapiTicket = $this->getJsApiTicket($appid,$appsecret);
		// 注意 URL 一定要动态获取，不能 hardcode.
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$url = ($url)?$url:"$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		//截取分享后的参数 ?from=groupmessage&isappinstalled=0
		/* if(strstr($url, '&isappinstalled=0', TRUE)){ 
			$url = strstr($url, '&isappinstalled=0', TRUE);
		}  */
		$timestamp = time();
		$nonceStr = $this->createNonceStr();
		// 这里参数的顺序要按照 key 值 ASCII 码升序排序
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
		$signature = sha1($string);
		$signPackage = array(
				"appId"     => $appid,
				"nonceStr"  => $nonceStr,
				"timestamp" => $timestamp,
				"url"       => $url,
				"signature" => $signature,
				"rawString" => $string,
				'jsapiTicket'=>$jsapiTicket,
		);
		return $signPackage;
	}
	
	private function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}
	
	private function getJsApiTicket($appid,$appsecret) {
		$key = 'ticket'.$appid;
		$ticket = \yii::$app->cache->get($key);
		if(!$ticket){		//如果缓存不存在ticket,重新获取
			$data = $this->saveTick($appid,$appsecret);
			if($data){		//正常获取到ticket
				$ticket = $data['jsapi_ticket'];
				\yii::$app->cache->set($key,$ticket,7000);
			}else{		//否则null非正常返回
				$ticket = null;
			}
		}
		return $ticket;
	}
	
	private function saveTick($appid,$appsecret){
		$Wxinutil = new Wxinutil();
		$accessToken = $Wxinutil->getAccesstoken($appid,$appsecret);
		// 如果是企业号用以下 URL 获取 ticket
		// $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
		$res = json_decode($Wxinutil->http_get($url));
		$ticket = isset($res->ticket)?$res->ticket:'';
		if ($ticket) {
			$data['jsapi_ticket'] = $ticket;
			$data['expire_time']  =  time() + 7000;
			//重新存入数据库
			//$Ticket->add($data);
			return  $data;
		}else{
			return null;
		}	
		
	}
}
