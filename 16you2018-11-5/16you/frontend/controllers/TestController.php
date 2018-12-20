<?php 
namespace frontend\controllers;

use yii;
use frontend\controllers\BaseController;
use common\models\Game;
use common\common\Helper;
use common\models\Carousel;
use common\models\User;
use common\models\Order;
use common\models\Gift;
use common\models\GiftReceive; 
use common\models\Playgameuser;
use common\redismodel\GameRedis;
use common\redismodel\CarouselRedis;
use common\models\Plateform;
use common\models\Consult;
use yii\web\Controller;
use common\common\Wxpayutil;
use common\alisms1\SendSms;
use common\models\Redpackrecord;
use common\common\Wxinutil;
    
class TestController extends Controller{
	public function actionSet(){
		exit;
	   /* if(!isset(yii::$app->session['rpopenid'])||!yii::$app->session['rpopenid']){//
	    	return false;
		}
		$openid = yii::$app->session['rpopenid'];
		$money = 2;
	           $partner_trade_no = ('wxp1'.date('YmdHis',time()).rand(0,9999));
				$wx = new Wxpayutil();
				$res = $wx->sendredpacket($openid,$partner_trade_no,$money,'16游国庆整点红包');*/ //发红包
	
	}
/**
	 * 静默授权，获取openid
	 */
	/*public function toAuth(){
		$appid = yii::$app->params['redpackwinfo']['appid'];
		$state = yii::$app->params['state'];
		$redirect_uri=urlencode('http://'.$_SERVER['HTTP_HOST'].'/luck/getinfo.html');
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=$state#wechat_redirect";
		header("Location:$url");
	}*/
	
	//获取openid
	/*public function actionGetinfo(){
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
		yii::$app->session['ropenid'] =  $data->openid;
		/* $url = yii::$app->params['frontend']."/luck/robredpacket.html";
		header("Location:$url"); */
	/*	$headurl = (yii::$app->session['aserver'])?yii::$app->session['aserver']:yii::$app->params['frontend'].'/luck/index.html';//原来的地址
		$this->redirect($headurl);
	}*/

	/*public function actionCheck(){
		
		exit;
		  
	}

    public function actionAuthtest(){
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx063d4062ae521385&redirect_uri=http%3A%2F%2Fwx.16you.com%2Fwxauth%2Fgetinfo.html&response_type=code&scope=snsapi_base&state=lianshang#wechat_redirect";
       $this->redirect($url);
    }*/

    /*    //获取openid
    public function actionGetinfo(){
        $state = yii::$app->params['state'];
        if(!isset($_GET['code'])&&!isset($_GET['state'])&&($_GET['state']!=yii::$app->params['state'])){    //链接不正确，分发访问
            echo '非法访问';
            exit();
        }
        $code = $_GET['code'];
        $appid = yii::$app->params['wxinfo']['appid'];
        $secret =yii::$app->params['wxinfo']['secret'];
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code ";
        $output = Wxinutil::http_get($url);
        $data = json_decode($output);
        if(!isset($data->access_token)){
            echo '获取不到权限，非法访问';
            exit();
        }
        $openid = $data->openid;
        var_dump($openid);exit;
        $userinfo = UserRedis::findOne(['openid'=>$openid]);
        if(!$userinfo){//redis不存在时往数据库查询
            $userinfo = User::findOne(['openid'=>$openid]);
            $userinfo && $this->saveuserredis($userinfo);
        }else{
            if($userinfo->vip==null){
                $userinfo->vip = 0;
                $userinfo->consult_id = '';
                $userinfo->save();
            }
        }
        if($userinfo){
            \Yii::$app->session->set('user',$userinfo);
            $headurl = (yii::$app->session['server'])?yii::$app->session['server']:yii::$app->params['frontend'].'/index/index!16you.html';//原来的地址
            $this->redirect($headurl);
        }else{
            return $this->redirect('/wxauth/auth.html?scope=userinfo');
        }
    }
*/
	
