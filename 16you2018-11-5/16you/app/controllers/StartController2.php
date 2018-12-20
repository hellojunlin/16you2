<?php
namespace app\controllers;

use yii;
use yii\web\Controller;
use common\common\Helper;
use common\models\Configuration;
use common\models\Game;
use common\models\Gift;
use common\models\User;
use common\models\Playgameuser;
use common\redismodel\GameRedis;
use common\redismodel\PlaygameuserRedis;
use common\redismodel\UserRedis;
use common\models\Plateform;
use common\models\Consult;
use common\models\Order;

/**
 * 开始游戏页面
 * @author junlin
 */
class StartController extends Controller{

	//首页
    public function actionIndex() { 
        if(!isset(yii::$app->session['user'])||empty(yii::$app->session['user'])){
            yii::$app->session['server'] = $_SERVER['REQUEST_URI'];
            return $this->redirect('/personal/index.html');
        }
    	if(!isset($_GET['id'])){
    		return $this->redirect('/game/list.html'); 
    	}
    	if(isset($_GET['puid'])){
    		$puid = Helper::filtdata($_GET['puid']);
    		yii::$app->session['puid'] = $puid;
    	}else{
    		$puid = yii::$app->session['puid'];
    	} 
    	$gid = Helper::filtdata($_GET['id'],'INT');//游戏id
    	/* $game = GameRedis::find()->where(['id'=>$gid])->asArray()->one(); //获取游戏
    	if(!$game){ */ //redis不存在时则查询数据库
    	$game = Game::find()->asArray()->where(['id'=>$gid])->one(); //获取游戏
    	if(!$game){ //游戏不存在
    			return $this->redirect('/game/list.html');
    	}
    	/* 	$this->savegameredis($game);//保存到redis
    	} */
        $gname = $game['name'];
        $user = yii::$app->session['user'];
    	$userarr = User::findOne(['openid'=>$user->openid]); 
    	if(!$userarr){//用户不存在
    		unset(yii::$app->session['user']);//清除session
    		return $this->redirect('/game/list.html');
    	}
    	$saveres = $this->saveplayuser($userarr, $gid,$user);//保存当天玩游戏用户记录和最近在玩记录
    	if(!$saveres){
    		return $this->redirect('/game/list.html');
    	}
    	$this->changesession($game); //更新最近在玩缓存
    	$acctoken = $user->access_token; //获取access_token
    	$res = strstr($game['game_url'],"?");
        if($res){
        	$url = $game['game_url'].'&access_token='.$acctoken.'|'.$user->id.rand(1000,9999);  //游戏链接带参数
        }else{
            $url = $game['game_url'].'?access_token='.$acctoken.'|'.$user->id.rand(1000,9999);  //游戏链接带参数
        }
    	if(isset(yii::$app->session['playgame'])){
    		$playgame = array_slice(yii::$app->session['playgame'],0,4);
    	}else{
    		$this->newplay(); //获取最近在玩游戏
    		$playgame = array_slice(yii::$app->session['playgame'],0,4);
    	}
    	
    	if(yii::$app->session->get('hotgame')){//不存在热门游戏或者游戏的缓存时则从数据库读取
    		$hotgame = array_slice(yii::$app->session->get('hotgame'),0,20);
    	}else{
    		$gameresarr = $this->getGame();
    		$hotgame = array_slice($gameresarr['hotgame'],0,20);
    	}
        $plate = Plateform::find()->where(['punid'=>$puid])->select('start_img,pname')->one();
        if($plate){
            $start_img = $plate->start_img;
            $pname = $plate->pname;
        }else{
            $start_img = '';
            $pname = '';
        }
        $payurl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];   //支付前端跳转页面
    	return $this->renderPartial('start',[ 
    		'gid'=>$gid,
            'gname'=>$gname,
    		'game_url'=>$url,
    		'playgame'=>$playgame,
    		'hotgame'=>$hotgame,
            'puid'=>$puid,
            'start_img'=>$start_img,
            'pname'=>$pname,
    		'payurl' =>$payurl,
    	]);
    } 
    
    
    
    
    
    //异步加载热门游戏
    public function actionGethotgame(){
    	if(!yii::$app->request->isAjax||!isset($_POST['page'])){
    		return json_encode([
    				'errorcode'=>1000,
    				'info'=>'数据错误，请稍后再试',
    		]);
    	}
    	$cur = Helper::filtdata(yii::$app->request->post('page',1));
    	$pageSize = 20;
    	$hotgame = Game::find()->where(['type'=>1])->offset(($cur-1)*$pageSize)->limit($pageSize)->orderBy('sort DESC')->asArray()->all();
    	if($hotgame){
    		return json_encode([
    				'errorcode'=>0,
    				'info'=>$hotgame
    		]);
    	}else{
    		$info =($cur>1)? '已加载完':'暂时没有热门游戏';
    		return json_encode([
    				'errorcode'=>1002,
    				'info'=>$info,
    		]);
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
    
    /**
     * 保存当天玩游戏用户记录和最近在玩记录
     */
    private function saveplayuser($userarr,$gid,$user){
    	$gidres = ($userarr['gid'])?json_decode($userarr['gid'],true):array();
    	if( count($gidres)>100){//最近在玩 超过100条，则删除
    		foreach ($gidres as $k=>$v){
    			if($v==min($gidres)){//删除时间最小的时间戳
    				unset($gidres[$k]);
    			}
    		}
    	}
    	$gidres[$gid] = time();  //以游戏id=>时间方式存入数据库 ，最近在玩使用该字段
    	$userarr->gid = json_encode($gidres);
    	if(!$userarr->save()){
    		return false;
    	}
    	
    	$userredis = UserRedis::findOne(['openid'=>$userarr->openid]);
    	$userredis && $this->updateUserRedis($userarr, $userredis);  //redis存在则更新
    	
    	//更新最近在玩记录session
    	$user = yii::$app->session['user'];
    	$user->gid = json_encode($gidres);
    	yii::$app->session['user'] = $user;
    	$sixtime = strtotime("-6 month");
    	//$playuserres = PlaygameuserRedis::find()->where(['uid'=>$user->id])->all();//查找该用户的游戏记录
    	$playuserres = Playgameuser::find()->where(['uid'=>$user->id])->all();//查找该用户的游戏记录
    	$time = strtotime(date('Y-m-d'));//获取当天时间戳
    	$isboolean = true;
    	$state = 1;           //1:该用户第一次玩  2：该用户已玩过
    	$first_time = $time;  //该用户第一次玩游戏的时间
    	$first_playtime = $time; //该用户第一次玩这款游戏的时间
    	$type = 1;            //1:该款游戏该用户第一次玩  2：该款游戏该用户已玩过
    	$isover = true;  //最近玩游戏时间是否超过半年时 ，超过半年时则为新用户  true 为超过   false 不超过
    	if($playuserres){     
    		$state = 2;      //1:该用户第一次玩  2：该用户已玩过
    		$first_time = $playuserres[0]['first_time'];
    		foreach ($playuserres as $pu){
    			($pu['createtime']>$sixtime)?$isover = false: $state = 1; //当最近玩游戏时间不超过半年时
    			if(isset($pu['gid'])&& isset($pu['createtime'])){
    				$pu['gid'] == $gid && $first_playtime = $pu['first_playtime'];
	    			$pu['gid'] == $gid  && $type = 2;  //1:该款游戏该用户第一次玩  2：该款游戏该用户已玩过
	    			($pu['gid'] == $gid && $pu['createtime'] == $time) &&$isboolean = false; //今天该用户进入过该款游戏的记录，则不保存到数据库
    			}
    		}
    	}
    	if($isboolean){//未有记录，则保存数据库 反之，否则不做处理
    		$session_res = Helper::setusersession($user);//验证用户session
    		if($session_res){
    			$user = yii::$app->session['user'];
    		}
    	 	$playgameuser = new Playgameuser();
    	 	$playgameuser->uid = $user->id;
    		$playgameuser->gid = $gid;
    		$playgameuser->pid = $user->pid;
    		$playgameuser->state = $state;
    		$playgameuser->type = $type;
    		$playgameuser->createtime = strtotime(date('Y-m-d'));
    		$playgameuser->first_time = ($isover)?$time:$first_time;
    		$playgameuser->first_playtime = ($isover)?$time:$first_playtime;
    		if(!$playgameuser->save()){
    			return false;
    		}
    		$predis = new PlaygameuserRedis();
    		$predis->id = $playgameuser->id;
    		$predis->uid = $user->id;
    		$predis->gid = $gid;
    		$predis->pid = $user->pid;
    		$predis->state = $state;
    		$predis->type = $type;
    		$predis->createtime = strtotime(date('Y-m-d'));
    		$predis->first_time = ($isover)?$time:$first_time;
    		$predis->first_playtime = ($isover)?$time:$first_playtime;
    		$predis->save();
    	}
    	return true;
    }
    /**
     * 最近在玩
     */
    private function newplay(){
        //获取最近玩的游戏
        $temparr = array();
    	$user =  yii::$app->session['user'];
    	if($user){
    		$gid_arr = ( $user->gid)?json_decode($user->gid,true):array();
    		if(!empty($gid_arr)){
    			arsort($gid_arr);//以降序排序
    			$gidarr = array_keys($gid_arr);
    			//获取已启用的最近在玩游戏
    			$playgame = GameRedis::find()->where(['id'=>$gidarr,'state'=>1])->limit(50)->asArray()->all();
    			if(!$playgame){
    				$playgame = Game::find()->where(['id'=>$gidarr,'state'=>1])->limit(50)->asArray()->select('id,name,descript,label,head_img,game_url')->all();
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
     * 获取热门游戏和其他游戏
     */
    private function getGame(){
    	//$gamearr = GameRedis::find()->where(['state'=>1])->asArray()->all();
    	//$gamearr && $gamearr = Helper::quick_sort($gamearr,'sort');//按某个字段排序
    	//if(!$gamearr){//redis不存在时，则去数据库查
    		$gamearr = Game::find()->where(['state'=>1])->orderBy('sort desc')->asArray()->all();//游戏
    	  /*   if($gamearr){//存在保存数据库
    	    	$this->savegameredis($gamearr,2);
    	    } */
    	//}
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
    	}
    	yii::$app->session->set('hotgame',$hotgame); //热门游戏
    	yii::$app->session->set('gamearr',$gamearr);
    	$resarra = array();
    	$resarra['hotgame'] = $hotgame;
    	$resarra['gamearr'] = $gamearr;
    	return $resarra;
    }
    
    
    /**
     * 更新redis缓存
     * @param unknown $user
     * @param unknown $userredis
     */
    private function updateUserRedis($user,$userredis){
    	if($userredis){//存在更新
    		$userredis->gid = $user->gid;
    		$userredis->save();
    	}else{//重新保存
    		$uredis = NEW UserRedis();
    		$uredis->id = $user->id;
    		$uredis->openid = $user->openid;
    		$uredis->pid = $user->pid;
    		$uredis->username = $user->username;
    		$uredis->sex = $user->sex;
    		$uredis->head_url = $user->head_url;
    		$uredis->province = $user->province;
    		$uredis->city = $user->city;
    		$uredis->integral = $user->integral;
    		$uredis->gid = $user->gid;
    		$uredis->phone = $user->phone;
    		$uredis->access_token = $user->access_token;
    		$uredis->createtime = $user->createtime;
    		$uredis->save();
    	}
    }

    //离开游戏页面的三个游戏
    public function actionThreegame(){
        if(yii::$app->request->isAjax&&isset($_POST['gid'])){
            $gid = Helper::filtdata($_POST['gid'],'INT');
            if(yii::$app->session->get('hotgame')){//不存在热门游戏或者游戏的缓存时则从数据库读取
                $hotgame = array_slice(yii::$app->session->get('hotgame'),0,20);
            }else{
                $gameresarr = $this->getGame();
                $hotgame = array_slice($gameresarr['hotgame'],0,20);
            }
            $leavegame = array();//退出页面时模态框的三个游戏
            if($hotgame){
                foreach($hotgame as $kh=>$vh){
                   if($vh["id"]==$gid){
                       unset($hotgame[$kh]);//去除本页面游戏
                       break;
                   }
                }
                $leavegame = array_slice($hotgame,0,3);
                return json_encode([
                    'info'=>$leavegame,
                    'errorcode'=>0,
                ]);
            }else{
                return json_encode([
                    'errorcode'=>1001,
                    'info'=>'没有数据',
                ]);
            }
        }
    }

    //异步加载礼包
    public function actionGetgift(){
        if(!yii::$app->request->isAjax||!isset($_POST['page'])||!isset($_POST['gid'])){
            return json_encode([
                    'errorcode'=>1000,
                    'info'=>'数据错误，请稍后再试',
            ]);
        }
        $cur = Helper::filtdata(yii::$app->request->post('page',1));
        $gid = Helper::filtdata(yii::$app->request->post('gid'),'INT');
        $pageSize = 50;
        $uid = yii::$app->session['user']->id;
        $game = GameRedis::find()->where(['id'=>$gid])->asArray()->one(); //获取游戏
        if(!$game){ //redis不存在时则查询数据库
            $game = Game::find()->where(['id'=>$gid])->asArray()->one(); //获取游戏
            if(!$game){ //游戏不存在
                return $this->redirect('/index/index.html');
            }
            $this->savegameredis($game);//保存到redis
        }
        $gift = (new \yii\db\Query())
        ->select('id,gid,gift_name,game_name,content,CDKEY,state,createtime,count(gift_name) as num,,game_image,number')
        ->from('g_gift')
        ->where(['state'=>0,'gid'=>$gid])
    	->andWhere(['!=','uid',$uid])  
        ->groupBy('number')
        ->offset(($cur-1)*$pageSize)
        ->limit($pageSize)
        ->orderBy('createtime DESC')
        ->all();
        if($gift){
            return json_encode([
                    'errorcode'=>0,
                    'info'=>$gift
            ]);
        }else{
            $info =($cur>1)? '已加载所有礼包':'暂时没有可领礼包';
            return json_encode([
                    'errorcode'=>1002,
                    'info'=>$info,
            ]);
        }
    }

    //检测是否已经付款
    public function actionDetection(){
        if(!yii::$app->request->isAjax||!isset($_POST['transaction_id'])){
            return json_encode([
                'errorcode'=>1000,
                'info'=>'数据错误，请稍后再试',
            ]);
        }
        $transaction_id = Helper::filtdata($_POST['transaction_id']);
        $order = Order::find()->where(['transaction_id'=>$transaction_id])->one();
        if($order){
            if($order->state==2){
                return json_encode([
                    'errorcode'=>0,
                    'info'=>'已支付成功',
                ]);
            }elseif($order->state==1){
                return json_encode([
                    'errorcode'=>1002,
                    'info'=>'正在支付中',
                ]);
            }
        }else{
            return json_encode([
                'errorcode'=>1003,
                'info'=>'数据错误，请稍后再试',
            ]);
        }
    }
}