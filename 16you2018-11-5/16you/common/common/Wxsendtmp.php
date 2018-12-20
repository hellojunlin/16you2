<?php

namespace common\common;
use yii;
use common\common\Wxinutil;

/**
 * 发送模板消息
 * @author junlin
 *
 */
class Wxsendtmp {
	 public function sendTmpMessage($appid,$appsecret,$data){
	 	$wxutil = new Wxinutil();
	 	$access_token = $wxutil->getAccesstoken($appid, $appsecret);
	 	$templatelsit = $this->getTempList($wxutil, $access_token); //获取模板列表
	 	$template_id = $this->getTemplateid($wxutil, $access_token);//模板id
	 	var_dump($template_id);exit();
	 	$template = '   {
	           "touser":"'+$appid+'",
	           "template_id":"'+$template_id+'",
	           "url":"http://weixin.qq.com/download",            
	           "data":{
	                   "first": {
	                       "value":"恭喜您，支付成功！",
	                       "color":"#173177"
	                   },
	                   "keynote1":{
	                       "value":"巧克力",
	                       "color":"#173177"
	                   },
	                   "remark":{
	                       "value":"欢迎再次购买！",
	                       "color":"#173177"
	                   }
	           }
	       }';
	 }	
	 
	 /**
	  * 获取模板列表
	  * @param unknown $wxutil
	  * @param unknown $access_token
	  */
	 private function getTempList($wxutil,$access_token){
	 	$url = "https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token=$access_token";
	 	$templist = $wxutil->http_get($url);
	 	return json_decode($templist,true);
	 }
	 /**
	  * 获取模板id
	  * @param unknown $wxutil
	  * @param unknown $access_token
	  */
	 private function getTemplateid($wxutil,$access_token){
	 	$url = "https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token=$access_token";
	 	$temres = $wxutil->https_request($url);
	 	return json_decode($temres,true);
	 }
	 
		/**
		 * 发送模板消息
		 * @param unknown $data
		 * @param unknown $access_token
		 */
	  private function send_template_message($wxutil,$data,$access_token){
	  	$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
	  	$res = $wxutil->https_request($url,$data);
	  	return json_decode($res,true);
	  	
	  }
}

?>