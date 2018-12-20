<?php
namespace frontend\controllers;

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
	/**
	 * 异步获取支付信息
	 */
	public function actionGetdata(){
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
			//满足支付条件，获取支付信息
			$wxpayutil = new Wxpayutil();
			$jsApiObj = $wxpayutil->pay($user->openid,$voucher->transaction_id,$price,'16游代金券','16youdaijinquanpaymm',$voucher->transaction_id);
			if(is_array($jsApiObj)){		//支付程序正确
				return json_encode(['errorcode'=>'0','msg'=>'可支付', 'jsApiParameters'=>$jsApiObj]);
			}else if($jsApiObj==false){
				return json_encode(['errorcode'=>'ERROR','msg'=>'网络异常，请稍后再重试']);
			}else if($jsApiObj=='ORDERPAID'){		//该订单已支付
				return json_encode(['errorcode'=>'ORDERPAID','msg'=>'您的订单已支付']);
			}else if($jsApiObj=='OUT_TRADE_NO_USED'){		//订单号重复，则删除此人的订单号，并重新请求支付
				$voucher->transaction_id = null;
				$voucher->save(); 
				return json_encode(['errorcode'=>'OUT_TRADE_NO_USED']);	
			}
		} 
	}

	/*
	 * 保存游戏记录到redis
	*/
	private function savegameredis($game){
		$gameredis = new GameRedis();
		$gameredis->name = $game['name'];
		$gameredis->cid = $game['cid'];
		$gameredis->descript = $game['descript'];
		$gameredis->unique= $game['unique'];
		$gameredis->state = $game['state'];
		$gameredis->label = $game['label'];
		$gameredis->intro = $game['intro'];
		$gameredis->game_url =$game['game_url'];
		$gameredis->type = $game['type'];
		$gameredis->sort =$game['sort'];
		$gameredis->image = $game['image'];
		$gameredis->head_img = $game['head_img'];
		$gameredis->game_type = $game['game_type'];
		$gameredis->createtime = $game['createtime'];
		$gameredis->save();
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
	* ptype : 支付类型   1:微信支付  2：盛付通微信支付 3：盛付通支付宝支付 4：盛付通网银支付  5：盛付通H5快捷支付  6：微信扫码支付   7: 盛付通微信扫码支付  8：游币支付   9：优赋H5支付
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
			if(isset($_POST['district_id'])){  //区服ID
				$district_id = Helper::filtdata($_POST['district_id']);
			}else{
				$district_id = '';
			}
			$darr = ['out_trade_no'=>$out_trade_no,'product_id'=>$product_id,'total_fee'=>$total_fee,'body'=>$body,'detail'=>$detail,'attach'=>$attach];
			$darr1 = json_encode($darr);
			$order = new Order();
			if( Yii::$app->session->get('user')){
				$user = Yii::$app->session->get('user');
				$uid = $user->id;
				$pid = $user->pid;
				$memberid = $user->openid;      //商户会员标识
			}else if(yii::$app->session['touristid']){ //游客支付
				$uid = yii::$app->session['touristid'];
				$puid = yii::$app->session['puid'];
				$plateform =  Plateform::findOne(['punid'=>$puid,'state'=>1]);
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
			$order->pid = $pid;//平台id
			$order->orderID = $out_trade_no;//厂商订单编号
			$order->transaction_id = 'sft'.date('YmdHis',time()).rand(1000,9999);//交易编号
			$order->detail = urldecode($detail);
			$order->attach = $attach;//厂商附加数据
			$order->product_id = $product_id;//厂商编号
			$order->districtID = $district_id;
			$order->createtime = time();
			$order->ptype = $ptype;
			$order->payclient = 1;  //支付端  1 公众号端
			if($order->save()){
				$payres = '';
				$requesturl = '';
				if($ptype==2 || $ptype==3){
					$requesturl = 'https://cardpay.shengpay.com/mobile-acquire-channel/cashier.htm';   //请求的链接
					$payres = $this->getsftpay($ptype,$order,$payurl);
				}elseif($ptype==5){
					$requesturl = 'https://api.shengpay.com/html5-gateway/express.htm?page=mobile';   //请求的链接
					$payres = $this->getsfthpay($memberid,$order,$payurl);
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
				}elseif($ptype==9){
					$payres = $this->unhpay($order, $payurl,'41');
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
		/* $customerid = yii::$app->params['unpay']['MsgSender']; //商户在网关系统上的商户号
		$sdcustomno = $order->transaction_id; //订单在商户系统中的流水号
		$orderAmount = $order->price*100;   //订单支付金额 以分为单位
		$cardno = '41';//支付类型:32(微信扫码),41(微信WAP,微信公众号),42(支付宝),44(支付宝WAP),36(QQ扫码),45(QQ支付WAP)
		$noticeurl = yii::$app->params['backend'].yii::$app->params['unpay']['unpayhnotifyUrl']; //异步通知地址,不能带任何参数,否则异步通知不会成功
		$backurl =  $payurl; //付款成功,同步跳转（不带参数）
		$key = yii::$app->params['unpay']['key'];//key值请联系商务人员获取
		$mark='16you';//数字+字母 不能存在中文 例如：test123
		$remarks='16you';//简短的中文说明,为空时,取mark
		$zftype='1';//返回类型 1:直接跳转 2:返回xml 3:返回json
		$Md5str='customerid='.$customerid.'&sdcustomno='.$sdcustomno.'&orderAmount='.$orderAmount.'&cardno='.$cardno.'&noticeurl='.$noticeurl.'&backurl='.$backurl.$key;
		echo $Md5str.'===';
		$sign=strtoupper(md5($Md5str));
		$url .='?'.'customerid='.$customerid.'&sdcustomno='.$sdcustomno.'&orderAmount='.$orderAmount.'&cardno='.$cardno.'&noticeurl='.$noticeurl.'&backurl='.$backurl .'&remarks='.$remarks.'&sign='.$sign.'&mark='.$mark.'&zftype=1';
		 *///$unpayparam .= '&zftype=';
		/* echo 'url=========='.$url.'-----';
		$res = Unpayutil::http_get($url);
		var_dump($res);exit; */
		
		
		//$res = Unpayutil::https_request($url, $unpayparamarr);
		//var_dump($res);
	}
}