<?php 
namespace frontend\controllers;

use yii;
use frontend\controllers\BaseController;
use common\models\Redpackrecord;
use common\models\Order;
use common\common\Helper;
use common\common\Wxpayutil;
use common\common\Wxinutil;
use common\models\Share;
use common\models\Winning;

/**
 * 春节红包活动
 * @author HE
 */
class LuckedController extends BaseController{
	
	/**
	 * 红包首页
	 */
	public function actionIndex(){
		return $this->renderPartial('error');
		echo "该活动已下架,敬请关注下期活动";exit;
		if(!isset(yii::$app->session['rpopenid'])||!yii::$app->session['rpopenid']){//判断是否有openid
			yii::$app->session['activeserver'] = $_SERVER['REQUEST_URI'];
			$this->toAuth();
		}
		$uid = yii::$app->session['user']->id;
		$firstredarr = array();
		$secredarr = array();
		$thiredarr = array();
		$fouredarr = array();
		$firstmoney = 0;
		$secmoney = 0;
		$thimoney = 0;
		$foumoney = 0;
		//今日时间戳
		$starttime = strtotime(date('Y-m-d',time())); //今日时间戳 
		$endtime = strtotime(date('Y-m-d 23:59:59',time())); 
		$redpacketrecord = Redpackrecord::find()->where("createtime between $starttime and $endtime")->all();
		if($redpacketrecord){
			foreach ($redpacketrecord as $rp){
				switch ($rp['type']){
					case 1://10点红包
						$firstmoney += $rp['money'];
						if($uid==$rp['uid']){
							$firstredarr['type'] = 1;//1已抢 
							$firstredarr['money'] = $rp['money']; //金额
					    }
						($firstmoney>=80) && $firstredarr['type'] = ($firstredarr['type']==1)?$firstredarr['type']:2;  //如果type=1 已抢到，则状态还是显示已抢到的状态
					break;

					case 2://12点红包
						$secmoney += $rp['money'];
						if($uid==$rp['uid']){
							$secredarr['type'] = 1;//1已抢
							$secredarr['money'] = $rp['money']; //金额
						}
						($secmoney>=100) &&	$secredarr['type'] = ($secredarr['type']==1)?$secredarr['type']:2; //如果type=1 已抢到，则状态还是显示已抢到的状态
					break;
					
					case 3://19点红包
						$thimoney += $rp['money'];
						if($uid==$rp['uid']){
							$thiredarr['type'] = 1;//1已抢
							$thiredarr['money'] = $rp['money']; //金额
						}
						($thimoney>=100) && $thiredarr['type'] = ($thiredarr['type']==1)?$thiredarr['type']:2; //如果type=1 已抢到，则状态还是显示已抢到的状态
					break;
					
					case 4: //21点红包
						$foumoney += $rp['money'];
						if($uid==$rp['uid']){
							$fouredarr['type'] = 1;//1已抢
							$fouredarr['money'] = $rp['money']; //金额
						}
						($foumoney>=100) && $fouredarr['type'] = ($fouredarr['type']==1)?$fouredarr['type']:2; //如果type=1 已抢到，则状态还是显示已抢到的状态
					break;
				}
			}
		}
		//邀请人分享出去的链接所带的参数
		if(isset($_GET['token'])){
			$pid = substr($_GET['token'],9);//截取uid
			if($uid != $pid){
				$share = new Share();
				$s_res = $share->findOne(['uid'=>$uid,'pid'=>$pid]);
				if(!$s_res){
					$share->uid = $uid;
					$share->pid = $pid;
					$share->createtime = $starttime;
					$share->save();
				}
			}
		}
		$num = $this->numLuck();//剩余转的次数
		yii::$app->session['typemenu'] = 8;
		return $this->render('index',[
				'firstredarr'=>$firstredarr,
				'secredarr'=>$secredarr,
				'thiredarr'=>$thiredarr,
				'fouredarr'=>$fouredarr,
				'time'=>$starttime,
				'num'=>$num,
				]);
	}
	
	
	/**
	 * 抢红包
	 *
	 */
	public function actionRobredpacket(){
		if(!isset($_POST['type'])){
			return json_encode([
					'errorcode'=>'1001',
					'msg'=>'您好，今日该红包已抢完',
					]);
		}
		$type = Helper::filtdata($_POST['type']); //类型：1：10点红包  2:12点红包  3：19点红包  4：21点红包
		$starttime = strtotime(date('Y-m-d',time())); //今日时间戳
		$endtime = time();
		$cacheindex = $starttime.$type;  //缓存下标
		$limitperson = 0; //最多多少人可以抢到红包
		$totalmoney = 0; //总金额
		$nowindex = 1;//当前位置
		$isboolean = true; //今日该点红包是否已抢
		switch ($type){
			case 1: $totalmoney = 80 ;$limitperson=60;break;
			case 2: $totalmoney = 100 ;$limitperson=80;break;
			case 3: $totalmoney = 100 ;$limitperson=80;break;
			case 4: $totalmoney = 100 ;$limitperson=80;break;
		}
		if($endtime>1520006400 || $endtime<1518710400){
			return json_encode([
					'errorcode'=>'1002',
					'msg'=>'您好，活动时间是2月16到3月2号',
					]);
		} 
		$timeres = $this->checktime($type, $endtime); //检测时间是否到点
		if($timeres==false){  //抢该红包的时间未到
			return json_encode([
					'errorcode'=>'1002',
					'msg'=>'您好，时间未到，请耐心等待',
					]);
		}  
		if(!yii::$app->cache->get($cacheindex)){//判断是否有缓存，没有则向数据库读取并保存到缓存
			$rpcount = Redpackrecord::find()->where(['type'=>$type])->andWhere("createtime between $starttime and $endtime")->count();
			yii::$app->cache->set($cacheindex,$rpcount);
		}
		//判断抢红包人数是否满限定的人数
		if(yii::$app->cache->get($cacheindex)>$limitperson){//大于限定的人数
			return json_encode([
					'errorcode'=>'1003',
					'msg'=>'您好，今日红包已抢完',
					]);
		}
	
		if(!isset(yii::$app->session['rpopenid'])||!yii::$app->session['rpopenid']){//判断是否有openid
			return json_encode([
					'errorcode'=>'1008',
					'msg'=>'您好，今日红包已抢完',
					]);
		}  
		$openid = yii::$app->session['rpopenid'];
		$uid = yii::$app->session['user']->id;
		//判断该用户当天是否充值
		$orderres = Order::find()->where(['uid'=>$uid,'state'=>2])->andWhere("createtime between $starttime and $endtime")->one();
		if(!$orderres){
			return json_encode([
					'errorcode'=>'1002',
					'msg'=>'您今日还未充值，无法抢红包',
					]);
		} 
	
		$redpackrecord = Redpackrecord::find()->where(['type'=>$type])->andWhere("createtime between $starttime and $endtime")->all();
		if($redpackrecord){
			foreach ($redpackrecord as $k=>$rp){
				if($rp['type']==$type){
					$totalmoney -= $rp['money'];
					$nowindex ++;
					($rp['uid']==$uid) && $isboolean = false;
				}
			}
		}
		if(!$isboolean){
			return json_encode([
					'errorcode'=>'1002',
					'msg'=>'您好，该红包您已抢过了',
					]);
		}
	
		if($totalmoney<0 ||$limitperson<1){ //红包已抢完
			return json_encode([
					'errorcode'=>'1006',
					'msg'=>'您好，红包已抢完',
					]);
		}
		try {
			//检测是否有锁
			$checkres = $this->checklock(1);
			if(!$checkres){
				return json_encode([
						'errorcode'=>'1007',
						'msg'=>'您好，红包已抢完',
						]);
			}
			$lockarr = ['time'=>time(),'lock'=>1];
			yii::$app->cache->set('lock',$lockarr); //加锁
			//获取缓存人数+1
			$pnum =  yii::$app->cache->get($cacheindex);
			if($pnum>$limitperson){
				return json_encode([
						'errorcode'=>'1009',
						'msg'=>'您好，红包已抢完',
						]);
			}
			$indexnum = $pnum+1;
			if($indexnum>$nowindex){
				$indexnum = $nowindex;
			}
			$money = $res = $this->hongbao($totalmoney,($limitperson-$nowindex+1),0.1);
			yii::$app->cache->set($cacheindex,$indexnum);
			$lockarr = ['time'=>time(),'lock'=>0];
			yii::$app->cache->set('lock',$lockarr); //释放锁
		} catch (Exception $e) {
			$lockarr = ['time'=>time(),'lock'=>0];
			yii::$app->cache->set('lock',$lockarr); //释放锁
			return json_encode([
					'errorcode'=>'1010',
					'msg'=>'您好，红包已抢完',
					]);
		}
		if($money>1 || $money==1){
			//存数据到数据库
			$redpackcord = new Redpackrecord();
			$redpackcord->title = '春节整点红包';
			$redpackcord->uid = $uid;
			$redpackcord->money = $money;
			$redpackcord->type = $type;
			$redpackcord->openid = $openid;
			$redpackcord->createtime = time();
			if($redpackcord->save()){//成功
				$partner_trade_no = ('wxp1'.date('YmdHis',time()).rand(0,9999));
				$wx = new Wxpayutil();
				$res = $wx->sendredpacket($openid,$partner_trade_no,$money,'16游春节整点红包'); //发红包
				if($res){
					return json_encode([
							'errorcode'=>'0',
							'msg'=>'红包发送成功',
							'money'=>$money,
							]);
				}else{
					return json_encode([
							'errorcode'=>'1011',
							'msg'=>'红包发送失败',
							]);
				}
			}else{//失败
				return json_encode([
						'errorcode'=>'1012',
						'msg'=>'您好，红包已抢完',
						]);
			}
		}else{
			return json_encode([
					'errorcode'=>'1013',
					'msg'=>'您好，红包已抢完',
					]);
		}
	}
	
	
	/**
	 * 检测锁是否解开
	 */
	private function checklock($num){
		if($num<4){//循环3次结束
			$lockarr = yii::$app->cache->get('lock');
			$time = isset($lockarr['time'])?time()-$lockarr['time'] : 100;
			if($time>30){//锁超过30秒  解锁
				$lockarr = ['time'=>time(),'lock'=>0];
				yii::$app->cache->set('lock',$lockarr); //释放锁
				return true;
			}
			if(isset($lockarr['lock']) && $lockarr['lock']==1){//已加锁
				sleep(3);
				$num += 1;
				$this->checklock($num);
			}else{
				return true;
			}
		}else{
			return false;
		}
	}
	
