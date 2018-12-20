<?php
namespace pc\controllers;

use yii;
use pc\controllers\BaseController;
use common\common\Wxpayutil;
use common\models\Order;
use common\models\Configuration;
use common\common\Helper;
use common\common\Sftpayutil;
use common\models\Plateform;
use common\models\User;
use common\common\Unpayutil;

class PayController extends BaseController{
    /**
	 * 异步获取支付信息
	 */
	public function actionGetdata(){
		if(Yii::$app->request->isAjax){
			$msg = '';
			(!isset($_POST['gid'])) && $msg = 'gid required';
			(!isset($_POST['out_trade_no'])) && $msg = 'out_trade_no required';
			(!isset($_POST['product_id'])) && $msg = 'product_id required';
			(!isset($_POST['body'])) && $msg = 'body required';
			(!isset($_POST['total_fee'])) && $msg = 'total_fee required';
			(!isset($_POST['detail'])) && $msg = 'detail required';
			(!isset($_POST['attach'])) && $msg = 'attach required';
			(!isset($_POST['sign'])) && $msg = 'sign required';
			if($msg){
				return json_encode(['errorcode'=>'1001','msg'=>$msg]);
			}
			$gid = Helper::filtdata($_POST['gid'],'INT');             //游戏id
			$out_trade_no = Helper::filtdata($_POST['out_trade_no']);    //'厂商订单编号',
			$product_id = Helper::filtdata(($_POST['product_id']));        //'商品id',
			$total_fee = Helper::filtdata(($_POST['total_fee']),'MONEY');  //'支付总金额	以分为单位 必须大于0', 
			$body = Helper::filtdata(($_POST['body'])); 					 //订单或商品的名称',
			$detail = Helper::filtdata(($_POST['detail']));				 //订单或商品的详情',	
			$attach = Helper::filtdata(($_POST['attach']));				 //	后台通知时原样返回
			$sign = Helper::filtdata(($_POST['sign']));
			$user = Yii::$app->session->get('user');				     //'请求参数签名'
			if(($out_trade_no||$product_id)==false ||$out_trade_no===false||$product_id===false || $total_fee===false ||$sign===false ||!$user  ){
				return json_encode(['errorcode'=>'1001','msg'=>'非法访问，参数错误']);
			}
			$Configuration = Configuration::findOne(['gid'=>$gid]);
			if(!$Configuration){
				return json_encode(['errorcode'=>'1001','msg'=>'非法访问，key错误']);
			}
			if(isset($_POST['district_id'])){
            	$district_id = Helper::filtdata($_POST['district_id']);
            }else{
            	$district_id = '';
            }
            $darr = ['out_trade_no'=>$out_trade_no,'product_id'=>$product_id,'total_fee'=>$total_fee,'body'=>$body,'detail'=>$detail,'attach'=>$attach];
            $sign_res = Helper::getSign($darr,$Configuration->key); //获取签名
		    if($sign_res!=$sign || $sign_res==''){ //比较签名，并且不能为空
				return json_encode(['errorcode'=>'1001','msg'=>'非法访问，签名错误']);
			} 
			$session_res = Helper::setusersession($user);//验证用户session
			if($session_res){
				$user = yii::$app->session['user'];
			}
			//获取个人信息，和报名信息，判断是否存在以及是否可以支付
			$order = new Order();
			$order->gid = $gid;
			$order->uid = $user->id;
			$order->propname = urldecode($body);//道具名
			$order->price = $total_fee/100; 
			$order->state = 1;
			$order->num = 1;
			$order->pid = ($user->pid == 13 || $user->pid==20)?14:$user->pid;//平台id
			$order->orderID = $out_trade_no;//厂商订单编号
			$order->transaction_id = 'wxp'.date('YmdHis',time()).rand(1000,9999);//交易编号
			$order->detail = urldecode($detail);
			$order->attach = $attach;//厂商附加数据
			$order->product_id = $product_id;//厂商编号
			// $order->districtID = $districtID;
			$order->createtime = time();
			$order->ptype = 6;//支付类型
			$order->payclient = 2;  //支付端  2pc端
			$res = $order->save();
			if(!$res){		//保存失败
				return json_encode(['errorcode'=>'1001','msg'=>'保存失败']);
			}
			//满足支付条件，若价格为0，则直接更改数据库状态，无需调用微信支付
			if($order->price==0){
				$order->state = 2;
				$order->createtime = time();
				$res = $order->save();
				if($res){
					return json_encode(['errorcode'=>'1000','msg'=>'支付成功']);
				}else{
					return json_encode(['errorcode'=>'1001','msg'=>'网络异常，请刷新页面后再重试']);
				}
			}
			//满足支付条件，获取支付信息
			$wxpayutil = new Wxpayutil();
			if($attach==''){
				$attach = 'null';
			}
			$nativepay = $wxpayutil->nativepay($user->openid,$order->transaction_id,$total_fee/100,$body,$attach,$product_id);
			if($nativepay){		//支付程序正确
				return json_encode(['errorcode'=>'0','msg'=>'可支付', 'codeurl'=>$nativepay,'tid'=>$order->transaction_id]);
			}else if($nativepay==false){
				return json_encode(['errorcode'=>'ERROR','msg'=>'网络异常，请稍后再重试']);
			}else if($nativepay=='ORDERPAID'){		//该订单已支付
				return json_encode(['errorcode'=>'ORDERPAID','msg'=>'您的订单已支付']);
			}else if($nativepay=='OUT_TRADE_NO_USED'){		//订单号重复，则删除此人的订单号，并重新请求支付
				$order->transaction_id = null;
				$order->save(); 
				return json_encode(['errorcode'=>'OUT_TRADE_NO_USED']);	
			}
		}
	}
	
	
	
