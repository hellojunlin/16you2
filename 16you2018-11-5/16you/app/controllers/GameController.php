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

class GameController extends Controller{ 
	//首页
    public function actionList() { 
        $limitnum = yii::$app->params['limitnum'];
        if(yii::$app->cache->get('carousel')){ //cahce不存在时则去数据库查询
            $carousel = yii::$app->cache->get('carousel');
        }else{
            $carousel = Carousel::find()->where(['state'=>1])->orderBy('sort desc')->limit(4)->asArray()->ALL();
            YII::$app->cache->set('carousel',$carousel);
        }
        if(yii::$app->cache->get('hotgame')|| yii::$app->cache->get('gamearr')){//不存在热门游戏或者游戏的缓存时则从数据库读取
            $gamearr = array_slice(yii::$app->cache->get('gamearr'),0,50);
            $hotgame = array_slice(yii::$app->cache->get('hotgame'),0,20);
            $allgame =  array_slice(array_merge(yii::$app->cache->get('gamearr'),yii::$app->cache->get('hotgame')),0,200);
        }else{
            $this->getGame();
            $gamearr = array_slice(yii::$app->cache->get('gamearr'),0,50);
            $hotgame = array_slice(yii::$app->cache->get('hotgame'),0,20);
            $allgame = array_slice(array_merge(yii::$app->cache->get('gamearr'),yii::$app->cache->get('hotgame')),0,200);
        }
         
        if(yii::$app->session['playgame']){ 
            $playgame = array_slice(yii::$app->session['playgame'],0,5);
        }else{
            $this->newplay(5); //获取最近在玩游戏
            $playgame = array_slice(yii::$app->session['playgame'],0,5);
        }
        $user = yii::$app->session['user'];
        yii::$app->session['typemenu'] = 1;
        //$isshow = $this->isshow();
       return $this->render("list",[  
                'carousel'=>$carousel,
                'user'=>$user, 
                'playgame'=>$playgame,
                'gamearr'=>$gamearr,
                'hotgame'=>$hotgame,
                'allgame'=>$allgame,
       		//	'isshow'=>$isshow,
        ]);
    } 
    
    /**
     * 跳转新版首页
     * @return Ambigous <string, string>
     */
    public function actionNewindex() {
    	//获取轮播图信息
    	if(yii::$app->cache->get('carousel')){ //cahce不存在时则去数据库查询
    		$carousel = yii::$app->cache->get('carousel');
    	}else{
    		$carousel = Carousel::find()->where(['state'=>1])->orderBy('sort desc')->limit(5)->asArray()->ALL();
    		YII::$app->cache->set('carousel',$carousel);
    	}
    	//获取游戏信息
    	if(yii::$app->cache->get('newhotgamearr')|| yii::$app->cache->get('newrelaxarr')|| yii::$app->cache->get('newgamearr')|| yii::$app->cache->get('newallgamearr')){//不存在热门游戏、休闲游戏、新游游戏的缓存时则从数据库读取
    		$newhotgamearr = array_slice(yii::$app->cache->get('newhotgamearr'),0,6);  //6条热门游戏
    		$newrelaxarr = array_slice(yii::$app->cache->get('newrelaxarr'),0,6);      //6条休闲游戏
    		$newgamearr = array_slice(yii::$app->cache->get('newgamearr'),0,6);        //6条新游游戏
    		$newallgamearr = array_slice(yii::$app->cache->get('newallgamearr'),0,100);                   //获取所有游戏
    	}else{
    		$this->getGame();   //更新游戏信息换成
    		$newhotgamearr = array_slice(yii::$app->cache->get('newhotgamearr'),0,6);
    		$newrelaxarr = array_slice(yii::$app->cache->get('newrelaxarr'),0,6);
    		$newgamearr = array_slice(yii::$app->cache->get('newgamearr'),0,6);
    		$newallgamearr = array_slice(yii::$app->cache->get('newallgamearr'),0,100);
    	}
    
    	//12条资讯信息
    	if(yii::$app->cache->get('newconsult')){
    		$newconsultarr = array_slice(yii::$app->cache->get('newconsult'),0,12);   //12条资讯信息
    	}else{
    		$this->updateconsultcache();  //更新资讯信息缓存
    		$newconsultarr = array_slice(yii::$app->cache->get('newconsult'),0,12);   //12条资讯信息
    	}
    
    	//5条最近在玩游戏
    	if(yii::$app->session['playgame']){
    		$playgame = array_slice(yii::$app->session['playgame'],0,5);
    	}else{
    		$this->newplay(5); //获取最近在玩游戏
    		$playgame = array_slice(yii::$app->session['playgame'],0,5);
    	}
    	$user = yii::$app->session['user'];
    	yii::$app->session['typemenu'] = 1;
    	return $this->render("newindex",[
    			'carousel'=>$carousel,
    			'user'=>$user,
    			'playgame'=>$playgame,
    			'newhotgamearr'=>$newhotgamearr,
    			'newrelaxarr'=>$newrelaxarr,
    			'newgamearr'=>$newgamearr,
    			'newconsultarr'=>$newconsultarr,
    			'newallgamearr'=>$newallgamearr,
    			]);
    }
    