	/**
	 * 检测时间是否到点
	 * @param unknown $time
	 */
	private function checktime($type,$time){
		$tboolean = true; //判断时间是否已到
		switch ($type){
			case 1:($time<strtotime(date('Y-m-d 10:00'))) && $tboolean =false; break;  //10点红包
			case 2:($time<strtotime(date('Y-m-d 12:00'))) && $tboolean =false; break;  //12点红包
			case 3:($time<strtotime(date('Y-m-d 19:00'))) && $tboolean =false; break;   //19点红包
			case 4:($time<strtotime(date('Y-m-d 21:00'))) && $tboolean =false; break;   //21点红包
			default: $tboolean = false;
		}
		return $tboolean;
	}
	
	/**
	 * 红包金额算法
	 * $total 红包总金额
	 * $num 分成10个红包，支持10人随机领取
	 * $min 每个人最少能收到0.01元
	 * $snum 当前是第几人领取
	 */
	private function randmoney($total,$num,$snum,$min){
		if($num>$snum){
			$safe_total = ($total-($num-$snum)*$min)/($num-$snum); //随机安全上限
			if($min>$safe_total){
				return 0;
			}
			$money = mt_rand($min*100,$safe_total*100)/100;
			$total = $total-$money;
		}else{
			$money = $total;
		}
		return $money;
	}
	
	
	/**
	 * 静默授权，获取openid
	 */
	public function toAuth(){
		$appid = yii::$app->params['redpackwinfo']['appid'];
		$state = yii::$app->params['state'];
		$redirect_uri=urlencode('http://'.$_SERVER['HTTP_HOST'].'/lucked/getinfo.html');
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=$state#wechat_redirect";
		header("Location:$url");
	}
	
