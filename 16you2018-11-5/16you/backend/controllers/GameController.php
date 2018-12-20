<?php 
namespace backend\controllers;

use yii;
use backend\controllers\BaseController;
use common\common\Helper;
use common\models\Company;
use yii\data\Pagination;
use common\models\Game;
use common\models\Order;
use common\models\DefaultSetting;
use common\models\SettingProportion;
use common\models\Plateform;
use common\redismodel\GameRedis;
use common\models\Gift;
use common\models\Refund;
use common\common\Phpexcelr;

class GameController extends BaseController{
    //游戏首页
    public function actionIndex() { 
        $gidarr = array();
        $pid = '';
        $cid = '';
        $managemodel = yii::$app->session['tomodel'];
        if($managemodel->type==1){//者游戏商
            $cid = $managemodel->g_p_id;
        }else{
            $pid = $managemodel->g_p_id;  //平台id         
        }
        //分页
        $curPage = Yii:: $app->request->get( 'page',1);
        $pageSize = yii::$app->params['pagenum'];
        //搜索
        $value = Helper::filtdata(Yii:: $app->request->get('keyword',''));
        $select = Yii:: $app->request->get('selectval','');
        $type = Yii:: $app->request->get('type','');
        $search = ($value)?['like',$select,$value]: '';
        $query = (new \yii\db\Query())
        ->select('gg.unique,gg.id,gg.name,gg.state,gg.label,gg.intro,gg.game_url,gg.head_img,gg.type,gg.createtime,gc.compname,gg.sort')
        ->from('g_game AS gg') 
        ->leftJoin('g_company AS gc','gc.id = gg.cid')
        ->leftJoin('g_plateform AS gp','gp.cid = gc.id')
        ->orderBy('gg.sort DESC');
        $cid && ($query = $query->where(["gg.cid"=>$cid])); 
        $pid && ($query = $query->where(["gp.id"=>$pid]));
        $type && ($query = $query->where(["gg.type"=>$type]));
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        $data['data'] =  ($data['data'])?$data['data']->all():'';
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/game/index.html';
        return $this->render('index', [
                'data' => $data, 
                'pages' => $pages,
                'value' => $value,
                'select'=>$select,
                'type'=>$type,
                ]);
    } 
    
    //游戏备注首页
    public function actionRemarkindex() {
    	$gidarr = array();
    	$pid = '';
    	$cid = '';
    	$managemodel = yii::$app->session['tomodel'];
    	if($managemodel->type==1){//者游戏商
    		$cid = $managemodel->g_p_id;
    	}else{
    		$pid = $managemodel->g_p_id;  //平台id
    	}
    	//分页
    	$curPage = Yii:: $app->request->get( 'page',1);
    	$pageSize = yii::$app->params['pagenum'];
    	//搜索
    	$value = Helper::filtdata(Yii:: $app->request->get('keyword',''));
    	$select = Yii:: $app->request->get('selectval','');
    	$search = ($value)?['like',$select,$value]: '';
    	$query = (new \yii\db\Query())
    	->select('gg.id,gg.name,gg.unique,gg.head_img,gg.createtime,gg.remark,gc.compname')
    	->leftJoin('g_company AS gc','gc.id = gg.cid')
    	->from('g_game AS gg')
    	->orderBy('gg.sort DESC')
    	->where("remark!=''");
    	$cid && ($query = $query->andWhere(["gg.cid"=>$cid]));
    	$pid && ($query = $query->andWhere(["gp.id"=>$pid]));
    	$data = Helper::getPages($query,$curPage,$pageSize,$search);
    	$data['data'] =  ($data['data'])?$data['data']->all():'';
    	$pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
    	//菜单定位
    	unset(yii::$app->session['localfirsturl']);
    	yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/game/remarkindex.html';
    	return $this->render('remarkindex', [
    			'data' => $data,
    			'pages' => $pages,
    			'value' => $value,
    			'select'=>$select,
    			'value'=>$value,
    			]);
    }
    
    /**
     * 游戏添加页面
     */
    public function actionToadd() {
        $company = Company::find()->where('id!=3')->asArray()->all();
         return $this->render("add",[
                'company'=>$company,
         ]);
    }
    