    /**
     * 跳转更多游戏
     * type 0 :查询热门  1: 查询新游   2：查询全部
     * @return
     */
    public function actionMoregame(){
    	$type = isset($_GET['id'])?Helper::filtdata($_GET['id']) : 2;
    	return $this->render('newmore',['type'=>$type]);
    }
    
    /**
     * 异步获取更多页面的游戏
     * page 页数从1开始
     * type 0:最热游戏    1：最新游戏   2：全部游戏
     */
    public function actionGetmoregame(){
    	if(!yii::$app->request->isAjax || !isset($_POST['page']) || !isset($_POST['type'])){
    		return json_encode(['errorcode'=>1001,'msg'=>'网络异常，请稍后再试']);
    	}
    	$type = Helper::filtdata($_POST['type'],'INTEGER');
    	$page =  Helper::filtdata($_POST['page'],'INT');
    	if($type===false || !$page){
    		return json_encode(['errorcode'=>1001,'msg'=>'网络异常，请稍后再试']);
    	}
    	//不存在游戏缓存时则从数据库中读取
    	if(($type==0 && !yii::$app->cache->get('newhotgamearr')) || ($type==1 && yii::$app->cache->get('newgamearr')) || ($type==2 && yii::$app->cache->get('newallgamearr')) ){
    		$this->getGame(); //更新游戏缓存
    	}
    	$gamearr = array(); //存储查询的数据
    	$pagenum = 20;    //条数
    	$start =  $pagenum * ($page-1);   //开始位置
    	switch ($type){
    		case 0 :$gamearr = array_slice(yii::$app->cache->get('newhotgamearr'),$start,$pagenum);break;   //获取最热游戏记录
    		case 1 :$gamearr = array_slice(yii::$app->cache->get('newgamearr'),$start,$pagenum);break;		//获取最新游戏记录
    		case 2 :$gamearr = array_slice(yii::$app->cache->get('newallgamearr'),$start,$pagenum);break;	//获取全部游戏记录
    	}
    	$errcode = $gamearr?0:1002;   //0：数据读取成功还可继续请求， 1002 数据已读完,无法继续读取
    	return json_encode(['errorcode'=>$errcode,'msg'=>'数据读取成功','gamearr'=>$gamearr]);
    }
    
    /**
     * 跳转更多资讯
     */
    public function actionMoreconsult(){
    	return $this->render('newconsult');
    }
    
    
    /**
     * 异步获取更多页面的资讯
     * page 页数从1开始
     * type 0:最热游戏    1：最新游戏   2：全部游戏
     */
    public function actionGetmoreconsult(){
    	if(!yii::$app->request->isAjax || !isset($_POST['page'])){
    		return json_encode(['errorcode'=>1001,'msg'=>'网络异常，请稍后再试']);
    	}
    	$page =  Helper::filtdata($_POST['page'],'INT');
    	if( !$page){
    		return json_encode(['errorcode'=>1001,'msg'=>'网络异常，请稍后再试']);
    	}
    	//不存在游戏缓存时则从数据库中读取
    	if(!yii::$app->cache->get('newconsult')){
    		$this->updateconsultcache();  //更新资讯信息缓存
    	}
    	$pagenum = 20;    //条数
    	$start =  $pagenum * ($page-1);   //开始位置
    	$consultarr = array_slice(yii::$app->cache->get('newconsult'),$start,$pagenum); //获取最热游戏记录
    	$errcode = $consultarr?0:1002;   //0：数据读取成功还可继续请求， 1002 数据已读完,无法继续读取
    	return json_encode(['errorcode'=>$errcode,'msg'=>'数据读取成功','consultarr'=>$consultarr]);
    }
    
    
    /**
     * 更新通知的缓存数据
     */
    private function updateconsultcache(){
    	$consult = Consult::find()->where(['state'=>1])->orderBy('type desc,sort desc,createtime desc')->limit(100)->asArray()->all();//查询100条咨询信息
    	yii::$app->cache->set('newconsult',$consult);
    }
    
    
    /**
     * 活动提示是否显示
     */
    private function isshow(){
    	if(yii::$app->session->get('isshow')){
    		$showtime = yii::$app->session->get('isshow');
    		if(time()-$showtime>1800){//时间超过一个小时则显示
    			yii::$app->session->set('isshow',time()); //释放锁
    			return true;
    		}else{
    			return false;
    		}
    	}else{
    		yii::$app->session->set('isshow',time()); //释放锁
    		return true;
    	}
    }
    