	//获取openid
	public function actionGetinfo(){
		$state = yii::$app->params['state'];
		if(!isset($_GET['code'])&&!isset($_GET['state'])&&($_GET['state']!=yii::$app->params['state'])){	//链接不正确，分发访问
			echo '非法访问';
			exit();
		}
		$code = $_GET['code'];
		$appid = yii::$app->params['redpackwinfo']['appid']; //'wx1874a10fb8e2bf85';
		$secret = yii::$app->params['redpackwinfo']['secret']; //'ece73aac0ca1908b7d68642f961d0960';
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
		$output = Wxinutil::http_get($url);
		$data = json_decode($output);
		if(!isset($data->access_token)){
			return '获取不到权限，非法访问';
		}
		$openid = $data->openid;
		yii::$app->session['rpopenid'] =  $data->openid;
		/* $url = yii::$app->params['frontend']."/luck/robredpacket.html";
			header("Location:$url"); */
		$headurl = (yii::$app->session['activeserver'])?yii::$app->session['activeserver']:yii::$app->params['frontend'].'/lucked/index.html';//原来的地址
		$this->redirect($headurl);
	}

	/**
	 * 大转盘算法
	 * @return [type] [description]
	 */
	public function actionTurnplateluck(){
		if(!yii::$app->request->isAjax){
			return json_encode([
				'info'=>'数据错误，请稍后再试',
				'errorcode'=>1001,
			]);
		}
		$endtime = strtotime(date('Y-m-d 23:59:59',time())); 
		if($endtime>1520006400 || $endtime<1518710400){
			return json_encode([
					'errorcode'=>'1001',
					'info'=>'您好，活动时间是2月16到3月2号',
					]);
		}
		$c_num = $this->numLuck();
		if($c_num>0){
			if(yii::$app->cache->get('arr_num_luck')){
				$arr = yii::$app->cache->get('arr_num_luck');
			}else{
				//先将可能转到的奖品按概率写好
				for ($i=0; $i < 1000; $i++) { 
					// if($i==0){//0.1%
					// 	$arr[] = 6;//红包168元
					// }else
					if($i>0 && $i<=10){//1%
					// 	$arr[] = 0;//红包1.68
					// }elseif ($i>50 && $i<=51) {//0.1%
					// 	$arr[] = 8;//红包16.88
					}elseif ($i>=10 && $i<=499) {//48.9%
						$arr[] = 1;//恭
					}elseif ($i>499 && $i<=889) {//38.9%
						$arr[] = 3;//喜
					}elseif ($i>889 && $i<=998) {//10%
						$arr[] = 9;//发
					}else{//0.1%
						$arr[] = 7;//财
					}
				}
				yii::$app->cache->set('arr_num_luck',$arr);
			}
			//随机取出数据中的一个键
			$ran = rand(0,999);
			$info = $arr[$ran];
			//保存奖品记录 
			$win = new Winning();
			$win->uid = yii::$app->session['user']->id;
			
			if($ran<10){//红包
				$price = ($info==6)?168:(($info==8)?16.88:1.68);
				$win->content = '红包'.$price.'元';
				$wx = new Wxpayutil();
				$openid = yii::$app->session['rpopenid'];
				$partner_trade_no = 'wxp'.date('YmdHis').rand(1000,9999);
				$p_res = $wx->sendredpacket($openid,$partner_trade_no,$price,'16游大转盘红包'); //发红包
				if($p_res){
					$win->type = 2;//红包发送成功
				}else{
					$win->type = 3;//红包发送失败
				}
				$win->openid = $openid;
			}else{
				//存恭喜发财对应的索引
				switch ($info) {
					case 1:
						$win->content = '0';
						break;
					case 3:
						$win->content = '1';
						break;
					case 9:
						$win->content = '2';
						break;
					case 7:
						$win->content = '3';
						break;
				}
				$win->type = 4;
			}
			$win->createtime = time();
			$w_res = $win->save();
			if($w_res){
				yii::$app->session['w_num'] = yii::$app->session['w_num']+1;//已转的次数+1
				return json_encode([
						'info'=>$info,
						'errorcode'=>0,
				]);
			}else{
				return json_encode([
						'info'=>'数据传输出错',
						'errorcode'=>1003,
				]);
			}
		}else{
			return json_encode([
				'info'=>'网络错误',
				'errorcode'=>1002,
			]);
		}
	}

