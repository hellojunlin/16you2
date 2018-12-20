<?php 
namespace backend\controllers;

use yii;
use backend\controllers\BaseController;
use common\common\Helper;
use common\models\Company;
use yii\data\Pagination;
use common\models\Plateform;
use common\models\Order;
use common\models\Manage;
use common\redismodel\PlatformRedis;

class PlatformController extends BaseController{
	//流量主平台首页
    public function actionIndex() { 
    	$pid = yii::$app->session->get('pid'); //权限管理
    	//分页
    	$curPage = Yii:: $app->request->get( 'page',1);
    	$pageSize = yii::$app->params['pagenum'];
    	
    	//搜索
    	$value  = Helper::filtdata(Yii:: $app->request->get('keyword',''));
    	$select = Yii:: $app->request->get('selectval','');
    	$search = ($value)?['like',$select,$value]: '';
    	$query = (new \yii\db\Query())
    	->select('gp.id,start_img,gp.pname,gp.punid,gp.state,gp.cid,gp.createtime,gc.compname,gp.remark,gp.code_img,gp.sort')
    	->from('g_plateform AS gp') 
    	->leftJoin('g_company AS gc','gc.id = gp.cid')
    	->orderBy('gp.sort DESC');
    	$pid && ($query = $query->where(["gp.id"=>$pid]));
    	$data = Helper::getPages($query,$curPage,$pageSize,$search);
    	$data['data'] =  ($data['data'])?$data['data']->all():'';
    	$pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
    	//菜单定位
    	unset(yii::$app->session['localfirsturl']);
    	yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/platform/index.html';
    	$managemodel = yii::$app->session['tomodel'];
    	return $this->render('index', [
    			'data' => $data, 
    			'pages' => $pages,
    			'value' => $value,
    			'select'=>$select,
    			'value'=>$value,
    			'managemodel'=>$managemodel,
    			]);
        return $this->render("index");
    } 
    
    /**
     * 平台添加页面
     */
    public function actionToadd() {
    	 $company = Company::find()->asArray()->all();
    	 $role =   yii::$app->authManager->getRoles();
    	 return $this->render("add",[
				'company'=>$company,
    	 		'role'=>$role,
    	 ]);
    }
    
    /**
     * 平台编辑页面
     */
    public function actionToedit() {
    	if(isset($_GET['id'])){
    		$id = Helper::filtdata($_GET['id'],'INT');
    		if($id){
    			//$platform = Plateform::findOne(['id'=>$id]);
    			$platform = (new \yii\db\Query())
    			->select('gp.id, gp.punid,gp.cid,gp.pname,gp.state,gp.createtime,gp.start_img,gm.id as mid,gp.remark,gp.code_img,gp.sort')
    			->from(' g_plateform as gp')
    			->leftJoin('g_manage as gm','gp.id=gm.g_p_id and gm.type=0')
    			->where(['gp.id'=>$id])
    			->one();
    			if(!$platform){//该平台不存在
    				exit;
    			}
    			$company = Company::find()->asArray()->all();
    			$assignment = yii::$app->authManager->getAssignments($platform['mid']);
    			$roles =    yii::$app->authManager->getRoles();
    			$role = '';
    			if(!empty($assignment)){
    				foreach ($assignment as $ass){
    					$role = yii::$app->authManager->getRole($ass->roleName);
    				}
    			}
    			return $this->render("edit",[
    					'company'=>$company,
    					'platform'=>$platform,
    					'roles'=>$roles,
    					'role'=>$role,
    					]);
    		}
    	}
    }
    