	/**
	 *获取ip 
	 */
	public function actionGetip(){
		$ip = '99'.substr(str_replace('.', '', $_SERVER["REMOTE_ADDR"]),-5,6).rand(1000,9999);
		$a = (int)$ip;
		echo $a;exit;
	}
        /**
     * 异步获取支付信息
     */
    public function actionGetdata1(){
        $openid = 'oYPpQxIlXHBdjkhLILrOj5ntbek8';
        $transaction_id = 'wx20157981212'.mt_rand(1000,9999);
        $total_fee = 1000;
        $body = '10.0元';
        $attach = 'null';
        $product_id = '1223541321407035645805'.mt_rand(1,9);
        $wxpayutil = new Wxpayutil();
        $jsApiObj = $wxpayutil->nativepay($openid,$transaction_id,$total_fee/100,$body,$attach,$product_id);
        if(is_array($jsApiObj)){        //支付程序正确
            return json_encode(['errorcode'=>'0','msg'=>'可支付', 'jsApiParameters'=>$jsApiObj]);
        }else if($jsApiObj==false){
            return json_encode(['errorcode'=>'ERROR','msg'=>'网络异常，请稍后再重试']);
        }else if($jsApiObj=='ORDERPAID'){       //该订单已支付
            return json_encode(['errorcode'=>'ORDERPAID','msg'=>'您的订单已支付']);
        }else if($jsApiObj=='OUT_TRADE_NO_USED'){       //订单号重复，则删除此人的订单号，并重新请求支付
            $order->transaction_id = null;
            $order->save(); 
            return json_encode(['errorcode'=>'OUT_TRADE_NO_USED']); 
        }
    }
    
	//首页
    public function actionIndex() { 
    	echo 111;exit;
    	$limitnum = yii::$app->params['limitnum'];
         if(yii::$app->cache->get('carousel')){ //cahce不存在时则去数据库查询
            $carousel = yii::$app->cache->get('carousel');
        }else{
            $carousel = Carousel::find()->where(['state'=>1])->orderBy('sort desc')->limit(5)->asArray()->ALL();
            YII::$app->cache->set('carousel',$carousel);
        }
    	
    	//获取游戏信息
        if(yii::$app->cache->get('hotgame')|| yii::$app->cache->get('gamearr')){//不存在热门游戏或者游戏的缓存时则从数据库读取
            $gamearr = array_slice(yii::$app->cache->get('gamearr'),0,50);
            $hotgame = array_slice(yii::$app->cache->get('hotgame'),0,20);
        }else{
            $this->getGame();
            $gamearr = array_slice(yii::$app->cache->get('gamearr'),0,50);
            $hotgame = array_slice(yii::$app->cache->get('hotgame'),0,20);
        }
		if(yii::$app->session['playgame']){ 
			$playgame = array_slice(yii::$app->session['playgame'],0,5);
		}else{
			$this->newplay(5); //获取最近在玩游戏
			$playgame = array_slice(yii::$app->session['playgame'],0,5);
		}
        $user = yii::$app->session['user'];
        yii::$app->session['typemenu'] = 1;
        yii::$app->session->set('tabindex',0); //选项卡的下标
        echo '执行完毕';
    } 
    
    /**
     * 获取咨询、平台名称
     */
    public function actionGetcpdata(){
    		$user = yii::$app->session['user'];
    		$puid = yii::$app->session['puid']; //平台id
    		$consult_id = 72;
    		$puid=20;
    		$pnameobject = Plateform::find()->where(['punid'=>$puid])->select('pname')->one();
    		$pname = ($pnameobject)?$pnameobject->pname:'';  //获取平台名称
    		$type = (isset($_GET['type']))?1:0;//0跳转热门页面   1:跳转之前的选项卡
    		$consult = Consult::find()->select('max(id) as id')->one();
    		$consult_boolean = 1; //是否查看最新资讯  2为看过， 1为未看过
    		if((isset($consult_id) && $consult_id >= $consult->id ) || !$consult){
    			$consult_boolean = 2;
    		}
    		$resarr['pname'] = $pname;
    		$resarr['consult_boolean'] = $consult_boolean;
    		return json_encode([
    				'errorcode'=>0,
    				'info'=>$resarr,
    				]);
    }
    