    /**
     * 游戏编辑页面
     */
    public function actionToedit() {
    if(isset($_GET['id'])){
            $id = Helper::filtdata($_GET['id'],'INT');
            if($id){
                $game = Game::findOne(['id'=>$id]);
                if(!$game){//该游戏不存在
                    exit;
                }
                $company = Company::find()->where('id!=3')->asArray()->all();
                return $this->render("edit",[
                        'company'=>$company,
                        'game'=>$game,                
                        ]);
            }
        }
    }
    
    /**
     * 游戏添加编辑
     */
    public function actionAdd(){
        if(yii::$app->request->isAjax && isset($_POST['name']) && isset($_POST['unique']) && isset($_POST['descript']) 
            && isset($_POST['cid'])&& isset($_POST['state']) && isset($_POST['intro'])&& isset($_POST['game_url']) 
            && isset($_POST['type']) && isset($_POST['sort'])  && isset($_POST['gametype']) && isset($_POST['remark'])){

            $name = Helper::filtdata($_POST['name']);
            $unique = Helper::filtdata($_POST['unique']);
            $descript = Helper::filtdata($_POST['descript']);
            $cid = Helper::filtdata($_POST['cid'],'INT');
            $state = Helper::filtdata($_POST['state'],'INTEGER');
            $label = (isset($_POST['label']))?$_POST['label']:'';
            $intro = Helper::filtdata($_POST['intro']);
            $game_url = Helper::filtdata($_POST['game_url']);
            $type = Helper::filtdata($_POST['type'],'INTEGER');
            $sort = Helper::filtdata($_POST['sort'],'INTEGER');
            $h_img = (isset($_POST['logo']))?Helper::filtdata($_POST['logo']):'0';
            $image = isset($_POST['detailimg'])?$_POST['detailimg']:'';
            $fgamelogo = isset($_POST['fgamelogo'])?$_POST['fgamelogo']:'';
            $gametype = Helper::filtdata($_POST['gametype'],'INTEGER');
            $new_game = Helper::filtdata($_POST['new_game'],'INTEGER');
            $r_company = (isset($_POST['r_company']))?Helper::filtdata($_POST['r_company']):'';
            $article = (isset($_POST['article']))?Helper::filtdata($_POST['article']):'';
            $remark = Helper::filtdata($_POST['remark']);
            // 描述  标签 简介允许为空
            $datastrarr = ['游戏名称'=>$name,'游戏唯一标识'=>$unique,'游戏链接'=>$game_url];
            $checkres = helper::checkingdata($datastrarr,2);
            if($checkres['errorcode']=='1001'){
                return json_encode ( [
                        'info' => '您好,请填写'.$checkres['info'],
                        'errorcode' => '1001'
                        ] );
                exit ();
            }
            
            $dataarr = ['游戏类型'=>$gametype,'所属公司'=>$cid,'类别'=>$type,'状态'=>$state,'排序'=>$sort];
            $check_res = helper::checkingdata($dataarr,1);
            if($check_res['errorcode']=='1001'){
                return json_encode ( [
                        'info' => '您好'.$check_res['info'].'填写不正确,请重写！',
                        'errorcode' => '1001'
                        ] );
                exit ();
            }
            
            $game = new Game();
            // $gameredis = new GameRedis();
            if(isset($_POST['id'])){//编辑
                $id = Helper::filtdata($_POST['id'],'INT');
                if(!$id){
                    return json_encode([
                            'errorcode'=>1001,
                            'info'=>'网络异常，稍后在试！！！',
                            ]);
                }
                $game = $game->findOne(['id'=>$id]);
                // $gameredis = $gameredis->findOne(['id'=>$id]);
                //$gameredis = ($gameredis)?$gameredis:new GameRedis();
            }else{
                $game->createtime = time();
            }
            $game->name = $name;
            $game->cid = $cid;
            $game->descript = $descript;
            $game->unique= $unique;
            $game->state = $state;
            $game->label = ($label)?json_encode($label):'';
            $game->intro = $intro;
            $game->game_url = $game_url;
            $game->type = $type;
            $game->sort = $sort;
            $game->detailimg = $image;
            $game->head_img = $h_img;
            $game->game_type = $gametype;
            $game->is_newgame = $new_game;
            $game->r_company = $r_company;
            $game->article = $article;
            $game->remark = $remark;
            $game->f_gamelogo = $fgamelogo;
            if($game->save()){   
            	if(isset($_POST['id'])){//编辑
              		  \Yii::$app->db->createCommand("UPDATE g_gift SET game_image=:img,game_name=:name WHERE gid=:id",[':id'=>$game->id,':img'=>$h_img,':name'=>$name])->execute();  //实时更新礼包游戏名称图片
            	}
                if(!isset($_POST['id'])){                  
                    $info = '添加成功';
                }else{
                    $info = '编辑成功';
                }
                $this->setCache();
                return json_encode([
                        'errorcode'=>0,
                        'info'=>$info,
                        ]);
            }else{
                $info = (isset($_POST['id']))? '编辑失败':'添加失败';
                return json_encode([
                        'errorcode'=>1001,
                        'info'=>$info,
                        ]);
            }
        }
    }
    
