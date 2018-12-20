<?php
namespace pc\controllers;

use yii;
use frontend\controllers\BaseController;
use common\models\Order;
use common\common\Wxpayutil;
use common\common\Helper;
use common\redismodel\GameRedis;
use common\common\Sftpayutil;
use common\models\User;
use common\models\Plateform;
use common\common\Unpayutil;
use common\models\Voucher;
use common\models\Discount;

/**
 * 商城代金券支付
 * @author lianshang
 *
 */
class ShoppayController extends BaseController{
	/*
	 * gid ：游戏id
	* out_trade_no :厂商订单编号
	* product_id : 商品 id
	* body : 订单或商品的名称
	* total_fee : 支付总金额 以分为单位 必须大于 0
	* detail : 订单或商品的详情
	* attach : 附加数据 后台通知时原样返回
	* sign : 请求参数签名
	* ptype : 支付类型   1:微信支付  2：盛付通微信支付 3：盛付通支付宝支付 4：盛付通网银支付  5：盛付通H5快捷支付  6：微信扫码支付   7: 盛付通微信扫码支付  8：游币支付   9：优赋H5支付
	* payurl : 支付请求链接
	*/
	public function actionAllsftpay(){
		if(Yii::$app->request->isAjax){
			$msg = '';
			(!isset($_POST['vtype'])) && $msg = 'vtype required';
			(!isset($_POST['ptype'])) && $msg = 'ptype required';
			(!isset($_POST['price'])) && $msg = 'price required';
			if($msg){
				return json_encode(['errorcode'=>'1001','msg'=>$msg]);
			}
			$vtype = Helper::filtdata($_POST['vtype'],'INT');               //代金券类型  1:5元   2:15元  3:25元  4:35元   5:40元  6:50元
			$ptype = Helper::filtdata($_POST['ptype'],'INT'); 				//支付类型
			$oprice = Helper::filtdata($_POST['price'],'MONEY');             //价格  以元为单位
			$payurl = Helper::filtdata($_POST['payurl']);                 //前端跳转链接
			$user = Yii::$app->session->get('user');				     
			$vouchernum = isset(yii::$app->params['vouchernum'][$vtype])?yii::$app->params['vouchernum'][$vtype] : false;   //代金券面值
			
			if(!$vtype || !$ptype || $oprice===false || !$vouchernum){
				return json_encode(['errorcode'=>'1001','msg'=>'参数错误']);
			}
			//获取本周折扣 
			$discount = 10;
			$mondaytime =  strtotime(date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600))); //本周一时间戳
			$sundaytime = $sundaytime = $mondaytime+24*3600*8-1;    //本周星期天的时间戳
			
			$voucherobj = Voucher::find()->where(['uid'=>$user->id,'vtype'=>$vtype,'state'=>2])->andWhere(['between','createtime',$mondaytime,$sundaytime])->select('id')->one();   //查找该类型的代金券在本周的数据
			if($voucherobj){
				return json_encode(['errorcode'=>'1001','msg'=>'您好，该类型的代金券本周已购买过']);
			}
			
		    $thisweekdiscountobj =   Discount::findOne(['mondaytime'=>$mondaytime,'uid'=>$user->id]);    //查找本周折扣
		    $thisweekdiscountobj && $discount = $thisweekdiscountobj->discount;
		    $price = $vouchernum*$discount/10;
			if($oprice!=$price){ //价格不相等则提示错误
				return json_encode(['errorcode'=>'1002','msg'=>'价钱不符，请联系客服']);
			}
			