    //app首页壳
    public function actionGamelist() {
    	return $this->render("gamelist");
    	$limitnum = yii::$app->params['limitnum'];
    	if(yii::$app->cache->get('carousel')){ //cahce不存在时则去数据库查询
    		$carousel = yii::$app->cache->get('carousel');
    	}else{
    		$carousel = Carousel::find()->where(['state'=>1])->orderBy('sort desc')->limit(4)->asArray()->ALL();
    		YII::$app->cache->set('carousel',$carousel);
    	}
    	if(yii::$app->cache->get('hotgame')|| yii::$app->cache->get('gamearr')){//不存在热门游戏或者游戏的缓存时则从数据库读取
    		$gamearr = array_slice(yii::$app->cache->get('gamearr'),0,50);
    		$hotgame = array_slice(yii::$app->cache->get('hotgame'),0,20);
    		$allgame =  array_slice(array_merge(yii::$app->cache->get('gamearr'),yii::$app->cache->get('hotgame')),0,200);
    	}else{
    		$this->getGame();
    		$gamearr = array_slice(yii::$app->cache->get('gamearr'),0,50);
    		$hotgame = array_slice(yii::$app->cache->get('hotgame'),0,20);
    		$allgame = array_slice(array_merge(yii::$app->cache->get('gamearr'),yii::$app->cache->get('hotgame')),0,200);
    	}
    	 
    	if(yii::$app->session['playgame']){
    		$playgame = array_slice(yii::$app->session['playgame'],0,5);
    	}else{
    		$this->newplay(5); //获取最近在玩游戏
    		$playgame = array_slice(yii::$app->session['playgame'],0,5);
    	}
    	$user = yii::$app->session['user'];
    	yii::$app->session['typemenu'] = 1;
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
     * 跳转咨询详情页
     */
    public function actionGameactivity(){
    	return $this->render('activity');
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
    		$openid = $user->openid;
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
    	/* $gamearr = false;//GameRedis::find()->where(['state'=>1])->asArray()->all();
    	$gamearr && $gamearr = Helper::quick_sort($gamearr,'sort');//按某个字段排序
    	if(!$gamearr){//redis不存在时，则去数据库查 */
    		$gamearr = Game::find()->where(['state'=>1])->orderBy('sort desc')->limit(200)->asArray()->all();//游戏
//     	    if($gamearr){//存在保存数据库
//     	    	$this->savegameredis($gamearr,2);
//     	    }
    	/* } */
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
    
    
     public function actionGamedetail(){
    	if(isset($_GET['id'])){
    		$gid = Helper::filtdata($_GET['id'],'INT');
    		if(!$gid){//id参数不存在时则跳转首页
    			$this->redirect('/index/index.html');
    		}
    		$gameinfo = (new \yii\db\Query())
    		->select('gg.id,gg.name,,gg.intro,gg.game_url,gg.head_img,gc.compname,gg.detailimg,gg.descript,gg.intro,gg.r_company,gg.article')
    		->from('g_game AS gg')
    		->leftJoin('g_company AS gc','gc.id = gg.cid')
    		->where(['gg.id'=>$gid])
    		->one();
    		if(!$gameinfo){ //不存在该款游戏时则跳转首页
    			$this->redirect('/index/index.html');
    		}
    		$user = yii::$app->session['user'];//用户信息
    		if($user){
    			$uid = $user->id;
    			$uuid = "AND number not in (select number from g_gift where uid=$uid)";
    		}else{
    			$uuid = '';
    		}
    		$gift = \Yii::$app->db->createCommand("SELECT id,gid,gift_name,game_name,content,CDKEY,state,createtime,count(gift_name) as num,game_image,number,payment,gifttype,validtime from g_gift where gid=:gid and state=0 AND type=1 $uuid group By number order By createtime desc",[':gid'=>$gid])->queryAll();
    		$hotgame = array_slice(yii::$app->cache->get('hotgame'),0,8);//热门游戏//Game::find()->where(['type'=>1,'state'=>1])->limit(8)->orderBy('sort asc')->asArray()->select('id,head_img,name')->all();//热门游戏
    		if(!$hotgame){//热门游戏缓存不存在时，则去数据库重新查询
    			$this->getGame();
    			$hotgame = array_slice(yii::$app->cache->get('hotgame'),0,8);
    		}
    		return $this->render('gamedetail',[
    				'gameinfo'=>$gameinfo,
    				'gift'=>$gift,
    				'hotgame'=>$hotgame,
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
    
    /**
     * 版本控制接口
     */
    public function actionGetgameversion(){
    	return json_encode(['version'=>'1.0.0']);
    }
}