    /**
     * 更新游戏缓存
     * 
     */
    private function setCache(){
            $gamearr = Game::find()->where(['state'=>1])->orderBy('sort desc')->asArray()->select('id,name,descript,label,head_img,game_url,type,is_newgame,f_gamelogo')->all();//非热门游戏  
            $hotgame = array();
            $Casualgame = array();  
            $newallgamearr = $gamearr;   //存储所有游戏
            $newhotgamearr = array();	//存储所有热门游戏
            $newrelaxarr = array();		//存储所有休闲游戏
            $newgamearr = array();		//存储所有新游游戏
            if($gamearr){
                $index = 1;
                foreach ($gamearr as $k=>$game){
                	if($game['type']==1){//存储所有热门游戏
                		$newhotgamearr[] = $game;
                	}
                	if($game['type']==2){//存储所有休闲游戏
                		$newrelaxarr[] = $game;
                	}
                	if($game['is_newgame']==1){//存储所有新游游戏
                		$newgamearr[] = $game;
                	}
                    if($index<=50 && $game['type']==1){ //存前50条热门游戏
                        $hotgame[] = $game;  
                        unset($gamearr[$k]);  //删除游戏
                        $index++;
                    }elseif($game['type']==2){//休闲游戏
                        $Casualgame[] = $game;
                    }
                }
            }
            yii::$app->cache->set('hotgame',$hotgame); //热门游戏
            yii::$app->cache->set('Casualgame',$Casualgame); //休闲游戏
            yii::$app->cache->set('gamearr',$gamearr);
            yii::$app->cache->set('newhotgamearr',$newhotgamearr);  //所有热门游戏
            yii::$app->cache->set('newrelaxarr',$newrelaxarr);     //所有休闲游戏
            yii::$app->cache->set('newgamearr',$newgamearr);       //所有新游游戏
            yii::$app->cache->set('newallgamearr',$newallgamearr);      //所有游戏
    }
    
    
    public function actionCheckunique(){
    	if(yii::$app->request->isAjax || isset($_POST['unique'])){
    		$unique = Helper::filtdata($_POST['unique']);
    		$gameobject = Game::findOne(['unique'=>$unique]);
    		if($gameobject){//存在
    			return 1;
    		}else{
    			return 0;
    		}
    	}else{
    		return 1001;
    	}
    }
    
    
    /**
     * 删除游戏
     * @return string
     */
    public function actionDel(){
        if(yii::$app->request->isAjax || isset($_POST['id'])){
            $id = Helper::filtdata(Yii::$app->request->post('id'),'INT');
            if(!$id){
                return json_encode([
                        'errorcode'=>'1001',
                        'info'=>'系统参数错误',
                        ]);
            }
            $res = Game::deleteAll(['id'=>$id]);
            if($res){
                Gift::deleteAll(['gid'=>$id]);
                $this->setCache();
                return json_encode([
                        'errorcode'=>0,
                        'info'=>'删除成功',
                        ]);
            }else{
                return json_encode([
                        'errorcode'=>1001,
                        'info'=>'删除失败',
                        ]);
            }
        }
    }
    