	/**
	 * 仓库的东西
	 * @return [type] [description]
	 */
	public function actionWarehouse(){
		if(!yii::$app->request->isAjax){
			return json_encode([
				'info'=>'数据错误，请稍后再试',
				'errorcode'=>1001,
			]);
		}
		$uid = yii::$app->session['user']->id;
		$arr = array(0,0,0,0);
		$data = Winning::find()->where(['type'=>4,'uid'=>$uid])->groupBy('content')->select('count(id) as num,content')->asArray()->all();
		$i = 0;
		if($data){
			foreach ($data as $key => $v) {
				$arr[$v['content']] = $v['num'];
				if($v['num']>0){
					$i++;
				}
			}
		}
		$state = ($i==4)?true:false;//判断是否可以兑换
		return json_encode([
			'info'=>$arr,
			'errorcode'=>0,
			'state'=>$state
		]);
	}

	/**
	 * 合成仓库的东西
	 * @return [type] [description]
	 */
	public function actionWarehousesynthesis(){
		if(!yii::$app->request->isAjax){
			return json_encode([
				'info'=>'数据错误，请稍后再试',
				'errorcode'=>1001,
			]);
		}
		$uid = yii::$app->session['user']->id;
		$data = Winning::find()->where(['type'=>4,'uid'=>$uid])->groupBy('content')->select('count(id) as num,id,content')->asArray()->all();
		if($data){
			$i = 0;
			foreach ($data as $key => $v) {
				if($v['num']>0){
					$i++;
				}
				$arr[] = $v['id'];
			}
			if($i==4){
				$wx = new Wxpayutil();
				$openid = yii::$app->session['rpopenid'];
				$partner_trade_no = 'wxp'.date('YmdHis').rand(1000,9999);
				$price = 88.88;
				$p_res = $wx->sendredpacket($openid,$partner_trade_no,$price,'16游大转盘恭喜发财红包'); //发红包
				$model = new Winning();
				$model::deleteAll(['id' => $arr]);
				$model->content = '恭喜发财'.$price.'红包';
				$model->openid = $openid;
				$model->uid = $uid;
				$model->type = $p_res?2:3;
				$model->createtime = time();
				$model->save();
				return json_encode([
					'info'=>'合成成功',
					'errorcode'=>0,
				]);
			}
		}else{
			return json_encode([
				'info'=>'数据错误，请稍后再试',
				'errorcode'=>3424,
			]);
		}
	}


