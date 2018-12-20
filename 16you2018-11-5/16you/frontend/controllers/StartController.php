<?php 
namespace frontend\controllers;

use yii;
use frontend\controllers\BaseController;
use common\common\Helper;
use common\models\Configuration;
use common\models\Game;
use common\models\Gift;
use common\models\User;
use common\models\Playgameuser;
use common\models\Plateform;
use common\redismodel\GameRedis;
use common\redismodel\PlaygameuserRedis;
use common\redismodel\UserRedis;
use common\models\Wxshare;
use common\models\Consult;
use common\models\Email;
use common\redismodel\WxshareRedis;
use common\redismodel\PlatformRedis;

/**
 * 开始游戏页面
 * @author junlin
 */
class StartController extends BaseController{

	//首页
    public function actionIndex() { 
    	if(!isset($_GET['id']) ||(!yii::$app->session['puid'] && !isset($_GET['puid']))){
    		return $this->redirect('/index/index.html'); 
    	}
    	//获取平台ID
    	if(isset($_GET['puid'])){
    		$puid = Helper::filtdata($_GET['puid']);
    	}else{
    		$puid = yii::$app->session['puid'];
    	}
    	//获取用户信息
    	$user = yii::$app->session['user'];
    	//获取游戏信息
    	$gid = Helper::filtdata($_GET['id'],'INT');//游戏id
        // $game = GameRedis::find()->where(['id'=>$gid])->asArray()->one(); //获取游戏
    	// if(!$game){ //redis不存在时则查询数据库
    		$game = Game::find()->asArray()->where(['id'=>$gid])->andWhere('state!=2')->one(); //获取游戏
    	 	if(!$game){ //游戏不存在
    	 		return $this->redirect('/index/index.html');
    		}
    	// 	$this->savegameredis($game);//保存到redis
    	// }
    	$this->changesession($game); //更新最近在玩缓存
    	$acctoken = $user->access_token; //获取access_token
     	$res = strstr($game['game_url'],"?");
        if($res){
        	$url = $game['game_url'].'&access_token='.$acctoken.'|'.$user->id.rand(1000,9999);  //游戏链接带参数
        }else{
            $url = $game['game_url'].'?access_token='.$acctoken.'|'.$user->id.rand(1000,9999);  //游戏链接带参数
        }
        $gname = $game['name'];
        //获取广告图片
        $plate = yii::$app->session['plateform'];
        $start_img = isset($plate->start_img)?$plate->start_img:'';
        $pname = isset($plate->pname)?$plate->pname:'';
        //获取分享数据
        $share_res = $this->getsharedata($gid);
        $share_res['imgurl'] = yii::$app->params['cdn'].'/game/'.$game['head_img'];
        $payurl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];   //支付前端跳转页面
        return $this->renderPartial('start',[ 
            'gid'=>$gid,
            'gname'=>$gname,
            'game_url'=>$url,
            'puid'=>$puid,
            'start_img'=>$start_img,
            'pname'=>$pname,
            'firend_share'=>$share_res,
        	'payurl' =>$payurl,
        	'plate'=>$plate,
        ]);
    } 

    /**
     * 存平台信息
     */
    private function toPlate($plateform){
        $redis = new PlatformRedis();
        $redis->id = $plateform->id;
        $redis->punid = $plateform->punid;
        $redis->cid = $plateform->cid;
        $redis->pname = $plateform->pname;
        $redis->state = $plateform->state;
        $redis->createtime = $plateform->createtime;
        $redis->start_img = $plateform->start_img;
        $redis->save();
    }
    
    /**
     * 获取最近在玩
     */
    public function actionGetnewplay(){
    	if(yii::$app->request->isAjax){
    		if(isset(yii::$app->session['playgame'])){
    			$playgame = array_slice(yii::$app->session['playgame'],0,4);
    		}else{
    			$this->newplay(); //获取最近在玩游戏
    			$playgame = array_slice(yii::$app->session['playgame'],0,4);
    		}
    		return json_encode([
    				'errorcode'=>0,
    				'info'=>$playgame]);
    	}
    }
    
    /**
     * 获取分享数据
     */
    private function getsharedata($gid){
    	// $wxshare = WxshareRedis::find()->where(['gid'=>$gid])->one();
    	// if(!$wxshare){
    		$wxshare = Wxshare::find()->where(['gid'=>$gid])->one();
    	// }
    	$datares = array();
    	if($wxshare){
    		//分享朋友
    		$datares['title'] = isset($wxshare->title)?$wxshare->title:'';
    		$datares['desc'] =  isset($wxshare->desc)?$wxshare->desc:'';
    		$datares['link'] =  isset($wxshare->link)?$wxshare->link:'';
    	}
    	return  $datares;
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
    public function actionSaveplayuser(){
    	if(yii::$app->request->isAjax && isset($_POST['gid'])){
    		$gid = Helper::filtdata($_POST['gid'],'INT');
    		if(!$gid){
    			return json_encode([
    					'errorcode'=>1001,
    					'info'=>'网络异常，稍后再试',
    					]);
    		}
    		$user = yii::$app->session['user'];
    		$userarr = User::findOne(['openid'=>$user->openid]);
    		if(!$userarr){//用户不存在
    			unset(yii::$app->session['user']);//清除session
    			return json_encode([
    					'errorcode'=>1001,
    					'info'=>'网络异常，稍后再试',
    					]);
    		}
    		$saveres = $this->saveplayuser($userarr,$gid,$user);//保存当天玩游戏用户记录和最近在玩记录
    		if(!$saveres){
    			return json_encode([
    					'errorcode'=>1001,
    					'info'=>'网络异常，稍后再试',
    					]);
    		}
    		return json_encode([
    				'errorcode'=>0,
    				'info'=>'保存成功',
    				]);
    	}
    	 
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
    	// $userredis = UserRedis::findOne(['openid'=>$userarr->openid]);
    	// $userredis && $this->updateUserRedis($userarr, $userredis);  //redis存在则更新
    	
    	//更新最近在玩记录session
    	$user = yii::$app->session['user'];
    	$user->gid = json_encode($gidres);
    	yii::$app->session['user'] = $user;
    	$sixtime = strtotime("-3 month");
    	//$playuserres = PlaygameuserRedis::find()->where(['uid'=>$user->id])->all();//查找该用户的游戏记录
    	$playuserres = Playgameuser::find()->where(['uid'=>$user->id])->andWhere("createtime>$sixtime")->all();//查找该用户的游戏记录
    	$time = strtotime(date('Y-m-d'));//获取当天时间戳
    	$isboolean = true;
    	$state = 1;           //1:该用户第一次玩  2：该用户已玩过
    	$first_time = $time;  //该用户第一次玩游戏的时间
    	$first_playtime = $time; //该用户第一次玩这款游戏的时间
    	$type = 1;            //1:该款游戏该用户第一次玩  2：该款游戏该用户已玩过
    	$isover = true;  //最近玩游戏时间是否超过半年时 ，超过半年时则为新用户  true 为超过   false 不超过
    	if($playuserres){     
    		$state = 2;      //1:该用户第一次玩  2：该用户已玩过
    		$first_time = ($playuserres[0]['first_time'])?$playuserres[0]['first_time']:strtotime(date('Y-m-d',$playuserres[0]['createtime']));
    		foreach ($playuserres as $pu){
    			($pu['createtime']>$sixtime)?$isover = false: $state = 1; //当最近玩游戏时间不超过三个月时
    			if(isset($pu['gid'])&& isset($pu['createtime'])){
    				$pu['gid'] == $gid && $first_playtime = ($pu['first_playtime'])?$pu['first_playtime']:$pu['createtime'];
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
    		// $predis = new PlaygameuserRedis();
    		// $predis->id = $playgameuser->id;
    		// $predis->uid = $user->id;
    		// $predis->gid = $gid;
    		// $predis->pid = $user->pid;
    		// $predis->state = $state;
    		// $predis->type = $type;
    		// $predis->createtime = strtotime(date('Y-m-d'));
    		// $predis->first_time = ($isover)?$time:$first_time;
    		// $predis->first_playtime = ($isover)?$time:$first_playtime;
    		// $predis->save();
    	}
    	return true;
    }
    
   

    /**
     * 最近在玩
     */
    private function newplay(){
    	$temparr = array();
    	//获取最近玩的游戏
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
        /* $gamearr = GameRedis::find()->where(['state'=>1])->asArray()->all();//非热门游戏
    	$gamearr && $gamearr = Helper::quick_sort($gamearr,'sort');//按某个字段排序
    	if(!$gamearr){//redis不存在则读取数据库
    		$gamearr = Game::find()->where(['state'=>1])->orderBy('sort desc')->asArray()->select('id,name,descript,label,head_img,game_url,type')->all();//游戏
    		 if($gamearr){//存在则保存到redis
    	    	$this->savegameredis($gamearr,2);
    	    }
    	} */
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
    	}
    	yii::$app->cache->set('hotgame',$hotgame); //热门游戏
    	yii::$app->cache->set('gamearr',$gamearr);
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
            if(yii::$app->cache->get('hotgame')){//不存在热门游戏或者游戏的缓存时则从数据库读取
                $hotgame = array_slice(yii::$app->cache->get('hotgame'),0,20);
            }else{
                $this->getGame();
                $hotgame = array_slice(yii::$app->cache->get('hotgame'),0,20);
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
        $cur1 = $cur*$pageSize-$pageSize;
        $gift = \Yii::$app->db->createCommand("SELECT id,gid,gift_name,game_name,content,CDKEY,state,createtime,count(gift_name) as num,game_image,number,payment from g_gift where gid=:gid and state=0 AND type=1 AND number not in (select number from g_gift where gid=:gid and type=1 and uid=:uid) group By number order By createtime desc limit $cur1,$pageSize",[':uid'=>$uid,':gid'=>$gid])->queryAll();
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

    /**
     * 获取邮箱
     * @return [type] [description]
     */
    public function actionGetemail(){
        $user = yii::$app->session['user'];
        //查询该用户邮件信息
        //type阅读状态0未读1已读 state是否有礼包1是2否 
        //uid=1 发给所有的用户
        $email = Email::find()->where(['or',['uid'=>$user->Unique_ID],'uid=1'])->select('id,title,createtime,type,state')->orderBy('createtime desc')->asArray()->all();
        $type0 = 0;//未读邮件的数量
        $type1 = 0;//已读邮件的数量
        if($email){
            foreach ($email as $ke=>$ve) {
                $email[$ke]['createtime'] = date("Y/m/d",$ve['createtime']);
                if($ve['type']==0){
                    $type0++;
                }
                $type1++;
            }
            return json_encode([
                'errorcode'=>0,
                'info'=>$email,
                'type0'=>$type0,
                'type1'=>$type1,
            ]);
        }else{
            return json_encode([
                'errorcode'=>1000,
                'info'=>'暂时没有邮件',
                'type0'=>$type0,
                'type1'=>$type1,
            ]);
        }
    }

    /**
     * 悬浮球内置弹框 -->资讯详情
     */
    public function actionGetconsult(){
        if(!yii::$app->request->isAjax||!isset($_POST['id'])){
            return json_encode([
                    'errorcode'=>1000,
                    'info'=>'数据错误，请稍后再试',
            ]);
        }
        $id = Helper::filtdata(yii::$app->request->post('id'));
        
        $consult = Consult::find()->where(['id'=>$id])->asArray()->one();//从mysql查出咨讯
        if(!$consult){//判断是否有数据
            return json_encode([
                    'errorcode'=>1002,
                    'info'=>'暂时没有资讯',
            ]);
        }else{
            return json_encode([
                    'errorcode'=>0,
                    'info'=>$consult,
            ]);
        }
    }

    /**
     * 悬浮球内置弹框 -->邮件详情
     */
    public function actionGetemaildata(){
        if(!yii::$app->request->isAjax||!isset($_POST['id'])){
            return json_encode([
                    'errorcode'=>1000,
                    'info'=>'数据错误，请稍后再试',
            ]);
        }
        $id = Helper::filtdata(yii::$app->request->post('id'));
        
        $model = Email::find()->where(['id'=>$id])->one();//从mysql查出咨讯
        if($model){//判断是否有数据
            $res = array();
            $res['title'] = $model->title;
            $res['content'] = $model->gift_content?$model->gift_content.'<br/>'.$model->content:$model->content;
            if($model->type==0){//改变阅读状态 1已读
                $model->type = 1;
                $model->save(); 
            }
            return json_encode([
                'info'=>$res,
                'errorcode'=>0
            ]);
        }else{
            return json_encode([
                'info'=>'该邮件已被屏蔽',
                'errorcode'=>1000
            ]);
        }
    }
}