    /**
     * 最近在玩
     */
    private function newplay($limit){
    	$user =  yii::$app->session['user'];
    	$openid = 'oYPpQxLaMytE5Pfg6hU7jCpfQdSc1';//$user->openid;
    	//获取最近玩的游戏
    	//$userarr = $user->gid;// User::find()->where(['openid'=>$openid])->asArray()->select('gid')->one();
    	$temparr = array();
    	if($user){
    		$gid_arr = ( $user->gid)?json_decode($user->gid,true):array();
    		if(!empty($gid_arr)){
    			arsort($gid_arr);//以降序排序
    			$gidarr = array_keys($gid_arr);
                //获取已启用的最近在玩游戏    	
    			$playgame = GameRedis::find()->where(['id'=>$gidarr,'state'=>1])->limit($limit)->asArray()->all();
    			if(!$playgame){
    				//$playgame = Game::find()->where(['id'=>$gidarr,'state'=>1])->limit(50)->asArray()->select('id,name,descript,label,head_img,game_url')->all();
    				$playgame = Game::find()->where(['id'=>$gidarr,'state'=>1])->limit($limit)->asArray()->all();
    				$playgame = $this->savegameredis($playgame,2);
    			}
    			if($playgame){
    				foreach ($gidarr as $g){//获取对应游戏内容
    					foreach ($playgame as $k=>$play){
    						if($g==$play['id']){
    							$play['playtime'] = $gid_arr[$play['id']];
    							$temparr[] = $play;
    						}
    					}
    				}
    			}
    	    }
    	}
    		yii::$app->session['playgame'] = $temparr;
    }
  
    /**
     * 获取热门游戏和其他游戏
     */
   private function getGame(){
        $gamearr = GameRedis::find()->where(['state'=>1])->asArray()->all();
    	$gamearr && $gamearr = Helper::quick_sort($gamearr,'sort');//按某个字段排序
        if(!$gamearr){//redis不存在时，则去数据库查
			$gamearr = Game::find()->where(['state'=>1])->orderBy('sort desc')->asArray()->select('id,name,descript,label,head_img,game_url,type')->all();//游戏
			        $hotgame = array();
			        if($gamearr){
			            $index = 1;
			            foreach ($gamearr as $k=>$game){
			                if($index<=20 && $game['type']==1){ //存前20条热门游戏
			                    $hotgame[] = $game;
			                    unset($gamearr[$k]);  //删除已存入到热门的游戏
			                    $index++;
			                }
			            }
			        }else{
			            $gamearr = array();
			  }
        }
        yii::$app->cache->set('hotgame',$hotgame); //热门游戏
        yii::$app->cache->set('gamearr',$gamearr);
    }
    
    
    /**
     * 异步获取游戏
     */
    public function actionGetpro(){
    	if (Yii::$app->request->isAjax &&isset ( $_POST ['page'] )) { 
    		$page = Helper::filtdata($_POST ['page'],'INT');
    		if(!$page){
    			return json_encode ( [
    					'errorcode' => '1002',
    					'list' => null
    					] );
    			exit ();
    		}
    		$plist = yii::$app->cache->get('gamearr'); // 从缓存中获取数据
    		$list = null;
    		$pagenum = 50;
    		$start =  $pagenum * ($page); //当前页面的显示数量
    		$end =  $pagenum * ($page+1);          //加载此页时应显示的数量
    		if ($plist != null) {
    			$alist = array_splice ( $plist, $start, $pagenum );
    			foreach ( $alist as $k => $v ) {
    				$list [$k] ['id'] = $v['id'];
    				$list [$k] ['name'] = $v['name'];
    				$list [$k] ['descript'] = $v['descript'];
    				$list [$k] ['label'] = json_decode($v['label']);
    				$list [$k] ['head_img'] = ($v['head_img'])?$v['head_img']:'notset.png';
    				$list [$k] ['game_url'] = $v['game_url'];
    				$list [$k] ['type'] = $v['type'];
    			}
    		}
    		$errorcode = ($list != null) ? '0' : '10001'; // 为空则返回1001码
    		return json_encode ( [
    				'errorcode' => $errorcode,
    				'info' => $list,
    				] );
    	 } else {
    		$this->redirect('/index/index.html');
    	} 
    	
    }
    
