<?php
namespace common\alisms1;
use common\alisms1\api_sdk\aliyun\Config;
use common\alisms1\api_sdk\aliyun\DefaultAcsClient;
use common\alisms1\api_sdk\aliyun\Profile\DefaultProfile;
use common\alisms1\api_sdk\aliyun\SendSmsRequest;

class SendSms{
    public function sendSms($msg,$phone) {
    
        //此处需要替换成自己的AK信息
        $accessKeyId = "LTAIhBbd8f9BBtlH";
        $accessKeySecret = "LM3bJoEX4EjKIq8EvNoMNFN0KfUuKz";
        //短信API产品名
        $product = "16yousmsapi";
        //短信API产品域名
        $domain = "wx.16you.com";
        //暂时不支持多Region
        $region = "cn-hangzhou";
        
        //初始化访问的acsCleint
        $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
        
        DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
        $acsClient= new DefaultAcsClient($profile);

        $request = new SendSmsRequest;
        //必填-短信接收号码
        $request->setPhoneNumbers($phone);
        //必填-短信签名
        $request->setSignName("野人16游");
        //必填-短信模板Code
        $request->setTemplateCode("SMS_77110034");
        //选填-假如模板中存在变量需要替换则为必填(JSON格式)
        $request->setTemplateParam("{\"code\":'".$msg."'}");
        //选填-发送短信流水号
        //$request->setOutId("1234");
        
        //发起访问请求
        $acsResponse = $acsClient->getAcsResponse($request);
        var_dump($acsResponse);exit;
        return $acsResponse;
    }
}