    /**
     * 启用 禁用 改变状态
     */
    public function actionChangestate(){
        if(yii::$app->request->isAjax || isset($_POST['id']) ||isset($_POST['state'])){
            $id = Helper::filtdata($_POST['id'],'INT');
            $state = Helper::filtdata($_POST['state'],'INTEGER');
            if(!$id || $state===false){
                return json_encode([
                        'errorcode'=>1001,
                        'info'=>'网络异常，稍后在试',
                        ]);
            }
            $game = Game::findOne(['id'=>$id]);
            if(!$game){
                return json_encode([
                        'errorcode'=>1001,
                        'info'=>'改游戏不存在,请刷新在试',
                        ]);
            }
            $game->state = $state;
            if($game->save()){
                // $gameredis = GameRedis::findOne(['id'=>$id]);
                // $gameredis = ($gameredis)?$gameredis:new GameRedis();
                //保存到redis中
                // $gameredis->id = $game->id;
                // $gameredis->name = $game->name;
                // $gameredis->cid = $game->cid;
                // $gameredis->descript = $game->descript;
                // $gameredis->unique= $game->unique;
                // $gameredis->state = $game->state;
                // $gameredis->label = $game->label;
                // $gameredis->intro = $game->intro;
                // $gameredis->game_url = $game->game_url;
                // $gameredis->type = $game->type;
                // $gameredis->sort = $game->sort;
                // $gameredis->image = $game->image;
                // $gameredis->head_img = $game->head_img;
                // $gameredis->game_type = $game->game_type;
                // $gameredis->createtime = $game->createtime;
                // $gameredis->save();
                // $this->setCache();
               $info = ($state==0)?'禁止成功':(($state==1)?'启用成功':'下架成功');
                $this->setCache();  //更新游戏缓存
                return json_encode([
                        'errorcode'=>0,
                        'info'=>$info,
                        ]);
            }else{
                return json_encode([
                        'errorcode'=>1001,
                        'info'=>'该游戏不存在,请刷新在试',
                        ]);
            }
        }
    }
    
    
    /**
     * 异步上传图片
     */
    public function actionSubimg() {
        if (Yii::$app->request->isAjax) {
            $imgb = Helper::filtdata($_POST ['imgbase64']);
            if(!$imgb){
                return json_encode ( [
                        'info' => '系统参数错误',
                        'errorcode' => '1002'
                        ] );
                exit ();
            }
            $imgdir = yii::$app->basePath . "/web/media/images/game/";
            if (! is_dir ( $imgdir )) {
                mkdir ( $imgdir, 0777 );
            }
            $savename = uniqid () . '.jpeg';
            $savepath = $imgdir . $savename;
            $claerBase64 = explode ( ',', $imgb );
            $image = $this->base64_to_img ( $imgb, $savepath );
            if ($image) {
                return json_encode ( [
                        'info' => '您好，图片保存成功',
                        'errorcode' => '0',
                        'imgurl' => $savename
                        ] );
            } else {
                return json_encode ( [
                        'info' => '您好，图片异常，请更换其它图片',
                        'errorcode' => '1001'
                        ] );
                exit ();
            }
        }
    }
    
    /**
     * 将base64码转为图片
     *
     * @param unknown $base64_string
     * @param unknown $output_file
     * @return unknown
     */
    private function base64_to_img($base64_string, $output_file) {
        $ifp = fopen ( $output_file, "wb" );
        fwrite ( $ifp, base64_decode ( $base64_string ) );
        fclose ( $ifp );
        return ($output_file);
    }
    
/**
     * 异步删除图片
     */
    public function actionDelimg() {
        if (Yii::$app->request->isAjax &&isset($_POST ['imgsrc']) && isset($_POST['type'])) {
            $imgurl = Helper::filtdata($_POST ['imgsrc']);
            $type = Helper::filtdata($_POST['type'])? $_POST['type'] :0;
            if($type==3){//删除新版首页游戏logo
            	$url = yii::$app->basePath . '/web/media/images/game/fgamelogo/' . $imgurl;
            }else{//删除其它游戏图片
            	$url = yii::$app->basePath . '/web/media/images/game/' . $imgurl;
            }
            $res = @unlink ( $url );
            if ($res) {
                return json_encode ( [
                        'info' => '删除成功！',
                        'errorcode' => 0
                        ] );
            }else{
                return json_encode ( [
                        'info' => '删除失败！',
                        'errorcode' => '1001'
                        ] );
                exit ();
            }
        }else{
        	return json_encode ( [
        			'info' => '网络异常，刷新再试！',
        			'errorcode' => '1001'
        			] );
        	exit ();
        }
    }
    