			if(!$vtype || !$ptype){
				return json_encode(['errorcode'=>'1001','msg'=>'非法访问，参数错误']);
			}
			//获取个人信息，和报名信息，判断是否存在以及是否可以支付
			$voucher = new Voucher();
			$voucher->uid = $user->id;
			$voucher->price = $price;  //价格
			$voucher->discount = $discount;//折扣
			$voucher->currencynum = $vouchernum; //游币数量
			$voucher->state = 1;       //付款状态 1待付款 2付款成功 3退款中 4已退款 5付款失败
			$voucher->pid = $user->pid;//平台id
			$voucher->transaction_id = 'djq'.date('YmdHis',time()).rand(1000,9999);//交易编号
			$voucher->ptype = $ptype;   //支付类型：1:微信支付  2：盛付通微信支付 3：盛付通支付宝支付 4：盛付通网银支付  5：盛付通H5快捷支付  6：微信扫码支付
			$voucher->logintype = 1;//登录方式:1：会员 2：游客  
			$voucher->payclient = 1;//支付端：1微信  2：pc端  3 app端
			$voucher->vtype = $vtype;//代金券类型：1:5元   2:15元  3:25元  4:35元   5:40元  6:50元
			$voucher->createtime = time();
			$res = $voucher->save();
			if(!$res){		//保存失败
				return json_encode(['errorcode'=>'1001','msg'=>'保存失败']);
			}
			//满足支付条件，若价格为0，则直接更改数据库状态，无需调用微信支付
			if($voucher->price==0){
				$voucher->state = 2;
				$voucher->createtime = time();
				if($voucher->save()){
					return json_encode(['errorcode'=>'1000','msg'=>'支付成功']);
				}else{
					return json_encode(['errorcode'=>'1001','msg'=>'网络异常，请刷新页面后再重试']);
				}
			}
			$payres = '';
			$requesturl = '';
			if($ptype==5){//快捷支付
				$requesturl = 'https://api.shengpay.com/html5-gateway/express.htm?page=mobile';   //请求的链接
				$payres = $this->getsfthpay($memberid,$voucher,$payurl);
			}elseif($ptype==9){
				$requesturl = $this->unhpay($voucher, $payurl,'41');
			}elseif($ptype==10){//10:优赋微信扫码支付
				$requesturl = $this->unhpay($voucher, $payurl,'32');
			}
			return json_encode([
					'errorcode'=>0,
					'msg'=>$payres,
					'requesturl'=>$requesturl,
					],JSON_UNESCAPED_SLASHES);
			}else{
				return json_encode([
						'errorcode'=>1001,
						'msg'=>'网络异常，稍后再试',
						]);
			}
	}
	
	/**
	 * 获取盛付通微信，支付宝支付
	 * ptype : 支付类型   1:微信支付  2：盛付通微信支付 3：盛付通支付宝支付 4：盛付通网银支付  5：盛付通H5快捷支付  6：微信扫码支付
	 * $order : 订单信息对象
	 */
	private function getsftpay($ptype,$order,$payurl){
		$paychannel = '';
		switch ($ptype){
			case 2: $paychannel = 'hw';break;
			case 3: $paychannel = 'ha';break;
		}
		$Sftpayutil = new Sftpayutil();
		$sftparamarr = array();
		$sftparamarr['Name'] = 'B2CPayment';        //调用方标识
		$sftparamarr["Version"] = 'V4.1.1.1.1';    //接口提交版本信息
		$sftparamarr["Charset"] = 'UTF-8';          //商户提交请求对应的字符集编码
		$sftparamarr['TraceNo'] = $Sftpayutil->getTraceno();  //请求序列号
		$sftparamarr["MsgSender"] = yii::$app->params['sftpay']['MsgSender'];      //发送方标识
		$sftparamarr["SendTime"] = date('YmdHis');       //请求时间
		$sftparamarr['OrderNo'] = $order->transaction_id;          //商户订单号
		$sftparamarr['OrderAmount'] = $order->price;  //订单金额
		$sftparamarr['OrderTime'] = date('YmdHis');	     //订单创建时间
		$sftparamarr["Currency"] = 'CNY';           //商家和盛付通约定的交易币种
		$sftparamarr["PayType"] = 'PT312';          //微信H5或者支付宝H5均为PT312
		$sftparamarr["PayChannel"] = $paychannel;   //支付渠道   微信H5为hw
		$sftparamarr["PageUrl"] = $payurl;   //前台返回地址
		$sftparamarr["NotifyUrl"] = yii::$app->params['backend'].yii::$app->params['sftpay']['HnotifyUrl']; //异步应答结果地址
		$sftparamarr['ProductName'] = $order->propname;
		$sftparamarr["Ext2"] = $Sftpayutil->getExt2();       //扩展2 json对象
		$sftparamarr['SignType'] = 'MD5';            //
		$sftparamarr['SignMsg'] = $Sftpayutil->getSignMsg($sftparamarr,yii::$app->params['sftpay']['key']);
		return $sftparamarr;
	}
	
	
	/**
	 * 盛付通H5快捷支付
	 * $ptype = 5 盛付通H5快捷支付 可调用
	 */
	private function getsfthpay($memberid,$order,$payurl){
		$Sftpayutil = new Sftpayutil();
		$sftparamarr = array();
		$sftparamarr['merchantNo'] = yii::$app->params['sftpay']['MsgSender'];        //商户号
		$sftparamarr['charset'] = 'UTF-8';          //字符集
		$sftparamarr['requestTime'] = date('YmdHis');  //请求时间
		$sftparamarr['outMemberId'] = $memberid;      //商户会员标识
		$sftparamarr['outMemberRegistTime'] = date('YmdHis');           //商户会员注册时间
		$sftparamarr['outMemberRegistIP'] = $_SERVER["REMOTE_ADDR"];      //商户会员注册IP
		$sftparamarr['outMemberVerifyStatus'] = 1;          //商户会员是否已实名
		$sftparamarr['outMemberName'] = "16游用户";  //商户会员注册姓名
		$sftparamarr['outMemberMobile'] = "13922456011";	     //商户会员注册手机号
		$sftparamarr['merchantOrderNo'] = $order->transaction_id;     //商户订单号
		$sftparamarr['productName'] = $order->propname;          //商品名称
		$sftparamarr['currency'] = 'CNY';   //货币类型
		$sftparamarr['amount'] = $order->price; //异步应答结果地址
		$sftparamarr['pageUrl'] = $payurl;              //前台通知回调地址
		$sftparamarr['notifyUrl'] = yii::$app->params['backend'].yii::$app->params['sftpay']['HnotifyUrl']; //后台通知回调地址
		$sftparamarr['userIP'] = $_SERVER["REMOTE_ADDR"];  //后台通知回调地址
		$sftparamarr['SignType'] = 'RSA';            //
		$sftparamarr['SignMsg'] = $Sftpayutil->getRsaSign($sftparamarr,OPENSSL_ALGO_MD5);//$Sftpayutil->getSignBankMsg($sftparamarr,'support4html5test');
		return $sftparamarr;
	}
	
	/**
	 * 优赋H5支付
	 */
	private function unhpay($voucher,$payurl,$cardno){
		$unpayparam = '';
		$unpayparam .= 'customerid='.yii::$app->params['unpay']['MsgSender']; //商户在网关系统上的商户号
		$unpayparam .= '&sdcustomno='. $voucher->transaction_id;     //订单在商户系统中的流水号
		$unpayparam .= '&orderAmount='.($voucher->price*100);     //订单支付金额 以分为单位
		$unpayparam .= '&cardno='.$cardno;   //支付类型:32(微信扫码),41(微信WAP,微信公众号),42(支付宝),44(支付宝WAP),36(QQ扫码),45(QQ支付WAP)
		$unpayparam .= '&noticeurl='.yii::$app->params['backend'].yii::$app->params['unpay']['djqunpayhnotifyUrl'];    //异步通知地址,不能带任何参数,否则异步通知不会成功
		$unpayparam .= '&backurl='.$payurl;   //付款成功,同步跳转（不带参数）
		$sign = Unpayutil::getSignMsg($unpayparam, yii::$app->params['unpay']['key']);//key值请联系商务人员获取
		$unpayparam .= '&sign='.$sign;
		$unpayparam .= '&mark=16you';  //数字+字母 不能存在中文 例如：test123
		$url = 'http://api.unpay.com/PayMegerHandler.ashx?'.$unpayparam;   //'http://api.unpay.com/PayMegerHandler.ashx?'.
		return  $url;
	}
}