    /**
     * 详情页
     */
    public function actionDetail() {
    	if(isset($_GET['id'])){
    		$gid = Helper::filtdata($_GET['id'],'INT');
    		if(!$gid){
    			$this->redirect('/index/index.html');
    		}
    		$game = GameRedis::find()->where(['id'=>$gid])->one();
    		if(!$game){//如果redis不存在时则去数据库 
    			//$game = Game::find()->where(['id'=>$gid])->select('id,name,label,intro,game_url,image,descript,head_img')->one();
    			$game = Game::find()->where(['id'=>$gid])->one();
    			if(!$game){
    				$this->redirect('/index/index.html');
    			}
    			$this->savegameredis($game); //游戏记录保存到redis
    		}
            if(yii::$app->session->get('hotgame')){//session存在时
           	$hotgame = array_slice(yii::$app->session->get('hotgame'),0,8);//热门游戏//Game::find()->where(['type'=>1,'state'=>1])->limit(8)->orderBy('sort asc')->asArray()->select('id,head_img,name')->all();//热门游戏
           }else{ //session不存在时则重新获取
            	$gameresarr = $this->getGame();
            	$hotgame = array_slice($gameresarr['hotgame'],0,8);
            }
            $puid = yii::$app->session['puid']; //平台id
            $pname = Plateform::find()->where(['punid'=>$puid])->select('pname')->one();
    		return $this->renderPartial("detail",[
					'game'=>$game,
    				'hotgame'=>$hotgame,
    				'pname'=>$pname,  
    		]);  
    	}else{
    		$this->redirect('/index/index.html');
    	}
    }     
    
    /*
     * 保存游戏记录到redis
    */
    private function savegameredis($game,$type=1){
    	if($type==1){
    		$gameredis = new GameRedis();
    		$gameredis->name = $game->name;
    		$gameredis->cid = $game->cid;
    		$gameredis->descript = $game->descript;
    		$gameredis->unique= $game->unique;
    		$gameredis->state = $game->state;
    		$gameredis->label = $game->label;
    		$gameredis->intro = $game->intro;
    		$gameredis->game_url =$game->game_url;
    		$gameredis->type = $game->type;
    		$gameredis->sort =$game->sort;
    		$gameredis->image = $game->image;
    		$gameredis->head_img = $game->head_img;
    		$gameredis->game_type = $game->game_type;
    		$gameredis->createtime = $game->createtime;
    		$gameredis->save();
    	}else if($type==2){
    		foreach ($game as $g){
    			$gameredis = new GameRedis();
    			$gameredis->name = $g['name'];
    			$gameredis->cid = $g['cid'];
    			$gameredis->descript = $g['descript'];
    			$gameredis->unique= $g['unique'];
    			$gameredis->state = $g['state'];
    			$gameredis->label = $g['label'];
    			$gameredis->intro = $g['intro'];
    			$gameredis->game_url = $g['game_url'];
    			$gameredis->type = $g['type'];
    			$gameredis->sort =$g['sort'];
    			$gameredis->image = $g['image'];
    			$gameredis->head_img = $g['head_img'];
    			$gameredis->game_type = $g['game_type'];
    			$gameredis->createtime = $g['createtime'];
    			$gameredis->save();
    		}
    	}
    	
    }
    