	/*
	 * gid ：游戏id
	* out_trade_no :厂商订单编号
	* product_id : 商品 id
	* body : 订单或商品的名称
	* total_fee : 支付总金额 以分为单位 必须大于 0
	* detail : 订单或商品的详情
	* attach : 附加数据 后台通知时原样返回
	* sign : 请求参数签名
	* ptype : 支付类型   1:微信支付  2：盛付通微信支付 3：盛付通支付宝支付 4：盛付通网银支付  5：盛付通H5快捷支付  6：微信扫码支付
	* payurl : 支付请求链接
	*/
	public function actionAllsftpay(){
		if(Yii::$app->request->isAjax){
			$msg = '';
			(!isset($_POST['gid'])) && $msg = 'gid required';
			(!isset($_POST['out_trade_no'])) && $msg = 'out_trade_no required';
			(!isset($_POST['product_id'])) && $msg = 'product_id required';
			(!isset($_POST['body'])) && $msg = 'body required';
			(!isset($_POST['total_fee'])) && $msg = 'total_fee required';
			(!isset($_POST['detail'])) && $msg = 'detail required';
			(!isset($_POST['attach'])) && $msg = 'attach required';
			(!isset($_POST['sign'])) && $msg = 'sign required';
			(!isset($_POST['ptype'])) && $msg = 'ptype required';
			if($msg){
				return json_encode(['errorcode'=>'1001','msg'=>$msg]);
			}
			$gid = Helper::filtdata($_POST['gid'],'INT');             //游戏id
			$out_trade_no = Helper::filtdata($_POST['out_trade_no']);    //'厂商订单编号',
			$product_id = Helper::filtdata(($_POST['product_id']));        //'商品id',
			$total_fee = Helper::filtdata(($_POST['total_fee']),'MONEY');  //'支付总金额	以分为单位 必须大于0',
			$body = Helper::filtdata(($_POST['body'])); 					 //订单或商品的名称',
			$detail = Helper::filtdata(($_POST['detail']));				 //订单或商品的详情',
			$attach = Helper::filtdata(($_POST['attach']));				 //	后台通知时原样返回
			$sign = Helper::filtdata(($_POST['sign']));                  //签名
			$ptype = Helper::filtdata($_POST['ptype'],'INT');            //支付类型
			$payurl = Helper::filtdata($_POST['payurl']);              //前端跳转页面
			if(($out_trade_no||$product_id)==false ||$out_trade_no===false||$product_id===false || $total_fee===false ||$sign===false  || $ptype===false ){
				return json_encode(['errorcode'=>'1001','msg'=>'非法访问，参数错误']);
			}
			$Configuration = Configuration::findOne(['gid'=>$gid]);
			if(!$Configuration){
				return json_encode(['errorcode'=>'1001','msg'=>'非法访问，key错误']);
			}
			if(isset($_POST['district_id'])){
				$district_id = Helper::filtdata($_POST['district_id']);
			}else{
				$district_id = '';
			}
			$darr = ['out_trade_no'=>$out_trade_no,'product_id'=>$product_id,'total_fee'=>$total_fee,'body'=>$body,'detail'=>$detail,'attach'=>$attach];
			$darr1 = json_encode($darr);
			$sign_res =  Helper::getSign($darr,$Configuration->key); //获取签名
			if($sign_res!=$sign || $sign_res==''){ //比较签名，并且不能为空
				return json_encode(['errorcode'=>'1001','msg'=>'非法访问，签名错误']);
			}
	
			$order = new Order();
			if( Yii::$app->session->get('user')){
				$user = Yii::$app->session->get('user');
				$session_res = Helper::setusersession($user);//验证用户session
				if($session_res){
					$user = yii::$app->session['user'];
				}
				$uid = $user->id;
				$pid = $user->pid;
				$memberid = $user->openid;      //商户会员标识
			}else if(yii::$app->session['touristid']){ //游客支付
				$uid = yii::$app->session['touristid'];
				$puid = yii::$app->session['puid'];
				$plateform = Plateform::findOne(['punid'=>$puid,'state'=>1]);
				if(!$plateform){
					return json_encode(['errorcode'=>'1001','msg'=>'该平台暂时还未开放，敬请期待！']);
				}
				$pid = $plateform->id;
				$memberid = $uid;
				$order->logintype = 2;
			}else{
				return json_encode(['errorcode'=>'1001','msg'=>'网络异常，请刷新页面再试！']);
			}
			$order->gid = $gid;
			$order->uid = $uid;
			$order->propname = urldecode($body);//道具名
			$order->price = $total_fee/100;
			$order->state = 1;
			$order->num = 1;
			$order->pid = ($pid==13 || $pid==20)?14:$pid;//平台id
			$order->orderID = $out_trade_no;//厂商订单编号
			$order->transaction_id = 'sft'.date('YmdHis',time()).rand(1000,9999);//交易编号
			$order->detail = urldecode($detail);
			$order->attach = $attach;//厂商附加数据
			$order->product_id = $product_id;//厂商编号
			$order->districtID = $district_id;
			$order->createtime = time();
			$order->ptype = $ptype;
			$order->payclient = 2;  //支付端  2pc端
			if($order->save()){
				$payres = '';
				$requesturl = '';
				if($ptype==2 || $ptype==3){//2：盛付通微信支付 3：盛付通支付宝支付
					$requesturl = 'https://cardpay.shengpay.com/mobile-acquire-channel/cashier.htm';   //请求的链接
					$payres = $this->getsftpay($ptype,$order,$payurl);
				}elseif($ptype==5){ //5：盛付通H5快捷支付 
					$requesturl = 'https://api.shengpay.com/html5-gateway/express.htm?page=mobile';   //请求的链接
					$payres = $this->getsfthpay($memberid,$order,$payurl);
				}elseif($ptype==7){ //7:盛付通微信扫码支付
					$requesturl ='http://mgw.shengpay.com/web-acquire-channel/pay/order.htm';
					$payres = json_decode($this->getsftwxscanpay($order,$payurl),true);
					if($payres['errorcode']!=0){
						return json_encode([
								'errorcode'=>1001,
								'msg'=>'网络异常，稍后再试',
						]);
					}
				}elseif($ptype==8){//8：游币支付
			         $cpayresarr = $this->gamecurrencypay($user->id,$order);
			         if($cpayresarr['errorcode']==1002){
			         	return json_encode([
			         			'errorcode'=>1002,
			         			'msg'=>'游币不足',
			         			]);
			         }else if($cpayresarr['errorcode']==1001){
			         	return json_encode([
			         			'errorcode'=>1001,
			         			'msg'=>'网络异常，稍后再试',
			         			]);
			         }
				}elseif($ptype==9){//9:优赋微信H5支付
					$requesturl = $this->unhpay($order, $payurl,'41');
				}elseif($ptype==10){//10:优赋微信扫码支付
					$requesturl = $this->unhpay($order, $payurl,'32');
				}elseif($ptype==11){//10:支付宝扫码
					$requesturl = $this->unhpay2($order, $payurl,'42');
				}elseif($ptype==12){//12:支付宝app
					$requesturl = $this->unhpay($order, $payurl,'44');
				}
				return json_encode([
						'errorcode'=>0,
						'msg'=>$payres,
						'requesturl'=>$requesturl,
						'transaction_id'=>$order->transaction_id,
						]);
			}else{
				return json_encode([
						'errorcode'=>1001,
						'msg'=>'网络异常，稍后再试',
						]);
			}
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
	 * 盛付通微信扫码支付
	 * $ptype = 7    盛付通微信扫码支付
	 */
	private function getsftwxscanpay($order,$payurl){
		$Sftpayutil = new Sftpayutil();
		$sftparamarr = array();
		$sftparamarr['merchantNo'] = yii::$app->params['sftpay']['MsgSender'];        //商户号
		$sftparamarr['charset'] = 'UTF-8';          //字符集
		$sftparamarr['requestTime'] = date('YmdHis');  //请求时间
		$sftparamarr['merchantOrderNo'] = $order->transaction_id;     //商户订单号
		$sftparamarr['amount'] = $order->price; //价格
		$sftparamarr['expireTime'] = date('YmdHis'); 	     //订单创建时间
		$sftparamarr['productName'] = $order->propname;          //商品名称
		$sftparamarr['currency'] = 'CNY';   //货币类型
		$sftparamarr['userIp'] = $_SERVER["REMOTE_ADDR"];  //用户IP
		$sftparamarr['payChannel'] = 'wp';    // PT312
		$sftparamarr['pageUrl'] = $payurl;
		$sftparamarr["exts"] = '';// $Sftpayutil->getExt();        //扩展2 json对象
		$sftparamarr['notifyUrl'] = yii::$app->params['backend'].yii::$app->params['sftpay']['HnotifyUrl']; //后台通知回调地址
		$data = json_encode($sftparamarr);
		$reqPar = $data.yii::$app->params['sftpay']['key'];
		$signature  = strtoupper(MD5($reqPar));
		$headers = array(
				"Content-Type:application/json;charset='utf-8'",
				'signType: MD5',
				'signMsg:'.$signature,
		);
		$requesturl ='http://mgw.shengpay.com/web-acquire-channel/pay/order.htm';
		$Sftpayutil = new Sftpayutil();
		$curlresult = $Sftpayutil->curlPost($requesturl,$data,$headers);
		return $curlresult;
	}

	/**
	 * 游币支付
	 */
	private function gamecurrencypay($uid,$order){
		$connection = Yii::$app->db->beginTransaction();//开启事务
		$user = User::findOne(['id'=>$uid]);
		if(!$user){
			return ['errorcode'=>1001,'msg'=>'用户不存在'];
		}
	
		if($user->currencynum<$order->price){ //游币不足
			return ['errorcode'=>1002,'msg'=>'用户不足'];
		}
		$order->state = 2;  //支付成功
		$user->currencynum = $user->currencynum - $order->price;
		if($user->save()&& $order->save()){
			$connection->commit();//事物提交
			$this->notifycurrency($order);
			yii::$app->session['user'] = $user;  //更新user缓存
			return ['errorcode'=>0,'msg'=>'支付成功'];
		}else{
			$connection->rollBack();//事物回滚
			return ['errorcode'=>1001,'msg'=>'支付失败'];
		}
	}
	
	/**
	 * 通知后台回调
	 */
	private function notifycurrency($order){
		$data['total_fee'] = $order->price*100;
		$data['transaction_id'] = $order->transaction_id;
		$url = yii::$app->params['backend'].'/notify/currencynotify.html';
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);//用PHP取回的URL地址
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);//禁用后cURL将终止从服务端进行验证
		if (defined('CURLOPT_SAFE_UPLOAD')) {
			curl_setopt($curl, CURLOPT_SAFE_UPLOAD, false);
		}
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);//设定是否显示头信息
		if(!empty($data)){
			curl_setopt($curl,CURLOPT_POST,1);//如果你想PHP去做一个正规的HTTP POST，设置这个选项为一个非零值。这个POST是普通的 application/x-www-from-urlencoded 类型，多数被HTML表单使用
			curl_setopt($curl,CURLOPT_POSTFIELDS,$data);//传递一个作为HTTP “POST”操作的所有数据的字符串
		}
		$output = curl_exec($curl);
		curl_close($curl);
		if(!$output){
			return false;
		}
	}
	
	/**
	 * 优赋H5支付
	 * $ptype = 7    优赋微信H5支付
	 */
	private function unhpay($order,$payurl,$cardno){
		$unpayparam = '';
		$unpayparam .= 'customerid='.yii::$app->params['unpay']['MsgSender']; //商户在网关系统上的商户号
		$unpayparam .= '&sdcustomno='. $order->transaction_id;     //订单在商户系统中的流水号
		$unpayparam .= '&orderAmount='.($order->price*100);     //订单支付金额 以分为单位
		$unpayparam .= '&cardno='.$cardno;   //支付类型:32(微信扫码),41(微信WAP,微信公众号),42(支付宝),44(支付宝WAP),36(QQ扫码),45(QQ支付WAP)
		$unpayparam .= '&noticeurl='.yii::$app->params['backend'].yii::$app->params['unpay']['unpayhnotifyUrl'];    //异步通知地址,不能带任何参数,否则异步通知不会成功
		$unpayparam .= '&backurl='.$payurl;   //付款成功,同步跳转（不带参数）
		$sign = Unpayutil::getSignMsg($unpayparam, yii::$app->params['unpay']['key']);//key值请联系商务人员获取
		$unpayparam .= '&sign='.$sign;
		$unpayparam .= '&mark=16you';  //数字+字母 不能存在中文 例如：test123
		$url = 'http://api.unpay.com/PayMegerHandler.ashx?'.$unpayparam;   //'http://api.unpay.com/PayMegerHandler.ashx?'.
		return  $url;
	}
	
	/**
	 * 优赋H5支付
	 * $ptype = 7    优赋微信H5支付
	 */
	private function unhpay2($order,$payurl,$cardno){
		$unpayparam = '';
		$unpayparam .= 'customerid='.yii::$app->params['unpay']['MsgSender']; //商户在网关系统上的商户号
		$unpayparam .= '&sdcustomno='. $order->transaction_id;     //订单在商户系统中的流水号
		$unpayparam .= '&orderAmount='.($order->price*100);     //订单支付金额 以分为单位
		$unpayparam .= '&cardno='.$cardno;   //支付类型:32(微信扫码),41(微信WAP,微信公众号),42(支付宝),44(支付宝WAP),36(QQ扫码),45(QQ支付WAP)
		$unpayparam .= htmlspecialchars('&noticeurl=').yii::$app->params['backend'].yii::$app->params['unpay']['unpayhnotifyUrl'];    //异步通知地址,不能带任何参数,否则异步通知不会成功
		$unpayparam .= '&backurl='.$payurl;   //付款成功,同步跳转（不带参数）
		$sign = Unpayutil::getSignMsg($unpayparam, yii::$app->params['unpay']['key']);//key值请联系商务人员获取
		$unpayparam .= '&sign='.$sign;
		$unpayparam .= '&mark=16you';  //数字+字母 不能存在中文 例如：test123
		$unpayparam .= '&zftype=1';
		$url = 'http://api.unpay.com/PayMegerHandler.ashx?'.$unpayparam;   //'http://api.unpay.com/PayMegerHandler.ashx?'.
		return  $url;
	}
}