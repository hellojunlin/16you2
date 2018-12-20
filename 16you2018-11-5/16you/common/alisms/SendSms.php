<?php

namespace common\alisms;

use common\alisms\top\TopClient;
use common\alisms\top\request\AlibabaAliqinFcSmsNumSendRequest;
class SendSms {
    /**
     * 
     * @param unknown $msg     //模板消息变量
     * @param unknown $phone   //手机号码
     * @param unknown $smsTemplateCode  //短信消息模板
     * @param unknown $type    //短信类型  ：1：验证码 ，2：短信通知  3:语音通知   
     */
	public function send($msg,$phone,$type=1){
		$c = new TopClient;
		$c->appkey = 'LTAIhBbd8f9BBtlH';
		$c->secretKey = 'LM3bJoEX4EjKIq8EvNoMNFN0KfUuKz';
		$req = new AlibabaAliqinFcSmsNumSendRequest();
		$req->setSmsType("normal");    
		$req->setSmsFreeSignName("野人16游");  
		$req->setRecNum($phone);
		$sms_template_code = "SMS_76610341";
		$req->setSmsParam("{\"code\":\"$msg\"}");
		$req->setSmsTemplateCode($sms_template_code);
		$resp = $c->execute($req);
		var_dump($resp);exit;
		if(isset($resp->result->err_code)){
			if($resp->result->err_code==0){//发送成功
				return true;
			}
		}else{//发送失败
			   return false;
		}
	}
}

?>