 /**
     * 游戏统计页面
     */
    public function actionTocount() {
        $manage_pid = yii::$app->session->get('pid'); //权限管理
        $setime = '';
        $platepid='';
        if($manage_pid){ //平台管理员或者平台商
            $g_where = ['id'=>$manage_pid];
            if(yii::$app->session->get('platepid')==6){
            	$setime = 1501516800;
            }
            $platepid = yii::$app->session->get('platepid')?yii::$app->session->get('platepid'):'';
        }else{//超级管理员
            $g_where = '';
        }
        $plate = Plateform::find()->where($g_where)->andWhere(['state'=>1])->select(['id','pname'])->orderBy('sort desc')->asArray()->all();  //查找所有平台
        $pidarr = array();
        if($plate){
        	foreach ($plate as $p){
        		$pidarr[] = $p['id'];
        	}
        }
        $pidstr = implode(',',$pidarr); 
        
        //公司查询
        $cid = yii::$app->request->get('cid','');
        $cname = yii::$app->request->get('cname','');
        $cidarr = array();
        $gid= '';
        if($cid){
        	$company = Game::find()->where(['cid'=>$cid])->asArray()->select('id')->all();
        	if($company){
        		foreach ($company as $c){
        			$cidarr[] = $c['id'];
        		}
        		$gid =  implode(',', $cidarr);
        	}else{
        		$gid = -1;
        	}
        }
        
        //分页
        $curPage = Yii:: $app->request->get( 'page',1);
        $pageSize = yii::$app->params['pagenum'];
        $pid = yii::$app->request->get('pid','');
        $pname = yii::$app->request->get('pname','');
        $andwhere = ($pid)?['pid'=>$pid]:'';
        //搜索
        $startdate = yii::$app->request->get('starttime','');
        $enddate =   yii::$app->request->get('endtime','');
        $starttime = $startdate?strtotime($startdate):strtotime(date('Y-m-d',time()));
        $endtime = $enddate?strtotime($enddate)+3600*24:time();
        $value = Helper::filtdata(Yii:: $app->request->get('keyword',''));
        $search = ($value)?['like','gg.name',$value]: '';
        $mpid = ($manage_pid&&$setime)?['is_hide'=>1]:'';
        $query = (new \yii\db\Query())
        ->select('go.id,go.gid,gg.name,COUNT(go.num) as num,SUM(go.price*go.num) as price')
        ->from('g_order AS go')
        ->leftJoin('g_game AS gg','gg.id = go.gid')
        ->where("go.state in(2,4) and go.pid in ($pidstr)") 
        ->andWhere($andwhere)
        ->andWhere($mpid)
        ->andWhere("go.createtime between $starttime and $endtime")
        ->groupBy('gg.id')
        ->orderBy('price DESC'); 
        $gid && $query = $query->andWhere("go.gid in ($gid)");
        $setime && $query = $query->andWhere(['>=','go.createtime',$setime]);
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        $data['data'] =  ($data['data'])?$data['data']->all():'';
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        //退款记录
        $refund = Refund::find()->where("createtime between $starttime and $endtime");
        $setime && $refund = $refund->andWhere(['>=','createtime',$setime]);
        $refund = $refund->select('gid,COUNT(num) as num,SUM(price*num) as price')->groupBy('gid');
        $gid && $refund->andWhere("gid in ($gid)");
        $refund = $refund->all();
        $refundarr = array();
        if($refund){
        	foreach ($refund as $re){
        		$refundarr[$re['gid']]['price'] = $re['price'];
        	}
        }
        //测试费用
    	$testorder = Order::find()->where("createtime between $starttime and $endtime and uid = 783")->groupBy('gid')->select('gid,COUNT(num) as num,SUM(price*num) as price');
    	$gid && $testorder->andWhere("gid in ($gid)");
    	$testorder = $testorder->all();
    	$testorderarr = array();
    	if($testorder){
    		foreach ($testorder as $to){
    			$testorderarr[$to['gid']]['price'] = $to['price'];
    		}
    	}
        //平台
        $user = yii::$app->session['tomodel'];
        $p_where = ($manage_pid)?['id'=>$manage_pid]:'';
        $plate = Plateform::find()->where($p_where)->andWhere(['state'=>1])->select(['id','pname'])->orderBy('sort desc')->all();  //查找所有平台
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/game/tocount.html';
        $managemodel = yii::$app->session['tomodel'];
        //统计
        $order =  (new \yii\db\Query())
			      ->select('sum(go.price*go.num) as count_p,count(distinct go.uid) as count_o')
			      ->from('g_order AS go')
			      ->leftJoin('g_game AS gg','gg.id = go.gid')
			      ->where("go.state in (2,4) and go.pid in ($pidstr)")
			      ->andWhere($andwhere)
			      ->andWhere($search)
                  ->andWhere($mpid)
			      ->andWhere("go.createtime between $starttime and $endtime");
	    $gid && $order->andWhere("go.gid in ($gid)");
        $order = $order->one();
        $c_where = $platepid?['id'=>$platepid]:'';
        $companyarr = Company::find()->where($c_where)->asArray()->all();
        return $this->render('count', [
                'data' => $data,
                'pages' => $pages,
                'search' => $value,
                'plate' => $plate,
                'pid'=>$pid,
                'managemodel'=>$managemodel,
                'starttime'=>$startdate,
                'endtime'=>$enddate,
        		'order'=>$order,
        		'refundarr'=>$refundarr,
        		'companyarr'=>$companyarr,
                'cid'=>$cid,
                'cname'=>$cname,
        		'pname'=>$pname,
        		'testorderarr'=>$testorderarr,
                ]);
    }
    