    /**
     * 平台添加编辑
     */
    public function actionAdd(){
      if(yii::$app->request->isAjax){
            $punid = Helper::filtdata($_POST['punid']); 
            $cid = Helper::filtdata($_POST['cid'],'INT'); //公司id
            $pname = Helper::filtdata($_POST['pname']);
            $state = Helper::filtdata($_POST['state'],'INTEGER');
            $role = Helper::filtdata($_POST['role']);
            $password = Helper::filtdata($_POST['password']);
            $sort = Helper::filtdata($_POST['sort'],'INTEGER');
            $code_img = isset($_POST['code_img'])?Helper::filtdata($_POST['code_img']):'';
            if($punid===false || !$cid || $pname===false || $state===false || $sort===false){
                return json_encode([
                        'errorcode'=>1001,
                        'info'=>'网络异常，稍后在试！！！', 
                        ]);
            }
            $plateform = new Plateform();
           // $redis = new PlatformRedis();
            if(isset($_POST['id'])){//编辑
                $id = Helper::filtdata($_POST['id'],'INT');
                if(!$id){
                    return json_encode([
                            'errorcode'=>1001,
                            'info'=>'网络异常，稍后在试！！！',
                            ]);
                }
                $plateform = $plateform->findOne(['id'=>$id]);
              /*   $redis1 = $redis->findOne(['id'=>$id]);
                $redis1&& $redis = $redis1; */
                $plateuid = $plateform->punid;
                if($plateform->punid!=$punid){
                    $punidres = $plateform->findOne(['punid'=>$punid]);
                    if($punidres){
                        return json_encode([
                                'errorcode'=>1001,
                                'info'=>'该平台标识id已存在，请换一个！',
                                ]);
                    }
                }
            }else{
                $punidres = $plateform->findOne(['punid'=>$punid]);
                if($punidres){
                    return json_encode([
                            'errorcode'=>1001,
                            'info'=>'该平台标识id已存在，请换一个！',
                            ]);
                }
                $plateuid = $punid;
            }
            $plateform->punid = $punid;
            $plateform->cid = $cid;
            $plateform->pname = $pname;
            $plateform->state = $state;
            $simage = Helper::filtdata($_POST['image']);
            $plateform->remark = isset($_POST['remark'])?Helper::filtdata($_POST['remark']):'';
            $simage1 = $plateform->start_img;
            $plateform->start_img = $simage;
            $plateform->code_img = $code_img;
            $plateform->sort = $sort;
            $plateform->createtime = time();
            if($plateform->save()){
                if($simage!=$simage1){
                    @unlink(dirname(dirname(__FILE__)).'/web/media/images/plateform/'.$simage1);
                }
               /*  $redis->id = $plateform->id;
                $redis->punid = $plateform->punid;
                $redis->cid = $plateform->cid;
                $redis->pname = $plateform->pname;
                $redis->state = $plateform->state;
                $redis->createtime = $plateform->createtime;
                $redis->start_img = $plateform->start_img;
                $redis->save(); */
                if(yii::$app->session->get('pid')){//更新平台id
                    $pidarr = yii::$app->session->get('pid');
                    (!in_array($plateform->id,$pidarr)) && $pidarr[] = $plateform->id;
                    yii::$app->session->set('pid',$pidarr);
                }
                $manager = Manage::findOne(['username'=>$plateuid,'g_p_id'=>$plateform->id]);
                if($manager){
                    ($manager->username!=$punid) && $manager->username = $punid;
                    ($password!='') && ($manager->password!=htmlspecialchars(trim(rand(1000,9999).md5($password)))) && $manager->password =htmlspecialchars(trim(rand(1000,9999).md5($password)));
                    ($manager->role !=$role) && $manager->role = $role;
                    $manager->updated_at = time();
                    $manager->state = $state;
                    $manager->remark = $pname;
                }else{
                    $manager = new Manage();
                    $manager->username = $punid;
                    $manager->password =htmlspecialchars(trim(rand(1000,9999).md5($password)));
                    $manager->role = $role;
                    $plateform->state = $state;
                    $manager->updated_at = time();
                    $manager->created_at = time();
                    $manager->g_p_id = $plateform->id;
                    $manager->type = 0;  //0 平台商， 1游戏商
                    $manager->remark = $pname;
                }
                if($manager->save()){
                    if(isset($_POST['role'])){
                        yii::$app->authManager->revokeAll($manager->id);//先删除所有角色
                    }
                    $role = yii::$app->authManager->getRole($_POST['role']);
                    $res = yii::$app->authManager->assign($role, $manager->id); //重新添加角色
                }
                $info = (isset($_POST['id']))? '编辑成功':'添加成功';
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
     * 删除平台
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
            $plateform  = plateform::findOne(['id'=>$id]);  //查找平台
            $manage = '';
            if($plateform){
                $manage = Manage::findOne(['username'=>$plateform->punid ,'g_p_id'=>$plateform->id]);
                $res = $plateform->delete();
                if($res){
                        @unlink(dirname(dirname(__FILE__)).'/web/media/images/platform/'.$plateform->start_img);
                        @unlink(dirname(dirname(__FILE__)).'/web/media/images/platform/'.$plateform->code_img);
                     /*    $redis = PlatformRedis::find()->where(['id'=>$id])->one();
                        if($redis){
                            $redis->delete();
                        } */
                        $pidarr = yii::$app->session->get('pid');
                        if($pidarr){
                             foreach ($pidarr as $k=>$p){
                                if($p['id']== $id){
                                    unset($pidarr[$k]);
                                }
                             }
                             yii::$app->session->set('pid',$pidarr);
                        }
                    $manage &&  $manage->delete();
                    $manage && yii::$app->authManager->revokeAll($manage->id);
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
              $platcomp = plateform::findOne(['id'=>$id]);
              if(!$platcomp){
                  return json_encode([
                        'errorcode'=>1001,
                        'info'=>'改平台不存在,请刷新在试',
                        ]);
              }
              $platcomp->state = $state;
              if($platcomp->save()){
                 /*  $plat = PlatformRedis::find()->where(['id'=>$id])->one();
                  $plat->state = $state;
                  $plat->save(); */
                  $manager = Manage::findOne(['username'=>$platcomp->punid,'g_p_id'=>$platcomp->id]);
                  if($manager){
                    $manager->state = $state;
                    $manager->save();
                  }
                  $info = ($state==0)?'禁止成功':'启用成功';
                  return json_encode([
                        'errorcode'=>0,
                        'info'=>$info,
                        ]);
              }else{
                  return json_encode([
                        'errorcode'=>1001,
                        'info'=>'该平台不存在,请刷新在试',
                        ]);
              }
        }
    }
    
    
    /**
     * 平台统计页面
     */
    public function actionTocount() {
    	$manage_pid = yii::$app->session->get('pid'); //权限管理
    	$setime = '';
    	if($manage_pid){//平台管理则或者平台商
    		$pidwhere = ['gp.id'=>$manage_pid];
    		if(yii::$app->session->get('platepid')==6){
    			$setime = 1501516800;
    		}
    	}else{//超级管理员
    		$pidwhere = '';
    	}
    	//分页
    	$curPage = Yii:: $app->request->get( 'page',1);
    	$pageSize = yii::$app->params['pagenum'];
    	//搜索
    	$value = Helper::filtdata(Yii:: $app->request->get('keyword',''));
    	$search = ($value)?['like','gp.pname',$value]: '';
    	
        $start_time = Yii:: $app->request->get('start_time','');
        $end_time = Yii:: $app->request->get('end_time');
        $starttime = $start_time?strtotime($start_time):strtotime(date('1970-01-01'));
        $endtime = $end_time?strtotime($end_time):time();
    	$mpid = ($manage_pid)?['is_hide'=>1]:'';
    	$query = (new \yii\db\Query())
    	->select('go.id,go.pid,gp.pname,COUNT(go.num) as num,SUM(go.price*go.num) as price,gp.remark')
    	->from('g_order AS go')
    	->leftJoin('g_plateform AS gp','gp.id = go.pid')
    	->groupBy('gp.id')
    	->where("go.state=2")
    	->andWhere("go.createtime between $starttime and $endtime")
    	->orderBy('go.createtime DESC')
    	->andWhere($mpid);
    	($pidwhere) && $query = $query->andWhere($pidwhere);
        $setime && $query = $query->andWhere(['between','go.createtime',$setime,'1525104000']);
    	$data = Helper::getPages($query,$curPage,$pageSize,$search);
    	$data['data'] =  ($data['data'])?$data['data']->all():'';
    	$pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
    	//菜单定位
    	unset(yii::$app->session['localfirsturl']);
    	yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/platform/tocount.html';
    	$managemodel = yii::$app->session['tomodel'];
    	return $this->render('count', [
    			'data' => $data,
    			'pages' => $pages,
    			'search' => $value,
    			'start_time' => $start_time,
    			'end_time' => $end_time,
    			'managemodel'=>$managemodel,
    			]);
    }
    
    /**
     * 平台统计详情页面
     */
    public function actionDetacount(){
    	if($_GET['id']){
    		$pid = Helper::filtdata($_GET['id'],'INT');
    		$discount = 1;
    		if(!$pid){
    			exit;
    		}
    		if(yii::$app->session->get('platepid')==6){
    			///$discount = 0.85;
    		}
    		$year = substr(date("Y-m-d"),0,4);//当前年份
    		$qtime = (isset($_GET['starttime'])) ? Helper::filtdata($_GET['starttime']):''; //查询的年份
    		$qyear = (!empty($qtime))? substr($qtime,0,4):$year;
    		$first = $qyear."0101";  //当前年份的第一天
    		$end = $qyear."1231";   //当前年份的最后一天
    		$firsttime = strtotime($first);  //当前年份第一天的时间戳
    		$endtime = strtotime($end);
    		$order = Order::find()->where(['state'=>2,'pid'=>$pid])->andWhere(['between', 'createtime', $firsttime, $endtime])->asArray()->all();  //查询当前年份的订单和金额
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
    					$pricearr[$vmonth] = $pricearr[$vmonth]+($o['price']*$o['num']*$discount);
    				}
    			}
    		}
    		//统计当月订单数
    		$orderdata =implode(',',$orderarr);
    		$pricedata = implode(',',$pricearr);
    		return $this->render('detacount',[
    				'orderdata'=>$orderdata,
    				'pricedata'=>$pricedata,
    				'pid'=>$pid,
    				'year'=>$qyear,
    				]);
    	}
    }

