<?php
namespace common\common;
use common\pay\OrderQuery_pub;
use Yii;
use common\pay\Refund_pub;
use common\pay\UnifiedOrder_pub;
use yii\log\Logger;
use common\redpackpay\Sendredpack_pub;
use common\redpackpay\Wxcommonutil;

/**
 * 微信支付工具类
 * @author HanksGump
 *
 */
class Wxpayutil{
	public function __construct(){
		$wxinfo = Yii::$app->session->get('wxinfo');
		if(!isset($wxinfo)||!is_array($wxinfo)){		//实例化该类时，先缓存微信信息
			$wxinfo = Yii::$app->params['wxinfo'];
			Yii::$app->session->set('wxinfo',$wxinfo);
		}
		//掌上宝藏发红包的微信信息
		$redpackwinfo = Yii::$app->session->get('redpackwinfo');
		if(!isset($redpackwinfo)||!is_array($redpackwinfo)){		//实例化该类时，先缓存微信信息
			$redpackwinfo = Yii::$app->params['redpackwinfo'];
			Yii::$app->session->set('redpackwinfo',$redpackwinfo);
		}
	}
	/**
	 * 微信支付
	 * @param  [type] $openid       [用户openid]
	 * @param  [type] $out_trade_no [支付订单号]
	 * @param  [type] $pay_money    [总金额]
	 * @param  [type] $theme        [商品描述]
	 * @param  [type] $attach       [厂商附加数据]
	 * @param  [type] $product_id   [厂商商品编号]
	 * @return [string]
	 */
	public function pay($openid,$out_trade_no,$pay_money,$theme,$attach,$product_id){
		$wxinfo = Yii::$app->session->get('wxinfo');
		$unifiedOrder = new UnifiedOrder_pub();
		$unifiedOrder->setParameter('openid',$openid);
		$unifiedOrder->setParameter('body',$theme);
		$unifiedOrder->setParameter('out_trade_no', "$out_trade_no");
		$unifiedOrder->setParameter('total_fee', $pay_money*100);	//总金额,以分为单位
		$unifiedOrder->setParameter('notify_url', yii::$app->params['wxinfo']['notify_url']);	//通知地址
		$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
		$unifiedOrder->setParameter("attach",$attach);//附加数据
		$unifiedOrder->setParameter("product_id",$product_id);//厂商支付时填写的商品id
		$result = $unifiedOrder->getPrepayId();//prepay_id
		
		if(isset($result['return_code'])&&$result['return_code']=='SUCCESS'){
			if($result['result_code']=='SUCCESS'){
				$prepay_id = $result['prepay_id'];
				$timeStamp = time();
				//使用jsapi接口
				$jsApiObj["appId"] = $wxinfo['appid'];
				$jsApiObj["timeStamp"] = "$timeStamp";
				$jsApiObj["nonceStr"] = $unifiedOrder->createNoncestr();
				$jsApiObj["package"] = "prepay_id=$prepay_id";
				$jsApiObj["signType"] = "MD5";
				$jsApiObj["paySign"] = $unifiedOrder->getSign($jsApiObj);
				return $jsApiObj;
			}else if(in_array($result['err_code'],['SYSTEMERROR','INVALID_TRANSACTIONID','PARAM_ERROR','NOAUTH'])){
				//接口后台错误，无效transaction_id，提交参数错误，商户无权限
				return false;
			}else if($result['err_code']=='ORDERPAID'){		//该订单已支付
				return 'ORDERPAID';
			}else if($result['err_code']=='OUT_TRADE_NO_USED'){		//商户订单号重复
				return 'OUT_TRADE_NO_USED';
			}
		}else{		//支付异常
			return false;
		}
	}

