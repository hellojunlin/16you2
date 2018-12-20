<?php
namespace app\controllers;

use yii;
use yii\web\Controller;
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

class TestController extends Controller{ 
	//首页
    public function actionList() { 
      //  $limitnum = yii::$app->params['limitnum'];
     /*    if(yii::$app->cache->get('carousel')){ //cahce不存在时则去数据库查询
            $carousel = yii::$app->cache->get('carousel');
        }else{ */
            $carousel = Carousel::find()->where(['state'=>1])->orderBy('sort desc')->limit(4)->asArray()->ALL();
            YII::$app->cache->set('carousel',$carousel);
        /* } */
       /*  if(yii::$app->cache->get('hotgame')|| yii::$app->cache->get('gamearr')){//不存在热门游戏或者游戏的缓存时则从数据库读取
            $gamearr = array_slice(yii::$app->cache->get('gamearr'),0,50);
            $hotgame = array_slice(yii::$app->cache->get('hotgame'),0,20);
            $allgame =  array_slice(array_merge(yii::$app->cache->get('gamearr'),yii::$app->cache->get('hotgame')),0,200);
        }else{ */
            $this->getGame();
            $gamearr = array_slice(yii::$app->cache->get('gamearr'),0,50);
            $hotgame = array_slice(yii::$app->cache->get('hotgame'),0,20);
            $allgame = array_slice(array_merge(yii::$app->cache->get('gamearr'),yii::$app->cache->get('hotgame')),0,200);
       /*  } */
       /*   
        if(yii::$app->session['playgame']){ 
            $playgame = array_slice(yii::$app->session['playgame'],0,5);
        }else{ */
            $this->newplay(5); //获取最近在玩游戏
            $playgame = array_slice(yii::$app->session['playgame'],0,5);
       /*  } */
        $user = yii::$app->session['user'];
        yii::$app->session['typemenu'] = 1;
        echo '执行结束';exit;
       return $this->render("list",[  
                'carousel'=>$carousel,
                'user'=>$user, 
                'playgame'=>$playgame,
                'gamearr'=>$gamearr,
                'hotgame'=>$hotgame,
                'allgame'=>$allgame,
        ]);
    } 

        /**
     * 获取咨询、平台名称
     */
    public function actionGetcpdata(){
        if(yii::$app->request->isAjax){
            $user = yii::$app->session['user'];
            $puid = yii::$app->session['puid']; //平台id
            $pnameobject = Plateform::find()->where(['punid'=>$puid])->select('pname')->one();
            $pname = ($pnameobject)?$pnameobject->pname:'';  //获取平台名称
            $type = (isset($_GET['type']))?1:0;//0跳转热门页面   1:跳转之前的选项卡
            $consult = Consult::find()->select('max(id) as id')->one();
            $consult_boolean = 1; //是否查看最新资讯  2为看过， 1为未看过
            if((isset($user->consult_id) && $user->consult_id >= $consult->id ) || !$consult){
                $consult_boolean = 2;
            }
            $resarr['pname'] = $pname;
            $resarr['consult_boolean'] = $consult_boolean;
            return json_encode([
                    'errorcode'=>0,
                    'info'=>$resarr,
                    ]);
        }
    }
    
    /**
     * 最近在玩
     */
    private function newplay(){
    	$user =  yii::$app->session['user'];
    	//获取最近玩的游戏
    	$temparr = array();
    	if($user){
    		$openid = 'oYPpQxFheZ9GyafMbdx861Me93FY1';
    		$gid = '{"43":1487580904,"44":1487578027,"45":1487577995,"10042":1488334151,"10041":1488195261,"10040":1488195526,"10043":1488550870,"10023":1488195244,"10039":1488267654,"10038":1488333949,"10044":1488782957,"10059":1489560899}';
    		$gid_arr = ( $user->gid)?json_decode($user->gid,true):array();
    		if(!empty($gid_arr)){
    			arsort($gid_arr);//以降序排序
    			$gidarr = array_keys($gid_arr);
                //获取已启用的最近在玩游戏    	
    			$playgame = false;//GameRedis::find()->where(['id'=>$gidarr,'state'=>1])->limit(50)->asArray()->all();
    			if(!$playgame){
    				$playgame = Game::find()->where(['id'=>$gidarr,'state'=>1])->limit(50)->asArray()->all();
    				//$playgame = $this->savegameredis($playgame,2);
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
    	$gamearr = false;//GameRedis::find()->where(['state'=>1])->asArray()->all();
    	$gamearr && $gamearr = Helper::quick_sort($gamearr,'sort');//按某个字段排序
    	if(!$gamearr){//redis不存在时，则去数据库查
    		$gamearr = Game::find()->where(['state'=>1])->orderBy('sort desc')->limit(200)->asArray()->all();//游戏
//     	    if($gamearr){//存在保存数据库
//     	    	$this->savegameredis($gamearr,2);
//     	    }
    	}
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
    		$this->redirect('/game/list.html');
    	} 
    	
    }
    
    /**
     * 详情页
     */
    public function actionDetail() {
    	if(isset($_GET['id'])){
    		$gid = Helper::filtdata($_GET['id'],'INT');
    		if(!$gid){
    			$this->redirect('/game/list.html');
    		}
    		$game = false; //GameRedis::find()->where(['id'=>$gid])->one();
    		if(!$game){//如果redis不存在时则去数据库 
    			$game = Game::find()->where(['id'=>$gid])->one();
    			if(!$game){
    				$this->redirect('/game/list.html');
    			}
    			//$this->savegameredis($game); //游戏记录保存到redis
    		}
          /*   if(yii::$app->session->get('hotgame')){//session存在时
            	$hotgame = yii::$app->session->get('hotgame');
           }else{ //session不存在时则重新获取
            	$gameresarr = $this->getGame();
            	$hotgame = array_slice($gameresarr['hotgame'],0,8);
            } */
    		$hotgame = array_slice(yii::$app->cache->get('hotgame'),0,8);//热门游戏//Game::find()->where(['type'=>1,'state'=>1])->limit(8)->orderBy('sort asc')->asArray()->select('id,head_img,name')->all();//热门游戏
    		if(!$hotgame){//热门游戏缓存不存在时，则去数据库重新查询
    			$this->getGame();
    			$hotgame = array_slice(yii::$app->cache->get('hotgame'),0,8);
    		}
            $puid = yii::$app->session['puid']; //平台id
            $pname = Plateform::find()->where(['punid'=>$puid])->select('pname')->one();
    		return $this->renderPartial("detail",[
					'game'=>$game,
    				'hotgame'=>$hotgame,
    				'pname'=>$pname,  
    		]);  
    	}else{
    		$this->redirect('/game/list.html');
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
				$this->newplay(); //获取最近在玩游戏
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

    public function actionLogout(){
    	unset(yii::$app->session['user']);
    	return $this->render('list');
    }
}