    //导出汇总统计数据
    public function actionOutput(){
    	$manage_pid = yii::$app->session->get('pid'); //权限管理
    	if($manage_pid){ //平台管理员或者平台商
    		$g_where = ['id'=>$manage_pid];
    	}else{//超级管理员
    		$g_where = '';
    	}
    	$plate = Plateform::find()->where($g_where)->andWhere(['state'=>1])->select(['id','pname'])->asArray()->all();  //查找所有平台
    	$pidarr = array();
    	if($plate){
    		foreach ($plate as $p){
    			$pidarr[] = $p['id'];
    		}
    	}
    	$pidstr = implode(',',$pidarr);
    	 
    	//公司游戏查询
    	$cid = yii::$app->request->get('cid','');
    	$cidarr = array();
    	$gid= '';
    	if($cid){
    		$company = Game::find()->where(['cid'=>$cid])->asArray()->select('id')->all();
    		if($company){
    			foreach ($company as $c){
    				$cidarr[] = $c['id'];
    			}
    			$gid =  implode(',', $cidarr);
    		}
    	}
    	$pid = yii::$app->request->get('pid','');
    	$andwhere = ($pid)?['pid'=>$pid]:'';
        $mpid = ($manage_pid)?['is_hide'=>1]:'';
    	//搜索
    	$startdate = yii::$app->request->get('starttime','');
        $enddate =   yii::$app->request->get('endtime','');
        $starttime = $startdate?strtotime($startdate):strtotime(date('Y-m-d'));
        $endtime = $enddate?strtotime($enddate)+86400:time();
    	$value = Helper::filtdata(Yii:: $app->request->get('keyword',''));
    	$search = ($value)?['like','gg.name',$value]: '';
    	$order = (new \yii\db\Query())
    	->select('go.id,go.gid,gg.name,COUNT(go.num) as num,SUM(go.price*go.num) as price,go.state')
    	->from('g_order AS go')
    	->leftJoin('g_game AS gg','gg.id = go.gid')
    	->groupBy('gg.id')
    	->where("go.state in(2,4) and go.pid in ($pidstr)")
    	->andWhere($andwhere)
        ->andWhere($search)
    	->andWhere($mpid)
    	->andWhere("go.createtime between $starttime and $endtime")
    	->orderBy('price DESC');
    	$gid && $order->andWhere("gid in ($gid)");
    	$order = $order->all();
    	//退款记录
    	$refund = Refund::find()->where("createtime between $starttime and $endtime")->select('gid,COUNT(num) as num,SUM(price*num) as price')->groupBy('gid');
    	$gid && $refund->andWhere("gid in ($gid)");
    	$refund = $refund->all();
    	$refundarr = array();
    	if($refund){
    		foreach ($refund as $re){
    			$refundarr[$re['gid']] = $re['price'];
    		} 
    	}
    	if($order){ 
    		foreach ($order as $k=>$v){ 
    			$order[$k]['refund'] =  isset($refundarr[$v['gid']])?$refundarr[$v['gid']]:0;
    			$order[$k]['reprice'] =  isset($refundarr[$v['gid']])?$v['price']-$refundarr[$v['gid']]:$v['price'];
    		}
    	}
    
    
    	if(!$order){
    		return '没有数据需要导出';
    	}
    	$header = ['编号','游戏名称','平台总交易金额(元)','退款金额(元)','游戏商总交易金额(元)'];
    	foreach ($order as $k => $v) {
    		$arr[$k]['v'] = $k+1;
    		$arr[$k]['name'] =$v['name'];
    		$arr[$k]['reprice'] = $v['reprice'];
    		$arr[$k]['refund'] = $v['refund'];
    		$arr[$k]['price'] = $v['price'];
    	}
    	$arr = $this->sigcol_arrsort($arr,'reprice',SORT_DESC);
    	$time = date("Y-m-d",$starttime).' - '.date('Y-m-d',$endtime).'_';
    	Phpexcelr::exportData($arr,$header,$time."游戏统计导出",$time."游戏统计导出");
    	exit;//阻止跳转，一定要写，不写会跳转
    }
    
