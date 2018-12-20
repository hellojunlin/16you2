<?php
namespace common\common;
use Yii;
use yii\log\Logger;
use common\pay\Sendredpack_pub;
use common\pay\Wxcommonutil;
/**
 * 16游企业到付
 * @author HanksGump
 *
 */
class Youwxpayutil{
	public function __construct(){
		$wxinfo = Yii::$app->session->get('wxinfo');
		if(!isset($wxinfo)||!is_array($wxinfo)){		//实例化该类时，先缓存微信信息
			$wxinfo = Yii::$app->params['wxinfo'];
			Yii::$app->session->set('wxinfo',$wxinfo);
		}
	}
	
	/**
	 * 16游企业付款 ---微信发红包
	 * $openid   公众号openid
	 * $partner_trade_no 商户订单号
	 * $pay_money  支付金额
	 * $desc  企业付款操作说明信息
	 * $act_name 活动名称
	 * $spbill_create_ip  调用接口的机器Ip地址
	 * $checkname NO_CHECK：不校验真实姓名 FORCE_CHECK：强校验真实姓名
	 */
	public function sendredpacket($openid,$partner_trade_no,$pay_money,$desc,$checkname='NO_CHECK',$re_user_name=''){
		$sendredpack = new Sendredpack_pub();
		$sendredpack->setParameter('partner_trade_no',$partner_trade_no);//商户订单号
		$sendredpack->setParameter('openid',$openid); //商户名称
		$sendredpack->setParameter('amount',$pay_money*100);//企业付款金额，单位为分
		$sendredpack->setParameter('desc', $desc);//企业付款操作说明信息。必填。
		$sendredpack->setParameter('check_name',$checkname);//是否校验真实姓名
		if($checkname=='FORCE_CHECK'){
			$sendredpack->setParameter('re_user_name', $re_user_name);//企业付款操作说明信息。必填。
		}
		$xmlres = $sendredpack->postXml();
		$wxcomm = new Wxcommonutil();
		$res = $wxcomm->xmlToArray($xmlres);
		if(isset($res['result_code'])&&$res['result_code']=='SUCCESS' ){//发红包成功
			return true;
		}else{//发红包失败
			return false;
		}
	
	}
}