    /**
     * 更新在玩缓存
     */
    private function changesession($game){
    	$playgame = (yii::$app->session['playgame'])?yii::$app->session['playgame']:array();
    	foreach ($playgame as $k=>$play){
    		if($play['id']==$game['id']){//原来的存在则删除
    			unset($playgame[$k]);
    		}
    	}
    	array_unshift($playgame,$game);//存入数组第一个元素中
    	if(count($playgame)>6){//如果大于6条则删除最后一条
    		array_pop($playgame);
    	}
    	yii::$app->session['playgame'] = $playgame;
    }
    
    
 /**
     * 最近在玩页
     */
    public function actionNearplay() { 
    	if(!isset(yii::$app->session['playgame'])){ 
				$this->newplay($limit); //获取最近在玩游戏
    	}	
    	return $this->render("nearplay");
    }  
    
    //游戏页面
    public function actionTogame(){
        return $this->renderPartial('togame');
    }
	
    //错误页面
    public function actionError(){
    	return $this->renderPartial('error');
    }


    public function actionPay(){
        return $this->renderPartial("index");
    }

    public function actionSmstest(){
        $phone = '15521399840';
        $msg = rand(1000,9999);
        $sms = new SendSms();
        $res = $sms->sendSms($msg,$phone);
    }
    