    /**
     * 详细统计页面
     */
    public function actionDetacount(){
        if($_GET['id']){
            $gid = Helper::filtdata($_GET['id'],'INT');
            if(!$gid){
                exit;
            }
            $manage_pid = yii::$app->session->get('pid'); //权限管理
            $pid = yii::$app->request->get('pid');
            $qyear = (isset($_GET['starttime'])) ? substr($_GET['starttime'],0,4) :substr(date("Y-m-d"),0,4); //查询的年份
            $first = $qyear."0101";  //当前年份的第一天
            $end = $qyear."1231";   //当前年份的最后一天
            $firsttime = strtotime($first);  //当前年份第一天的时间戳
            $endtime = strtotime($end);
            $mpid = ($manage_pid)?['is_hide'=>1]:'';
            $order = Order::find()->where(['state'=>2,'gid'=>$gid])->andWhere(['between', 'createtime', $firsttime, $endtime]);
            if($manage_pid){
                $order = $order->andWhere(['pid'=>$manage_pid]);
            }
            $order = $order->andWhere($mpid)->asArray()->all();  //查询当前年份的订单和金额
            $orderarr = array();//存储订单数
            $pricearr = array();//存储交易金额
            for($index=1;$index<=12;$index++){
                $orderarr[$index] = 0;//$index=>天  value=>订单数
                $pricearr[$index] = 0;
            }
            if($order){//存在订单
                foreach ($order as $o){
                    $createtime = date('Y-m-d',$o['createtime']);
                    if(substr($createtime,0,4) == $qyear){  //查询的年份
                        $vmonth = substr($createtime,5,2); //月份
                        if($vmonth<10){
                            $vmonth = substr($vmonth,1,1);
                        }
                        $orderarr[$vmonth] = $orderarr[$vmonth]+1;
                        $pricearr[$vmonth] = $pricearr[$vmonth]+($o['price']*$o['num']);
                    }
                }
            }
            //统计当月订单数
            $orderdata =implode(',',$orderarr);
            $pricedata = implode(',',$pricearr);
    
            //平台
            $user = yii::$app->session['tomodel'];
            $where = ($user->role==-1)?'':['cid'=>$user->id];
            $plate = Plateform::find()->where($where)->andWhere(['state'=>1])->select(['id','pname'])->all();
             
            return $this->render('detacount',[
                    'orderdata'=>$orderdata,
                    'pricedata'=>$pricedata,
                    'gid'=>$gid,
                    'year'=>$qyear,
                    'plate'=>$plate,
                    'pid'=>$pid,
                    ]);
        }
    }