	/**
	 * 剩余转的次数
	 * @return [type] [description]
	 */
	private function numLuck(){
		$uid = yii::$app->session['user']->id;
		$start = strtotime(date('Ymd'));
		$end = time();
		$o_num = 1;
		//查出今天已转的次数
		if(yii::$app->session['w_num']){
			$w_num = yii::$app->session['w_num'];
		}else{
			//中将表，查出今天中奖的记录次数
			$res = Winning::find()->where(['uid'=>$uid,'type'=>2])->andWhere(['between', 'createtime',$start,$end])->select('count(id) as num')->asArray()->one();
			$w_num = !empty($res)?$res['num']:0;
			yii::$app->session['w_num'] = $w_num;
		}
		if($w_num<3){//每人每天只有3次转的机会
			//每天任意充值获得一次抽奖机会
			$o_res = Order::find()->where(['uid'=>$uid,'state'=>2])->andWhere(['between', 'createtime',$start,$end])->select('id')->asArray()->one();
			!empty($o_res) && $o_num++;
			
			//每天邀请好友获得一次抽奖机会
			$share = Share::find()->where(['pid'=>$uid])->andWhere(['between', 'createtime',$start,$end])->select('id')->asArray()->one();
			!empty($share) && $o_num++;
		}
		$c_num = $o_num - $w_num;//剩余转的次数

		return $c_num;
	}
	
	
	/**
	 * 红包生成算法
	 * @param $money    总金额
	 * @param $number   红包数量
	 * @param $ratio    浮动系数
	 */
	function hongbao($money,$number,$ratio = 0.5){
		$res = array(); //结果数组
		if($number==0){
			return 0;
		}
		$min = ($money / $number) * (1 - $ratio);   //最小值
		$max = ($money / $number) * (1 + $ratio);   //最大值
		/*--- 第一步：分配低保 ---*/
		for($i=0;$i<$number;$i++){
			$res[$i] = $min;
		}
		$money = $money - $min * $number;
		/*--- 第二步：随机分配 ---*/
		$randRatio = 100;
		$randMax = ($max - $min) * $randRatio;
		for($i=0;$i<$number;$i++){
			//随机分钱
			$randRes = mt_rand(0,$randMax);
			if($randRatio==0){
				return 0;
			}
			$randRes = $randRes / $randRatio;
			if($money >= $randRes){ //余额充足
				$res[$i]    += $randRes;
				$money      -= $randRes;
			}
			elseif($money > 0){     //余额不足
				$res[$i]    += $money;
				$money      -= $money;
			}
			else{                   //没有余额
				break;
			}
		}
		/*--- 第三步：平均分配上一步剩余 ---*/
		if($money > 0){
			$avg = $money / $number;
			for($i=0;$i<$number;$i++){
				$res[$i] += $avg;
			}
			$money = 0;
		}
		$moneyres = 0;
		if($res){
			/*--- 第四步：打乱顺序 ---*/
			shuffle($res);
			/*--- 第五步：格式化金额(可选) ---*/
			foreach($res as $k=>$v){
				//两位小数，不四舍五入
				preg_match('/^\d+(\.\d{1,2})?/',$v,$match);
				$match[0]   = number_format($match[0],2);
				//$res[$k]    = $match[0];
				$moneyres    = $match[0];
			}
		}
		return $moneyres;
	}
}