    /**
     * 抢红包
     *
     */
    public function actionRobredpacket(){
    	if(!isset($_GET['type'])){
    		return json_encode([
    				'errorcode'=>'1001',
    				'msg'=>'您好，今日该红包已抢完',
    				]);
    	}
    	$type = Helper::filtdata($_GET['type']); //类型：1：10点红包  2:12点红包  3：19点红包  4：21点红包
    	$starttime = strtotime(date('Y-m-d',time())); //今日时间戳
    	$endtime = time();
    	$cacheindex = $starttime.$type;  //缓存下标
    	$limitperson = 50; //最多多少人可以抢到红包
    	/* if($endtime<1506700800 || $endtime>1507478400){
    	 return json_encode([
    	 		'errorcode'=>'1014',
    	 		'msg'=>'您好，活动时间是9月30到10月7号',
    	 		]);
    	} */
    	/* 	$timeres = $this->checktime($type, $endtime); //检测时间是否到点
    	 if($timeres==false){  //抢该红包的时间未到
    	return json_encode([
    			'errorcode'=>'1002',
    			'msg'=>'您好，时间未到，请耐心等待',
    			]);
    	} */
    	if(!yii::$app->cache->get($cacheindex)){//判断是否有缓存，没有则向数据库读取并保存到缓存
    		$rpcount = Redpackrecord::find()->where(['type'=>$type])->andWhere("createtime between $starttime and $endtime")->count();
    		yii::$app->cache->set($cacheindex,$rpcount);
    	}
    	//判断抢红包人数是否满50人
    	if(yii::$app->cache->get($cacheindex)>$limitperson){//大于50人
    		return json_encode([
    				'errorcode'=>'1003',
    				'msg'=>'您好，今日红包已抢完',
    				]);
    	}
    
    	/* if(!isset(yii::$app->session['rpopenid'])||!yii::$app->session['rpopenid']){//判断是否有openid
    	 return json_encode([
    	 		'errorcode'=>'1008',
    	 		'msg'=>'您好，今日红包已抢完',
    	 		]);
    	} */
    	$openid = 'oPmDyjhkbPvGsUj4j3e3dLzyeu98';//yii::$app->session['rpopenid'];
    	$uid ='11201128';// yii::$app->session['user']->id;
    	//判断该用户当天是否充值
    	$orderres = Order::find()->where(['uid'=>$uid])->andWhere("createtime between $starttime and $endtime")->one();
    	/* 	if(!$orderres){
    		return json_encode([
    				'errorcode'=>'1004',
    				'msg'=>'您今日还未充值，无法抢红包',
    				]);
    	}  */
    	$totalmoney = 200; //总金额
    	$totalperson = 50; //人数
    	$nowindex = 1;//当前位置
    	$isboolean = true; //今日该点红包是否已抢
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
    	/* if(!$isboolean){
    		return json_encode([
    				'errorcode'=>'1005',
    				'msg'=>'您好，该红包已抢',
    				]);
    	} */
    
    	if($totalmoney<0 ||$totalperson<1){ //红包已抢完
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
    		if($indexnum!=$nowindex){
    			$indexnum = $nowindex;
    		}
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
    	$money = $this->randmoney($totalmoney, $totalperson, $nowindex, 2);
    	if($money>0){
    		//存数据到数据库
    		$redpackcord = new Redpackrecord();
    		$redpackcord->title = '国庆整点红包';
    		$redpackcord->uid = $uid;
    		$redpackcord->money = $money;
    		$redpackcord->type = $type;
    		$redpackcord->openid = $openid;
    		$redpackcord->createtime = time();
    		if($redpackcord->save()){//成功
    			//echo $money;
    			if($money){
    				return json_encode([
    						'errorcode'=>'0',
    						'msg'=>'红包发送成功',
    						'money'=>$money,
    						]);
    			}
    			/* $partner_trade_no = ('wxp1'.date('YmdHis',time()).rand(0,9999));
    			 $wx = new Wxpayutil();
    			$res = $wx->sendredpacket($openid,$partner_trade_no,$money,'16游国庆整点红包');
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
    			} */
    
    			//发红包
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
    
   /*  public function actionGetredpack(){
    	$openid = yii::$app->session['rpopenid'];
    	$partner_trade_no = 'wxp'.date('YmdHis').rand(1000,9999);
    	$price = 0;
    	$desc = '16游狗粮兑换';
    	//发红包
    	$wx = new Wxpayutil();
    	$res = $wx->sendredpacket($openid,$partner_trade_no,$price,$desc);
    }
     */
    /**
     * 保存用户unionid
     */
   /*  public function actionSaveuserunionid(){
    	$start = (yii::$app->cache->get('startnum_p'))?yii::$app->cache->get('startnum_p'):18000;
    	$end = (yii::$app->cache->get('endnum_p'))?yii::$app->cache->get('endnum_p'):19000;
    	$this->saveunionid($start,$end);
    }
    
    public function saveunionid($startnum,$endnum){
    	if($endnum<=$endnum && $endnum<485421){
    		$user  = User::find()->where(['between','id',$startnum,$endnum])->asArray()->all();
    		if($user){
    			foreach ($user as $u){
    					if(!$u['unionid']){
    						$userinfo = User::findOne(['id'=>$u['id']]);
    						if($userinfo && $userinfo->unionid == ''){
    							$appid = yii::$app->params['wxinfo']['appid'];
    							$userdata = Wxinutil::getUserinfo($userinfo->openid,$appid);
    							if($userdata!='false'){
    								if(isset($userdata->unionid) && $userdata->unionid!=''){
    									$isuser = User::find()->where(['unionid'=>$userdata->unionid])->one();
    									if(!$isuser){
    										$userinfo->unionid = isset($userdata->unionid)?$userdata->unionid:'';
    										$userinfo->save();
    									}
    									
    								}
    							}
    						}
    					}
    			}
    		}
    		$s = $startnum + 1000;
    		$e = $endnum + 1000;
    		yii::$app->cache->set('startnum_p',$s);   
    		yii::$app->cache->set('endnum_p',$e);  
    	}
    	
    } */
   public function actionSendcurl(){
	   	$data['trade_status'] = 'FAIL';
	   	$data['game'] = '111';
	   	$data['partnerid'] =  '111';
	   	$data['userid'] =  '111';
	   	$data['total_fee'] =  '111';
	   	$data['transaction_id'] =  '111';
	   	$data['out_trade_no'] =  '111';
	   	$data['product_id'] =  '111';
	   	$data['attach'] =  '111';
	   	$data['pay_time'] = '11111';
	   	$data['timestamp'] = time();
	   	$data['sign'] =  '111';//获取签名
	   	// $data['district_id'] = $order->districtID;
	   	$url = 'http://test.fkdz.crazytoy.com.cn/pay/osyoupayreceiver.do';
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
    }
}