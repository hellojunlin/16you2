<?php
namespace backend\controllers;
use yii;
use yii\log\Logger;
use yii\web\Controller;
use common\pay\Wxnotify_pub;
use common\models\Order;
use common\models\Game;
use common\models\User;
use common\models\Integral;
use common\models\Configuration;
use common\common\Wxinutil;
use common\common\Helper;
use common\redismodel\UserRedis;
use common\models\Acquire;
use common\common\Sftpayutil;
use common\models\Rebatecurrencytemp;
use common\models\Voucher;
use common\models\Gamecurrency;

/**
 * 微信支付后台通知
 * 1.重复通知的处理
 * 2.检查对应业务的状态，判断是否处理过
 * 		=>未处理过：处理业务逻辑
 * 		=>处理过：直接返回结果
 * 3.使用数据锁进行并发控制，避免函数重入造成的数据混乱
 * @author HanksGump
 *
 */
class NotifyController extends Controller{
	public function init(){
		$wxinfo = Yii::$app->session->get('wxinfo');
		if(!isset($wxinfo)||!is_array($wxinfo)){		//实例化该类时，先缓存微信信息
			Yii::$app->session->set('wxinfo',Yii::$app->params['wxinfo']);
		}
	}
	
	
	/**
	 * 微信通知
	 */
	public function actionWxnotify(){
		$notify = new Wxnotify_pub();
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
		$notify->saveData($xml);
		
		//验证签名，并回应
		if($notify->checkSign()==TRUE){
			if($notify->data['return_code']=='FAIL'){		//通信出错
				$this->wxlogerrcode('return_code fail' ,$notify->data['return_msg'], '支付');
				$notify->setReturnParameter("return_code","FAIL");//返回状态码
				$notify->setReturnParameter("return_msg","通信出错");//返回信息
			}elseif($notify->data['result_code']=='FAIL'){		//业务出错
				$this->wxlogerrcode($notify->data['err_code'] ,$notify->data['err_code_des'], '支付');
				$notify->setReturnParameter("result_code","FAIL");//返回状态码
				$notify->setReturnParameter("return_msg","业务出错");//返回信息
			}elseif($notify->data['return_code']=='SUCCESS'&&$notify->data['result_code']=='SUCCESS'){		//支付成功通知
				$openid = $notify->data['openid'];
				$total_fee = $notify->data['total_fee']/100;	//总金额
				$transaction_id = $notify->data['transaction_id'];	//微信支付订单号
				$out_trade_no =  $notify->data['out_trade_no'];		//商户系统的订单号
				$time_end = $notify->data['time_end'];		//支付完成时间
				$attach = $notify->data['attach'];
				if($attach == '16youdaijinquanpaymm'){//代金券通知 购买代金券时才进入
					$shopnotres = $this->shoppaynotify($out_trade_no);
					if($shopnotres){
						$notify->setReturnParameter("return_code","SUCCESS");//设置返回码
						$returnXml = $notify->returnXml();
						echo $returnXml;
					}
					return false;
				}
				//数据锁并发操作
				//1.查到该订单，验证订单状态是否处理过
				$order = Order::findOne(['transaction_id'=>$out_trade_no]);
				if($order){
					if($order->state!=2){		//处于未付款状态,未处理过，则进行订单状态修改操作
						$now = time();
						$time = strtotime(date('Y-m-d'));
						$id = $order->uid;
						$order->state = 2;
						$order->createtime = $now;
						$todayordernum = 0;     //今日订单数
						//判断是否为新付款用户
						$_yew = Order::find()->where(['uid'=>$id,'state'=>2])->orderBy('createtime')->asArray()->all();
						$num = $order->price;
						if($_yew){
							$ogid = $order->gid;
							$order->first_time = strtotime(date('Y-m-d',$_yew['0']['createtime']));
							$order->utype = 1;
							foreach ($_yew as $vy) {
								if(!$order->gfirst_time && $vy['gid']==$ogid){//非游戏新付款用户
									$order->gtype = 1;
									$order->gfirst_time = strtotime(date('Y-m-d',$vy['createtime']));
								}
								$num = $num+$vy['price'];//计算vip等级
								if($vy['createtime']>$time){// 统计今日的订单数
								//	yii::trace('--------------今日充值数-------------------'.$todayordernum);
									$todayordernum = $todayordernum+1;
								}
							}
							//yii::trace('--------------今日充值数222-------------------'.$todayordernum);
							if($order->gtype!=1){
								$order->gtype = 2;
								$order->gfirst_time = $time;
							}
						}else{
							$order->gtype = 2;//新付款用户
							$order->utype = 2;//新付款用户
							$order->gfirst_time = $time;
							$order->first_time = $time;
						}
						$res = $order->save();
						if(!$res){		//若保存失败，则终止，等待微信的第二次通知进行处理
							exit();
						}
						$vip = Helper::vipSort($num);
						$user = User::findOne($id);
						if($user){
							$integralnum = $this->saveintegral($vip['num'], $order->price,$todayordernum,$user);   //保存积分到数据库，并返回获得的积分
							$user->vip = $vip['num'];
							//$user->integral = $user->integral+ $integralnum;
							$user->save();
						}
					}else{
						$this->wxlogerrcode('repeatnotify' ,'该订单已支付，可能属于重复通知状态', '支付');
					} 
				}else{
					$this->wxlogerrcode('nosignrecord' ,'支付了一笔未存在该订单号的订单', '支付');
				}
				//2.将成功结果发送给游戏方，处理过后，则直接返回true给微信
				$config = Configuration::findOne(['gid'=>$order->gid]);
				if($config){
					$guniqe = Game::find()->where(['id'=>$order->gid])->select('unique')->asArray()->one();
					$data['trade_status'] = 'SUCCESS';
					$data['game'] = $guniqe?$guniqe['unique']:'';
					$data['partnerid'] = $config->partnerid;
					$data['userid'] = $order->uid;
					$data['total_fee'] = $order->price*100;
					$data['transaction_id'] = $order->transaction_id;
					$data['out_trade_no'] = $order->orderID;
					$data['product_id'] = $order->product_id;
					$data['attach'] = $order->attach;
					$data['pay_time'] = date('Y-m-d H:i:s',$order->createtime);
					$data['timestamp'] = time();
					$data['sign'] = Helper::getSign($data,$config->key); //获取签名
					// $data['district_id'] = $order->districtID;
					$url = $config->type_url;
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
		    			yii::trace("------------------------------------------$output------------");
		    			exit;
		    		}
					$_order = clone $order;
					if($output=='SUCCESS'){
						//$this->rebate($order);   //五一活动
						$file = dirname(dirname(__FILE__))."/runtime/notify_log.txt";
						$myfile = fopen($file, "a+");
						$txt = date('Y-m-d H:i:s').'---------回调参数:'.json_encode($data)."\r\n回调地址：".$url."\r\n\r\n";
						fwrite($myfile, $txt);
						fclose($myfile);
						$_order->type = 1;//成功
						$_order->save();
						$notify->setReturnParameter("return_code","SUCCESS");//设置返回码
						$returnXml = $notify->returnXml();
						echo $returnXml; 
					}else{
						$_order->type = 2;//失败
						$_order->save();
					}
				}
			}
		}
		
	}
	
	/**
	 * 商城代金券通知回调
	 */
	private function shoppaynotify($transaction_id){
		$voucherobj = Voucher::findOne(['transaction_id'=>$transaction_id]);     //查找该代金券
		if(!$voucherobj){
			yii::trace("---------该订单号不存在:$transaction_id-----------");
			return false;
		}
		$connection = Yii::$app->db->beginTransaction();//开启事务
		if($voucherobj->state!=2){//处于未付款状态,未处理过，则进行订单状态修改操作
			$voucherobj->state = 2;
		}
		$gamecurrencyobj = new Gamecurrency();
		$gamecurrencyobj->uid = $voucherobj->uid;
		$gamecurrencyobj->state = 2;   //审核通过
		$gamecurrencyobj->currencynum = $voucherobj->currencynum;  //游戏币值
		$gamecurrencyobj->createtime = $voucherobj->createtime;      //创建时间
		$gamecurrencyobj->checkcreatetime = time();  //审核时间
		$gamecurrencyobj->remark = '购买代金券';
		$gamecurrencyobj->source = 2;  //2:购买代金券
		$user = User::findOne(['id'=>$voucherobj->uid]);
		if(!$user){
			yii::trace("---------该用户不存在:$voucherobj->uid-----------");
			return false;
		}
		$user->currencynum = $user->currencynum + $voucherobj->currencynum;
		if($voucherobj->save() && $gamecurrencyobj->save() && $user->save()){
			$voucherobj->type = 1;//成功
			$voucherobj->save();
			$connection->commit();//事物提交
			return true;
		}else{
			yii::trace("---------代金券通知回调失败，订单号为:$transaction_id-----------");
			$connection->rollBack();//事物回滚
			return false;
		}
	}
	
	/**
	 * 保存积分
	 * $vip  vip等级
	 * $price 充值金额
	 * $todayordernum   今日充值订单数
	 * $user 用户信息
	 * return $integralnum 返回积分
	 */
	private function saveintegral($vip,$price,$todayordernum,$user){
		$integralnum = 0;
		if($todayordernum==0){//今日首单
			$integralnum = 50;
			$integral = new Integral();
			$integral->type = 4; //每日首充
			$integral->integral = 50;
			$integral->uid = $user->id;
			$integral->createtime = time();
			$res = $integral->save();
			if(!$res){
				yii::trace('---------------今日首单充值积分保存失败，用户ID是'.$user->id.',应获取的积分是：50 -时间是：'.time().',只需在积分表添加记录，无需往用户添加积分---------------------------------');
			}
		}
		$vipintegralrule = isset(yii::$app->params['integralrule'][$vip])? yii::$app->params['integralrule'][$vip] :0;
		$integral = new Integral();
		$integral->type = 5; //充值
		$integral->integral = round($price*10+$vipintegralrule*$price);//获得的积分
		$integral->uid = $user->id;
		$integral->createtime = time();
		$res = $integral->save();
		if(!$res){
			yii::trace('---------------充值积分保存失败，用户ID是'.$user->id.',应获取的积分是：'.$integralnum.' -时间是：'.time().',只需在积分表添加记录，无需往用户添加积分-------------------------------');
		}
		$integralnum = $integralnum + $integral->integral;
		return $integralnum;
	}
	
	/**
	 * 记录微信支付的异常通知
	 * @param  $err_code  微信错误码
	 * @param  $err_code  接口类型
	 */
	private function wxlogerrcode($err_code,$wxmsg,$type){
		$time = date('Y-m-d H:i:s');
	 	$msg = "";
		switch($err_code){
			case 'SYSTEMERROR':$msg = '接口后台错误';break;
			case 'INVALID_TRANSACTIONID':$msg = '无效 transaction_id';break;
			case 'PARAM_ERROR':$msg = '提交参数错误';break;
			case 'ORDERPAID':$msg = '订单已支付';break;
			case 'OUT_TRADE_NO_USED':$msg = '商户订单号重复';break;
			case 'NOAUTH':$msg = '商户无权限';break;
			case 'NOTENOUGH':$msg = '余额不足';break;
			case 'NOTSUPORTCARD':$msg = '丌支持卡类型';break;
			case 'ORDERCLOSED':$msg = '订单已关闭';break;
			case 'BANKERROR':$msg = '银行系统异常';break;
			case 'REFUND_FEE_INVALID':$msg = '退款金额大亍支付金额';break;
			case 'ORDERNOTEXIST':$msg = '订单丌存在';break;
			default:$msg=$err_code;
		}
		 
		Yii::getLogger()->log("----$time----微信".$type."异常通知：$err_code---错误描述：$wxmsg-------", Logger::LEVEL_WARNING);
	}
	
	
	/**
	 * 盛付通微信、支付宝、H5快捷支付、微信扫码支付通知
	 * 盛付通后台通过notifyUrl通知商户,商户做业务处理后,需要以字符串(OK)的形式反馈处理结果处理成功,盛付通系统收到此结果后不再进行后续通知
	 * 处理自己相关的逻辑，可以选择入库，然后，前台隔断时间扫描数据库获取相关标识是否获取到数据
	 */
	public function actionSfthnotify(){
		try {
			$Name =  Helper::filtdata($_POST["Name"]);           //版本名称
			$Version = Helper::filtdata($_POST["Version"]);       //版本号
			$Charset = Helper::filtdata($_POST["Charset"]);       //字符集
			$TraceNo = Helper::filtdata($_POST["TraceNo"]);       //请求序列号
			$MsgSender = Helper::filtdata($_POST["MsgSender"]);       //发送方标识
			$SendTime = Helper::filtdata($_POST["SendTime"]);       //发送支付请求时间
			$InstCode = Helper::filtdata($_POST["InstCode"]);       //银行编码
			$OrderNo = Helper::filtdata($_POST["OrderNo"]);       //商户订单号
			$OrderAmount = Helper::filtdata($_POST["OrderAmount"]);       //支付金额
			$TransNo = Helper::filtdata($_POST["TransNo"]);       //盛付通交易号
			$TransAmount = Helper::filtdata($_POST["TransAmount"]);       //盛付通实际支付金额
			$TransStatus = Helper::filtdata($_POST["TransStatus"]);       //支付状态
			$TransType = Helper::filtdata($_POST["TransType"]);       //版盛付通交易类型
			$TransTime = Helper::filtdata($_POST["TransTime"]);       //盛付通交易时间
			$MerchantNo = Helper::filtdata($_POST["MerchantNo"]);       //商户号
			$ErrorCode = Helper::filtdata($_POST["ErrorCode"]);       //错误代码
			$ErrorMsg = Helper::filtdata($_POST["ErrorMsg"]);       //错误消息
			$Ext1 = stripslashes($_POST["Ext1"]);       //扩展1
			$Ext2 = $_POST["Ext2"];       //扩展2
			$SignType = Helper::filtdata($_POST["SignType"]);       //签名串
			$SignMsg = Helper::filtdata($_POST["SignMsg"]);
			if($TransStatus!="01"){//状态不为01 则为付款不成功
				$this->sftlogerrcode($TransStatus, '盛付通H5快捷支付');
				exit;
			}
			$symbol =  ($SignType=="MD5")? "" :(($SignType=="RSA")? "|" :"");
			
			//第一步进行相关的验签操作
			$encryptCode =$this->isEmpty($Name)?"":$Name.$symbol;
			$encryptCode.=$this->isEmpty($Version)?"":$Version.$symbol;
			$encryptCode.=$this->isEmpty($Charset)?"":$Charset.$symbol;
			$encryptCode.=$this->isEmpty($TraceNo)?"":$TraceNo.$symbol;
			$encryptCode.=$this->isEmpty($MsgSender)?"":$MsgSender.$symbol;
			$encryptCode.=$this->isEmpty($SendTime)?"":$SendTime.$symbol;
			$encryptCode.=$this->isEmpty($InstCode)?"":$InstCode.$symbol;
			$encryptCode.=$this->isEmpty($OrderNo)?"":$OrderNo.$symbol;
			$encryptCode.=$this->isEmpty($OrderAmount)?"":$OrderAmount.$symbol;
			$encryptCode.=$this->isEmpty($TransNo)?"":$TransNo.$symbol;
			$encryptCode.=$this->isEmpty($TransAmount)?"":$TransAmount.$symbol;
			$encryptCode.=$this->isEmpty($TransStatus)?"":$TransStatus.$symbol;
			$encryptCode.=$this->isEmpty($TransType)?"":$TransType.$symbol;
			$encryptCode.=$this->isEmpty($TransTime)?"":$TransTime.$symbol;
			$encryptCode.=$this->isEmpty($MerchantNo)?"":$MerchantNo.$symbol;
			$encryptCode.=$this->isEmpty($ErrorCode)?"":$ErrorCode.$symbol;
			$encryptCode.=$this->isEmpty($ErrorMsg)?"":$ErrorMsg.$symbol;
			$encryptCode.=$this->isEmpty($Ext1)?"":$Ext1.$symbol;
			$encryptCode.=$this->isEmpty($Ext2)?"":$Ext2.$symbol;
			$encryptCode.=$this->isEmpty($SignType)?"":$SignType.$symbol;
			$sftpayutil = new Sftpayutil();
			
			$ret = '';
			switch ($SignType){
				case "MD5":
					$encryptCode=$encryptCode.yii::$app->params['sftpay']['key'];
					$mysignMsg= strtoupper(md5($encryptCode));
					if($mysignMsg==$SignMsg){
						$ret=1;
					}
					break;
				case "RSA":
					if ( $sftpayutil->rsaVerify($encryptCode,$SignMsg,OPENSSL_ALGO_MD5)) {//RSA 验证
						$ret=1;
					}
					break;
				case "NONEED":  //盛付通微信扫码支付 返回的类型是NONEED  但进行的还是MD5签名  此处有点较坑
					$encryptCode=$encryptCode.yii::$app->params['sftpay']['key'];
					$mysignMsg= strtoupper(md5($encryptCode));
					if($mysignMsg==$SignMsg){
						$ret=1;
					}
				break;
				default:
					break;
			}
			
			if($ret==1 && $TransStatus=="01"){ //签名验证
				$res = $this->successpaynotify($OrderNo, 'H5快捷支付',$InstCode); //成功付款通知游戏商
				if($res){//已成功通知则通知第三方支付
					echo "OK";
				}else{
					yii::trace("-------------------------盛付通通知失败-------------------------------");
				}
			}else{
				echo "verify faile";
				yii::trace("-------H5快捷支付------ErrorCode=--$ErrorCode------------ErrorMsg=$ErrorMsg----------------------------");
			}
		} catch (Exception $e) {
			$str="File:".$e->getFile()."line:".$e->getLine().";code=".$e->getCode().";message=".$e->getMessage();
			yii::trace("------------------------h5支付回调:$str----------------");
		}
	}
	
	
	
	/**
	 * 优赋H5支付通知回调
	 */
	public function actionUnpayhnotify(){
		$state=trim($_GET["state"]);            // 1:充值成功 2:充值失败
		$customerid=trim($_GET["customerid"]);  //商户注册的时候，分配的商户ID
		$sd51no=trim($_GET["sd51no"]);          //网关系统的订单号
		$sdcustomno=trim($_GET["sdcustomno"]);  //商户系统的流水号
		$ordermoney=trim($_GET["ordermoney"]);  //商户订单实际金额单位：（元）
		$cardno=trim($_GET["cardno"]);          //支付类型：32(微信扫码),41(微信WAP,微信公众号),42(支付宝),44(支付宝WAP),36(QQ扫码),45(QQ支付WAP)
		$mark=trim($_GET["mark"]);              //订单提交时的mark值
		$sign=trim($_GET["sign"]);             //一次签名字符串
		$resign=trim($_GET["resign"]);          //二次签名字符串
		$des=trim($_GET["des"]);                //订单支付描述备注
		$key=yii::$app->params['unpay']['key'];  //key值请联系商务人员获取
		$sign2=strtoupper(md5("customerid=".$customerid."&sd51no=".$sd51no."&sdcustomno=".$sdcustomno."&mark=".$mark."&key=".$key));
		$resign2=strtoupper(md5("sign=".$sign."&customerid=".$customerid."&ordermoney=".$ordermoney."&sd51no=".$sd51no."&state=".$state."&key=".$key));
		if($sign!=$sign2){
			yii::trace("-------------------------优赋 支付：一次签名不正确-------------------------------");
			//记录日志
			exit();
		}
		if($resign!=$resign2){
			yii::trace("-------------------------优赋 支付：二次签名不正确-------------------------------");
			//记录日志
			exit();
		}
		//判断异步返回的状态并且做相应处理
		if($state=="1"){
			//当充值成功后同步商户系统订单状态
			$res = $this->successpaynotify($sdcustomno, '优赋支付'); //成功付款通知游戏商
			if($res){//已成功通知则通知第三方支付
				yii::trace("-------------------------优赋支付：通知成功-------------------------------");
				//商户在接受到网关通知时，应该打印出<result>1</result>标签，以供接口程序抓取信息，获取是否通知成功的信息，否则订单网关系统会显示没有通知
				echo "<result>1</result>";
			}else{
				yii::trace("-------------------------优赋支付：通知失败-------------------------------");
			}
		}
		else if($state=="2"){
			//当充值失败后同步商户系统订单状态
			yii::trace("-------------------------优赋支付失败-订单号是：$sdcustomno------------------------------");
			//此处编写商户系统处理订单失败流程
			//商户在接受到网关通知时，应该打印出<result>1</result>标签，以供接口程序抓取信息，获取是否通知成功的信息，否则订单网关系统会显示没有通知
			echo "<result>1</result>";
			//记录订单处理日志
		}else{
			//异常处理部分（可选）,根据自己系统而定
			yii::trace("-------------------------优赋支付异常-------------------------------");
			echo "<result>0</result>";   //当返回<result>0</result>时网关系统会继续通知
			//记录订单处理日志
		}
	}
	
	/**
	 * 代金券优赋H5支付通知回调
	 */
	public function actionDjqunpayhnotify(){
		$state=trim($_GET["state"]);            // 1:充值成功 2:充值失败
		$customerid=trim($_GET["customerid"]);  //商户注册的时候，分配的商户ID
		$sd51no=trim($_GET["sd51no"]);          //网关系统的订单号
		$sdcustomno=trim($_GET["sdcustomno"]);  //商户系统的流水号
		$ordermoney=trim($_GET["ordermoney"]);  //商户订单实际金额单位：（元）
		$cardno=trim($_GET["cardno"]);          //支付类型：32(微信扫码),41(微信WAP,微信公众号),42(支付宝),44(支付宝WAP),36(QQ扫码),45(QQ支付WAP)
		$mark=trim($_GET["mark"]);              //订单提交时的mark值
		$sign=trim($_GET["sign"]);             //一次签名字符串
		$resign=trim($_GET["resign"]);          //二次签名字符串
		$des=trim($_GET["des"]);                //订单支付描述备注
		$key=yii::$app->params['unpay']['key'];  //key值请联系商务人员获取
		$sign2=strtoupper(md5("customerid=".$customerid."&sd51no=".$sd51no."&sdcustomno=".$sdcustomno."&mark=".$mark."&key=".$key));
		$resign2=strtoupper(md5("sign=".$sign."&customerid=".$customerid."&ordermoney=".$ordermoney."&sd51no=".$sd51no."&state=".$state."&key=".$key));
		if($sign!=$sign2){
			yii::trace("-------------------------优赋 支付：一次签名不正确-------------------------------");
			//记录日志
			exit();
		}
		if($resign!=$resign2){
			yii::trace("-------------------------优赋 支付：二次签名不正确-------------------------------");
			//记录日志
			exit();
		}
		//判断异步返回的状态并且做相应处理
		if($state=="1"){
			//当充值成功后同步商户系统订单状态
		    $this->shoppaynotify($sdcustomno);
			return false;
			
		}
		else if($state=="2"){
			//当充值失败后同步商户系统订单状态
			yii::trace("-------------------------优赋支付失败-订单号是：$sdcustomno------------------------------");
			//此处编写商户系统处理订单失败流程
			//商户在接受到网关通知时，应该打印出<result>1</result>标签，以供接口程序抓取信息，获取是否通知成功的信息，否则订单网关系统会显示没有通知
			echo "<result>1</result>";
			//记录订单处理日志
		}else{
			//异常处理部分（可选）,根据自己系统而定
			yii::trace("-------------------------优赋支付异常-------------------------------");
			echo "<result>0</result>";   //当返回<result>0</result>时网关系统会继续通知
			//记录订单处理日志
		}
	}
	
	
	/**
	 * 记录盛付通支付的异常通知
	 * @param  $err_code  盛付通错误码
	 * @param  $err_code  接口类型
	 */
	private function sftlogerrcode($err_code,$type){
		$time = date('Y-m-d H:i:s');
		$msg = "";
		switch($err_code){
			case '00':$msg = '等待付款中';break;
			case '02':$msg = '付款失败';break;
			case '03':$msg = '过期';break;
			case '04':$msg = '撤销成功';break;
			case '05':$msg = '退款中';break;
			case '06':$msg = '退款成功';break;
			case '07':$msg = '退款失败';break;
			case '08':$msg = '订单已关闭';break;
			default:$msg=$err_code;
		}
		Yii::getLogger()->log("----$time----$type---盛付通异常通知：$err_code---错误描述：$msg-------", Logger::LEVEL_WARNING);
	}
	
	/**
	 * 判读是否是空值
	 * @param unknown $var
	 * @return boolean
	 */
	private function isEmpty($var){
		if(isset($var)&&$var!=""){
			return false;
		}else{
			return true;
		}
	}
	
	/**
	 * 游币支付的后台通知地址
	 */
	public function actionCurrencynotify(){
		  if(isset($_POST['transaction_id'])){
		  	  $tranid =  Helper::filtdata($_POST["transaction_id"]);  
		  	  $this->successpaynotify($tranid,'游币支付');  
		  }else{
		  	yii::trace("------------------游币支付：请求错误--------------------------------");
		  	return false;
		  }
	}
	
	
	/**
	 * 成功付款通知游戏商
	 * @param unknown $transaction_id   订单号
	 * @param unknown $paytype          支付类型
	 * @param string $TransNo           第三方订单号
	 * @param string $InstCode          银行编码
	 * @return boolean                  返回结果
	 */
	private function successpaynotify($transaction_id,$paytype,$tnstCode=''){
		//1.查到该订单，验证订单状态是否处理过
		$order = Order::findOne(['transaction_id'=>$transaction_id]);
		if(!$order){//订单不存在
			$this->wxlogerrcode('nosignrecord' ,'支付了一笔未存在该订单号的订单', $paytype);
			return false;
		}
	
		if($order->state==2){//已付款
			if($order->type==1){//游戏商已通知成功 ,不在继续通知
				$this->wxlogerrcode('nosignrecord' ,'支付了一笔已通知的订单号的订单', $paytype);
				return true;
			}
		}else{
			//处于未付款状态,未处理过，则进行订单状态修改操作
			$time = strtotime(date('Y-m-d'));
			$id = $order->uid;
			$order->state = 2;
			$order->createtime = time();
			$tnstCode?$order->instcode = $tnstCode : '';
			$todayordernum = 0;     //今日订单数
			//判断是否为新付款用户
			$_yew = Order::find()->where(['uid'=>$id,'state'=>2])->orderBy('createtime')->asArray()->all();
			$num = $order->price;
			if($_yew){
				$ogid = $order->gid;
				$order->first_time = strtotime(date('Y-m-d',$_yew['0']['createtime']));
				$order->utype = 1;
				foreach ($_yew as $vy) {
					if(!$order->gfirst_time && $vy['gid']==$ogid){//非游戏新付款用户
						$order->gtype = 1;
						$order->gfirst_time = strtotime(date('Y-m-d',$vy['createtime']));
					}
					$num = $num+$vy['price'];//计算vip等级
					if($vy['createtime']>$time){// 统计今日的订单数
						$todayordernum = $todayordernum+1;
					}
				}
				if($order->gtype!=1){
					$order->gtype = 2;
					$order->gfirst_time = $time;
				}
			}else{
				$order->gtype = 2;//新付款用户
				$order->utype = 2;//新付款用户
				$order->gfirst_time = $time;
				$order->first_time = $time;
			}
			$res = $order->save();
			if(!$res){		//若保存失败，则终止，等待微信的第二次通知进行处理
				return false;
			}
			if($order->logintype==1){ //会员登录
				$vip = Helper::vipSort($num);
				$user = User::findOne($id);
				if($user){
					$integralnum = $this->saveintegral($vip['num'], $order->price,$todayordernum,$user);   //保存积分到数据库，并返回获得的积分
					$user->vip = $vip['num'];
					$user->save();
				}
			}
			
			/* $a_time = time();//活动时间 10.11 - 11.21
			if($a_time>1510243200 && $a_time <1511280000){//狗粮活动
				$Acquire = new Acquire();
				$Acquire->type = 3;
				$Acquire->num = 10*$order->price;
				$Acquire->uid = $id;
				$Acquire->createtime = time();
				$a_RE = $Acquire->save();
			} */
		}
		//2.将成功结果发送给游戏方，处理过后，则直接返回true给微信
		$config = Configuration::findOne(['gid'=>$order->gid]);
		if($config){
			$guniqe = Game::find()->where(['id'=>$order->gid])->select('unique')->asArray()->one();
			$data['trade_status'] = 'SUCCESS';
			$data['game'] = $guniqe?$guniqe['unique']:'';
			$data['partnerid'] = $config->partnerid;
			$data['userid'] = $order->uid;
			$data['total_fee'] = $order->price*100;
			$data['transaction_id'] = $order->transaction_id;
			$data['out_trade_no'] = $order->orderID;
			$data['product_id'] = $order->product_id;
			$data['attach'] = $order->attach;
			$data['pay_time'] = date('Y-m-d H:i:s',$order->createtime);
			$data['timestamp'] = time();
			$data['sign'] = Helper::getSign($data,$config->key); //获取签名
			$url = $config->type_url;
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
				yii::trace("----------------------------------------output=--$output------------");
				return false;
			}
			$_order = clone $order;
			if($output=='SUCCESS'){
				$file = dirname(dirname(__FILE__))."/runtime/notify_log.txt";
				$myfile = fopen($file, "a+");
				$txt = date('Y-m-d H:i:s')."---------".$paytype."回调参数:".json_encode($data)."\r\n".$paytype."回调地址：".$url."\r\n\r\n";
				fwrite($myfile, $txt);
				fclose($myfile);
				$_order->type = 1;//成功
				if($_order->save()){//回调成功
					//$this->rebate($order);   //五一活动
					return true;
				}
			}else{
				$_order->type = 2;//通知失败
				$_order->save();
				return false;
			}
		}else{
			yii::trace("--------------------通知=----$paytype配置信息不存在----------------");
			return false;
		}
	}
	
	
	
	/**
	 * 五一充值返利活动
	 * [98,150) 返 1-10
	 * [150,350) 返  15~40
	 * [350,700)  返  35~70
	 * [700,1000) 返   100
	 *
	 */
	public function rebate($order){
		$time = time();
		$price = isset($order->price)?$order->price:0;
		if(!$price || $time<1524844800 || $time>1525449600 ||$price<98){ //活动时间在4.28-5.4
			return false;
		} 
		
		$rebatecurrencynum = 0;   //返利的游币值
		$type = '';             //福袋类型： 1：白银  2：黄金  3：铂金  4：砖石
		$minnum = 0;           //随机最小金额
		$maxnum = 0;          //随机最大金额
		$randgrade = $this->getrandnum();  //获取等级
		if($price>=98 && $price<150){
			switch ($randgrade){
				case 1: $minnum = 1; $maxnum = 3;break;
				case 2: $minnum = 4; $maxnum = 7;break;
				case 3: $minnum = 8; $maxnum = 10;break;
			}
			$rebatecurrencynum = rand($minnum,$maxnum);
			$type = 1;
		}else if($price>=150 && $price<350){
			switch ($randgrade){
				case 1: $minnum = 15; $maxnum = 20;break;
				case 2: $minnum = 21; $maxnum = 30;break;
				case 3: $minnum = 31; $maxnum = 40;break;
			}
			$rebatecurrencynum = rand($minnum,$maxnum);
			$type = 2;
		}else if($price>=350 && $price<700){
			switch ($randgrade){
				case 1: $minnum = 35; $maxnum = 45;break;
				case 2: $minnum = 46; $maxnum = 64;break;
				case 3: $minnum = 65; $maxnum = 70;break;
			}
			$rebatecurrencynum = rand($minnum,$maxnum);
			$type = 3;
		}else if($price>=700){
			$rebatecurrencynum =100;
			$type = 4;
		}
	
		$rebatecurrencytemp = new Rebatecurrencytemp();
		$rebatecurrencytemp->uid = isset($order->uid)?$order->uid:'-1';
		$rebatecurrencytemp->oid = isset($order->id)?$order->id:'-1';   //$user->id;
		$rebatecurrencytemp->aid = 1;   //活动名称
		$rebatecurrencytemp->price = $price;  //充值金额
		$rebatecurrencytemp->rebatecurrency =$rebatecurrencynum; //返利的游币值
		$rebatecurrencytemp->type =$type;    //福袋类型： 1：白银  2：黄金  3：铂金  4：砖石
		$rebatecurrencytemp->createtime = $time;
		$rebatecurrencytemp->isdraw = 0;  //是否领取： 0未领取  1：已领取
		if($rebatecurrencytemp->save()){ 
			return true;
		}else{
			yii::trace('-----------------------------'.$order->uid.'充值了'.$price.'元，但在'.date('Y-m-d H:i:s',time()).'中返利失败，请查询原因--------------');
			return false;
		}
	}
	
	
	/**
	 * 概率等级
	 * 1等级10%
	 * 2等级80%
	 * 3等级10%
	 * @return number
	 */
	public function getrandnum(){
		$randomnum = rand(1,100);  //随机取1-100的随机数
		if($randomnum<=40){
			return 1;
		}elseif($randomnum>40 && $randomnum<=92){
			return 2;
		}elseif($randomnum>92 && $randomnum<=100){
			return 3;
		}
		return 1;
	}
}