	public  function nativepay($openid,$out_trade_no,$pay_money,$theme,$attach,$product_id){
		$wxinfo = Yii::$app->session->get('wxinfo');
		$unifiedOrder = new UnifiedOrder_pub();
		$unifiedOrder->setParameter('openid',$openid);
		$unifiedOrder->setParameter('body',$theme);
		$unifiedOrder->setParameter('out_trade_no', "$out_trade_no");
		$unifiedOrder->setParameter('total_fee', $pay_money*100);	//总金额,以分为单位
		$unifiedOrder->setParameter('notify_url', yii::$app->params['wxinfo']['notify_url']);	//通知地址
		$unifiedOrder->setParameter("trade_type","NATIVE");//交易类型
		$unifiedOrder->setParameter("attach",$attach);//附加数据
		$unifiedOrder->setParameter("product_id",$product_id);//厂商支付时填写的商品id
		$result = $unifiedOrder->getPrepayId();//prepay_id
		if(isset($result['return_code'])&&$result['return_code']=='SUCCESS'){
			if($result['result_code']=='SUCCESS'){
				$code_url = urlencode($result['code_url']);
				echo  \Yii::getAlias('@common').'/common/phpqrcode.php';exit;
				/* require_once \Yii::getAlias('@common').'/common/phpqrcode.php';
				$codeimg = QRcode::png($url);
				var_dump($codeimg);exit;  */ 
				return 'http://paysdk.weixin.qq.com/example/qrcode.php?data='.$code_url;
			}else if(in_array($result['err_code'],['SYSTEMERROR','INVALID_TRANSACTIONID','PARAM_ERROR','NOAUTH'])){
				//接口后台错误，无效transaction_id，提交参数错误，商户无权限
				return false;
			}else if($result['err_code']=='ORDERPAID'){		//该订单已支付
				return 'ORDERPAID';
			}else if($result['err_code']=='OUT_TRADE_NO_USED'){		//商户订单号重复
				return 'OUT_TRADE_NO_USED';
			}
		}else{		//支付异常
			return false;
		}
	}
	
	/**
	 * 查询微信订单
	 * @param unknown $out_trade_no
	 * @return multitype:string |multitype:string unknown |unknown
	 */
	public function order_query($out_trade_no){
		$orderquery = new OrderQuery_pub();
		$orderquery->setParameter('out_trade_no', $out_trade_no);		//订单号
		$orderqueryresult = $orderquery->getResult();		//获取订单查询结果
		if($orderqueryresult['return_code']=='FAIL'){		//通信出错
			return ['errorcode'=>'1000','msg'=>'微信通信出错'];
		}elseif($orderqueryresult['result_code']=='FAIL'){
			return ['errorcode'=>'1000','msg'=>$orderqueryresult['err_code_des']];
		}else{
			return ['errorcode'=>0,'data'=>$orderqueryresult];
		}
	}
	
	/**
	 * 退款接口
	 * @param unknown $out_trade_no 退款订单号
	 * @param unknown $refund_fee  退款金额
	 * @param unknown $op_user_id  操作员(string 32)
	 */
	public function refund($out_trade_no,$refund_fee,$op_user_id){
		$refund = new Refund_pub();
		$refund->setParameter('out_trade_no', $out_trade_no);		//商户订单号
		$refund->setParameter('out_refund_no', $out_trade_no);		//商户退款单号
		$refund->setParameter("total_fee",$refund_fee*100);			//总金额
		$refund->setParameter('refund_fee', $refund_fee*100);			//退款金额
		$refund->setParameter("op_user_id",$op_user_id);	//操作员
		$refundresult = $refund->getResult();		//调用退款
		Yii::getLogger()->log($refundresult, Logger::LEVEL_ERROR);
		if($refundresult['return_code']=='FAIL'){		//通信出错
			return ['errorcode'=>'1000','msg'=>'微信通信出错'];
		}elseif($refundresult['result_code']=='FAIL'){
			return ['errorcode'=>'1000','msg'=>$refundresult['err_code_des'],'err_code'=>$refundresult['err_code']];
		}else{
			return ['errorcode'=>0,'data'=>$refundresult];
		}
	}
	
	/**
	 * 下载历史清单
	 */
	public function downloadbill($date){
		
	}
	
	/**
	 * 掌上宝藏企业付款 ---微信发红包
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