    //订单流水
    public function actionOrderc(){
        $manage_pid = yii::$app->session->get('pid'); //权限管理
        $gcreatime = '';
        if($manage_pid){//平台管理则或者平台商
            if(yii::$app->session->get('platepid')==6){
                $gcreatime = ['>=','go.createtime','1501516800'];//显示2017年8月份以后的数据
            }
        }
        //分页
        $curPage = Yii:: $app->request->get( 'page',1);
        $where['go.gid'] = Helper::filtdata(Yii:: $app->request->get('id'),'INT');
        $pageSize = 100;
        //搜索
        $keyword = Yii:: $app->request->get('keyword','');    
        $value = Yii:: $app->request->get('value','');    
        $search = ($value)?['like',$keyword,$value]: '';
        $start_time = Yii:: $app->request->get('start_time','');
        $end_time = Yii:: $app->request->get('end_time');
        $endtime = $end_time?strtotime($end_time):time();
        $starttime = $start_time?strtotime($start_time):strtotime(date('1970-01-01'));
        $mpid = ($manage_pid)?['is_hide'=>1]:'';
        $query = (new \yii\db\Query())
                ->select('go.id as id,propname,username,name,price,go.num,go.state,orderID,go.createtime,pname,gu.Unique_ID')
                ->from('g_order AS go')
                ->leftJoin('g_game AS gg','gg.id = go.gid')
                ->leftJoin('g_user AS gu','gu.id = go.uid')
                ->leftJoin('g_plateform AS gp','gp.id = go.pid')
                ->where("go.state=2 and go.createtime between $starttime and $endtime")
                ->andWhere($where)
                ->andWhere($mpid)
                ->orderBy('go.createtime desc');
        $gcreatime&&$query = $query->andWhere($gcreatime);
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        $data['data'] =  ($data['data'])?$data['data']->all():'';
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        return $this->render('orderc', [
             'data' => $data,
             'pages' => $pages,
             'value' => $value,
             'keyword' => $keyword,
             'start_time' => $start_time,
             'end_time' => $end_time,
        ]);
    } 

    //选择平台
    public function actionDownload(){
        $data = Plateform::find()->where(['state'=>1])->asArray()->ALL();
        $id = Helper::filtdata(yii::$app->request->post('id',''));
        if($data){
            if($id){
                $game = GamePlate::find()->where(['gid'=>$id])->asArray()->all();
            }else{
                $game = '';
            }
            return json_encode([
                'errorcode'=>0,
                'info'=>$data,
                'game'=>$game
            ]);
        }else{
            return json_encode([
                'errorcode'=>1001,
                'info'=>'暂时没有平台数据',
            ]);
        }
    }
    
    
    /**
     * 数组排序
     */
    private function sigcol_arrsort($data,$col,$type=SORT_DESC){
    	$arr = array();
    	if(is_array($data)){
    		$i=0;
    		foreach($data as $k=>$v){
    			if(key_exists($col,$v)){
    				$arr[$i] = $v[$col];
    				$i++;
    			}else{
    				continue;
    			}
    		}
    	}else{
    		return false;
    	}
    	array_multisort($arr,$type,$data);
    	return $data;
    }
    
    
 	//上传图片
    public function actionFilephoto(){
        if(!isset($_FILES) || !isset($_GET['type'])){
            return json_encode(['false','未检测到有图片信息']);
        }
        $type = Helper::filtdata($_GET['type'],'INT');
        if($type==1){
        	//获取图片信息
        	$file = $_FILES['gamelogo'];
        	$dir = "/web/media/images/game/";
        }else{
        	//获取图片信息
        	$file = $_FILES['mygamelogo'];
        	$dir = "/web/media/images/game/fgamelogo/";
        }
        //获取图片后缀名
        $suffix = substr(strrchr($file['name'], '.'), 1);
        $new_name = uniqid().round('1000,9999').'.'.$suffix;
        $imgdir = yii::$app->basePath . $dir;
        if (! is_dir ( $imgdir )) {
            mkdir ( $imgdir, 0777 );
        }
        $res = move_uploaded_file($file["tmp_name"],$imgdir.$new_name);
        if($res){
            return json_encode(['true',$new_name]);
        }else{
            return json_encode(['false','上传图片失败']);
        }
    }

        /**
     * 游戏推广链接
     * @return [type] [description]
     */
    public function actionReferrallinks(){
        $plat = yii::$app->session['tomodel'];//平台信息
        $company = $plat->remark;
        $pid = $plat->g_p_id;
        if(!$pid){
            $pid = '16you';
            $pid1 = '';
        }else{
        	$pid = $plat->username;
            $pid1 = "!".$plat->username;
        }
        $game = Game::find()->limit(500)->asArray()->all();
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/game/referrallinks.html';
        return $this->render('referrallinks',[
            'game'=>$game,
            'pid'=>$pid,
            'pid1'=>$pid1,
            'company'=>$company
        ]);
    }

    /**
     * 游戏推广建议
     * @return [type] [description]
     */
    public function actionReferralsuggest(){
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/game/referralsuggest.html';
        return $this->render('referralsuggest');
    }
}