    //订单流水
    public function actionOrderc(){
        $manage_pid = yii::$app->session->get('pid'); //权限管理
        if($manage_pid){//平台管理则或者平台商
            if(yii::$app->session->get('platepid')==6){
                $gcreatime = ['>=','go.createtime','1501516800'];//显示2017年8月份以后的数据
            }
        }else{
            $gcreatime = '';
        }
        //分页
        $curPage = Yii:: $app->request->get( 'page',1);
        $tomodel = Yii:: $app->session->get('tomodel');
        $pid = Helper::filtdata(Yii:: $app->request->get('id'),'INT');
        $where['go.pid'] = $pid;
        $pageSize = 100;
        //搜索
        $keyword = Yii:: $app->request->get('keyword','');    
        $value = Helper::filtdata(Yii:: $app->request->get('value',''));
        $search = ($value)?['like',$keyword,$value]: '';
        $start_time = Yii:: $app->request->get('start_time','');
        $end_time = Yii:: $app->request->get('end_time');
        $endtime = $end_time?strtotime($end_time)+3600*24:time();
        $starttime = $start_time?strtotime($start_time):strtotime(date('Y-m'));
        $mpid = ($manage_pid)?['is_hide'=>1]:'';
        $mpid1 = ($manage_pid)?('is_hide=1 and'):'';
        $query = (new \yii\db\Query())
                ->select('go.id as id,propname,username,name,price,go.num,go.state,orderID,go.createtime,pname,districtID,Unique_ID')
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
        trim($pid) && $pid = "o.pid=$pid and";  
        $search1 = $value?"$keyword like '%$value%' and":'';
        //统计
        $order = \Yii::$app->db->createCommand("SELECT sum(o.price) as count_p FROM g_order o left Join g_game gg on gg.id=o.gid left Join g_user u on u.id=o.uid WHERE $mpid1 o.state=2 and $search1 $pid o.createtime BETWEEN $starttime and $endtime")->queryOne();
        return $this->render('orderc', [
             'data' => $data,
             'pages' => $pages,
             'value' => $value,
             'keyword' => $keyword,
             'start_time' => $start_time,
             'end_time' => $end_time,
             'starttime' => $starttime,
             'endtime' => $endtime,
             'count_p' => $order['count_p'],
        ]);
    } 

       /**
     * 判断添加时的平台唯一标识是否存在
     */
    public function actionUniqueone(){
        $punid = Helper::filtdata(Yii::$app->request->post('punid'),'STRING');
        $res = Plateform::findOne(['punid'=>$punid]);
        if($res){
        	$code = 1;
        	if(isset($_POST['pid'])){ //编辑
        	 ($res->id == $_POST['pid']) &&$code =0;
        	}
            return $code;
        }else{
            return 0;
        }
    }
    
    /**
     * 异步上传图片
     */
    public function actionSubimg(){
        if(Yii::$app->request->isAjax){
          $imgb = $_POST ['imgbase64'];
          $img = array ();
          $imgdir = dirname(dirname(__FILE__)).'/web/media/images/plateform/';
          $image = Helper::imgurl($imgb, $imgdir);
          if ($image) {
            return json_encode ( [
                'info' => '图片截取成功',
                'errorcode' => '0',
                'imgurl'=>$image
            ] );
          } else {
            return json_encode ( [
                'info' => '您好，图片异常，请更换其它图片',
                'errorcode' => '1001'
            ] );
            exit;
          }
        }
    }
    
    /**
     * 异步删除图片
     */
    public function actionDelimg() {
    	if (Yii::$app->request->isAjax &&isset($_POST ['imgsrc'])) {
    		$imgurl = Helper::filtdata($_POST ['imgsrc']);
    		$url = yii::$app->basePath . '/web' . $imgurl;
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
    	}
    }
}