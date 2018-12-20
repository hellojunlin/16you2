<?php

namespace backend\controllers;

use Yii;
use common\models\Plateform;
use backend\controllers\BaseController;
use yii\data\Pagination;
use common\common\Helper;
use common\models\Order;
use common\models\User;
use common\redismodel\UserRedis;
use common\models\Gamecurrency;
use common\models\Rebaterecord;
use common\common\Phpexcelr;
use common\models\OrderTest;


/**
 * 用户类
 */
class UserController extends  BaseController{
    /**
     * 进入用户记录页.
     * @return mixed
     */
    public function actionIndex(){
    	$manage_pid = yii::$app->session->get('pid'); //权限管理
    	($manage_pid)?$where['pid'] = $manage_pid:$where='';
    	$pid1 = Helper::filtdata(yii::$app->request->get('pid',''));  //选择的pid
        //分页
        $curPage = Yii:: $app->request->get( 'page',1); 
        $pageSize = 20;//yii::$app->params['pagenum'];
        //搜索
        $uniqueid = Helper::filtdata(Yii:: $app->request->get('uniqueid','')); 
        $value = Helper::filtdata(Yii:: $app->request->get('keyword','')); 
        $search = ($value)?['like','username',$value]: '';
        $search1 = ($uniqueid)?['like','Unique_ID',$uniqueid]: '';
        $query = (new \yii\db\Query())
        ->select('ou.id as id,ou.pid,username,ou.phone,sex,gp.state,gp.pname as name,ou.currencynum,ou.head_url,ou.province,ou.city,ou.createtime,compname,vip,Unique_ID')
        ->from('g_user AS ou')
        ->leftJoin('g_plateform AS gp','gp.id = ou.pid')
        ->leftJoin('g_company AS gc','gc.id = gp.cid')
        ->where($where)
        ->groupBy('openid')
        ->orderBy('ou.id desc');
        $pid1 && ($query = $query->andWhere(["gp.id"=>$pid1]));
        $search1 && ($query = $query->andWhere($search1));
       
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        $data['data'] =  ($data['data'])?$data['data']->all():'';
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        yii::$app->session['data'] = $data['data'];
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/user/index.html';
        ($manage_pid)?$p_where['id'] = $manage_pid:$p_where='';
        $plate = Plateform::find()->where($p_where)->asArray()->orderBy('sort desc')->ALL();
        $managemodel = yii::$app->session['tomodel'];
        return $this->render('index', [
             'data' => $data,
             'pages' => $pages,
             'value' => $value,
             'uniqueid' => $uniqueid,
        	 'plate' => $plate,
        	 'pid' => $pid1,
        	'managemodel'=>$managemodel,
        ]);
    }
    
    
    /**
     * 进入申请页.
     * @return mixed
     */
    public function actionApplyindex(){
    	$manage_pid = yii::$app->session->get('pid'); //权限管理
    	($manage_pid)?$where['pid'] = $manage_pid:$where='';
    	$pid1 = Helper::filtdata(yii::$app->request->get('pid',''));  //选择的pid
        //分页
        $curPage = Yii:: $app->request->get( 'page',1); 
        $pageSize = yii::$app->params['pagenum'];
        //搜索
        $uniqueid = Helper::filtdata(Yii:: $app->request->get('uniqueid','')); 
        $value = Helper::filtdata(Yii:: $app->request->get('keyword','')); 
        $search = ($value)?['like','username',$value]: '';
        //$search1 = ($uniqueid)?['like','Unique_ID',$uniqueid]: '';
        if($pid1 || $uniqueid || $value){
        	$query = (new \yii\db\Query())
			        ->select('ou.id as id,ou.pid,username,ou.phone,sex,gp.state,gp.pname as name,ou.currencynum,ou.head_url,ou.province,ou.city,ou.createtime,compname,vip,Unique_ID')
			        ->from('g_user AS ou')
			        ->leftJoin('g_plateform AS gp','gp.id = ou.pid')
			        ->leftJoin('g_company AS gc','gc.id = gp.cid')
			        ->where($where)
			        ->groupBy('openid')
			        ->orderBy('ou.id desc');
			        $pid1 && ($query = $query->andWhere(["gp.id"=>$pid1]));
			        $uniqueid && ($query = $query->andWhere(['Unique_ID'=>$uniqueid]));
			       
			        $data = Helper::getPages($query,$curPage,$pageSize,$search);
			        $data['data'] =  ($data['data'])?$data['data']->all():'';
			        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        }else{
        	$pages ='';
        	$data['data'] = '';
        }
        
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/user/applyindex.html';
        ($manage_pid)?$p_where['id'] = $manage_pid:$p_where='';
        $plate = Plateform::find()->where($p_where)->asArray()->ALL();
        $managemodel = yii::$app->session['tomodel'];
        return $this->render('applyindex', [
             'data' => $data,
             'pages' => $pages,
             'value' => $value,
             'uniqueid' => $uniqueid,
        	 'plate' => $plate,
        	 'pid' => $pid1,
        	'managemodel'=>$managemodel,
        ]);
    }
    
    /**
     * 粉丝订单详情
     */
    public function actionTouserorder(){
    	if(isset($_GET['id'])){
    		$uid = Helper::filtdata($_GET['id'],'INT');
    		$username = Yii:: $app->request->get( 'name','');
    		if(!$username){
    			$username = yii::$app->session['userordername'];
    		}
    		if(!$uid){
    			echo '非法访问';exit;
    		}
    		//分页
    		$curPage = Yii:: $app->request->get( 'page',1);
    		$pid = yii::$app->session->get('pid');
    		$pageSize = yii::$app->params['pagenum'];
    		$pid = yii::$app->request->get('pid','');
    		//平台
    		$user = yii::$app->session['tomodel'];
    		$where = ($user->role==-1)?'':['cid'=>$user->id];
    		$plate = Plateform::find()->where($where)->andWhere(['state'=>1])->select(['id','pname'])->all();
    		//搜索
    		$keyword = Yii:: $app->request->get('keyword','');
    		$value = Yii:: $app->request->get('value','');
    		$value = ($keyword=='username')?base64_encode($value):$value;
    		$search = ($value)?['like',$keyword,$value]: '';
    		$start_time = Yii:: $app->request->get('starttime','');
    		$end_time = Yii:: $app->request->get('endtime','');
    		$starttime = $start_time?strtotime($start_time):strtotime(date('1970-01-01'));
    		$endtime = $end_time?strtotime($end_time)+23*60*60:time();
    		$order = Order::find()->where(['uid'=>$uid,'state'=>2])->select(['count(id) as allnum,sum(price*num) as allprice'])->asArray()->one();
    		$query = (new \yii\db\Query())
    		->select("go.id as id,propname,gg.name,sum(go.price) as price, sum(go.num) as num ,go.createtime,gp.pname,go.orderID")
    		->from('g_order AS go')
    		->leftJoin('g_game AS gg','gg.id = go.gid and go.state=2')
    		->leftJoin('g_plateform AS gp','gp.id = go.pid')
    		->where("go.createtime between $starttime and $endtime")
    		->andWhere(["go.uid"=>$uid,'go.state'=>2])
    		->groupBy(['FROM_UNIXTIME(go.createtime,"%Y-%m-%d")']); 
    		$pid && ($query = $query->andWhere(["gp.id"=>$pid]));
    		$data = Helper::getPages($query,$curPage,$pageSize,$search);
    		$data['data'] =  ($data['data'])?$data['data']->all():'';
    		$pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
    		yii::$app->session['userordername'] = $username;
    		return $this->render('userorder',[
    				'order'=>$order,
    				'data'=>$data,
    				'starttime'=>$start_time,
    				'endtime'=>$end_time,
    				'pages'=>$pages,
    				'pid'=>$pid,
    				'plate'=>$plate,
    				'username'=>$username,
    				'uid'=>$uid,
    				]);
    
    	}
    }

    //重置密码为123456
    public function actionPwd(){
        if(yii::$app->request->isAjax&&isset($_POST['id'])){
            $id = Helper::filtdata(yii::$app->request->post('id'),'INT');
            $user = User::findOne($id);
            $user->password = md5('123456');
            if($user->save()){
                return 1;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

    /**
     * 查看用户资料
     * @return [type] [description]
     */
    public function actionDetail(){
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/user/index.html';
        if(isset($_GET['id'])){
            $id = Helper::filtdata(yii::$app->request->get('id'),'INT');
            $user = User::find()->where(['id'=>$id])->asArray()->One();
            if($user){
                return $this->render('detail',[
                    'model'=>$user,
                ]);
                exit;
            }
        }
        return $this->redirect('index.html');
    }

    /**
     * 排行榜
     * @return [type] [description]
     */
    public function actionRanking(){
        $manage_pid = yii::$app->session->get('pid'); //权限管理
        ($manage_pid)?$where['pid'] = $manage_pid:$where='';
        $pid1 = Helper::filtdata(yii::$app->request->get('pid',''));  //选择的pid
        //分页
        $curPage = Yii:: $app->request->get( 'page',1);
        $pageSize = yii::$app->params['pagenum'];
        //搜索
        $value = Helper::filtdata(Yii:: $app->request->get('value',''));    
        $Unique_ID = Helper::filtdata(Yii:: $app->request->get('UniqueID',''));  
        $search = ($value)?['like','username',$value]: '';
        $search1 = ($Unique_ID)?['like','Unique_ID',$Unique_ID]: '';
        $startdate = yii::$app->request->get('starttime','');
        $enddate =   yii::$app->request->get('endtime','');
        $starttime = $startdate?strtotime($startdate):strtotime('1970-01-01');
        $endtime = $enddate?strtotime($enddate)+86400:time();
        $time = "O.createtime between {$starttime} and {$endtime}";
        $query = (new \yii\db\Query())
        ->select('sum(price) AS Sprice,username,count(O.id) AS Cid,Unique_ID')
        ->from('g_order AS O')
        ->leftJoin('g_user AS U','U.id = O.uid')
        ->where('O.state=2')
        ->andWhere($time)
        ->groupBy('O.uid')
        ->orderBy('Sprice desc');
        $pid1 && ($query = $query->andWhere(["O.pid"=>$pid1]));
        $where && $query = $query->andWhere(['O.pid'=>$where]);
        $search1 && $query = $query->andWhere($search1);
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        $data['data'] =  ($data['data'])?$data['data']->all():'';
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/user/ranking.html';
        ($manage_pid)?$p_where['id'] = $manage_pid:$p_where='';
        $plate = Plateform::find()->where($p_where)->asArray()->orderBy('sort desc')->ALL();
        $managemodel = yii::$app->session['tomodel'];
        return $this->render('ranking',[
            'data' => $data,
            'pages' => $pages,
            'value' => $value,
            'Unique_ID' => $Unique_ID,
            'starttime'=>$startdate,
            'endtime'=>$enddate,
            'plate' => $plate,
            'pid' => $pid1,
            'managemodel'=>$managemodel,
        ]);
    }
    
    /**
     * 添加待审核的游戏币
     * @author lin
     */
    public function actionAddgamecurrency(){
    	if(yii::$app->request->isAjax&&isset($_POST['uid'])&&isset($_POST['currencyvalue'])){
    		$uid = Helper::filtdata($_POST['uid'],'INT');   //用户id
    		$currencyvalue = Helper::filtdata($_POST['currencyvalue'],'INT');   //币值
    		$remarkvalue = Helper::filtdata($_POST['remarkvalue']);  //备注
    		if($currencyvalue<=0){  //币值不小于或等于0
    			return json_encode([
    					'errorcode'=>1001,
    					'msg'=>'币值必须大于0，请重新设置！',
    					]);
    		}
    		if(!$uid || !$currencyvalue){
    			return json_encode([
    					'errorcode'=>1001,
    					'msg'=>'请求参数错误，请刷新再试！',
    					]);
    		}
    		$gamecurrency = new Gamecurrency();
    		$gamecurrency->uid = $uid;
    		$gamecurrency->currencynum = $currencyvalue;
    		$gamecurrency->state = 0;      //是否审核  0：未审核  1：已审核
    		$gamecurrency->remark = $remarkvalue;
    		$gamecurrency->createtime = time();
    		if($gamecurrency->save()){
    			return json_encode([
    					'errorcode'=>0,
    					'msg'=>'提交成功，请通知负责人审核',
    					]);
    		}else{
    			return json_encode([
    					'errorcode'=>1001,
    					'msg'=>'保存失败，请刷新重试',
    					]);
    		}
    		 
    	}else{
    		return json_encode([
    				'errorcode'=>1001,
    				'msg'=>'请求错误，请刷新再试！',
    				]);
    	}
    }
    
    /**
     * 添加待审核的返利额
     * @author lin
     */
    public function actionAddrebate(){
    	if(yii::$app->request->isAjax&&isset($_POST['uid'])&&isset($_POST['currencyvalue'])){
    		$uid = Helper::filtdata($_POST['uid'],'INT');   //用户id
    		$rebatenum = Helper::filtdata($_POST['currencyvalue'],'INT');  //返利额度
    		$remarkvalue = Helper::filtdata($_POST['remarkvalue']);  //备注
    		if($rebatenum<=0){  //币值不小于或等于0
    			return json_encode([
    					'errorcode'=>1001,
    					'msg'=>'币值必须大于0，请重新设置!',
    					]);
    		}
    		if(!$uid || !$rebatenum){
    			return json_encode([
    					'errorcode'=>1001,
    					'msg'=>'请求参数错误，请刷新再试！',
    					]);
    		}
    		$rebate = new Rebaterecord();
    		$rebate->uid = $uid;
    		$rebate->rebatenum = $rebatenum;
    		$rebate->state = 0;      //是否审核  0：未审核  1：已审核
    		$rebate->remark = $remarkvalue;
    		$rebate->createtime = time();
    		if($rebate->save()){
    			return json_encode([
    					'errorcode'=>0,
    					'msg'=>'提交成功，请通知负责人审核',
    					]);
    		}else{
    			return json_encode([
    					'errorcode'=>1001,
    					'msg'=>'保存失败，请刷新重试',
    					]);
    		}
    	}else{
    		return json_encode([
    				'errorcode'=>1001,
    				'msg'=>'请求错误，请刷新再试！',
    				]);
    	}
    }
    
    
  /**
     * 修改平台id
     */
    public function actionUpdatepid(){
    	if(isset($_POST['uid'])){
    		$uid = Helper::filtdata($_POST['uid']);
    		$starttime = strtotime(date('y-m-d'));
    		$endtime = $starttime+86400;
    		$ordertestobj = OrderTest::find()->where("createtime between $starttime and $endtime")->andWhere(['uid'=>$uid])->one();
    		if($ordertestobj){
    			return json_encode([
    					'errorcode'=>1001,
    					'msg'=>'该用户今日无法修改',
    					]);
    		}
    		$connection = Yii::$app->db->beginTransaction();//开启事务
    		$user = User::findOne(['id'=>$uid]);
    		if(!$user){
    			return json_encode([
					'errorcode'=>1001,
    					'msg'=>'用户不存在'
    			]);
    		}
    	    $user->pid= 14; 
    		if($user->save()){
    			$order = Order::find()->where(['uid'=>$uid])->andWhere("createtime between $starttime and $endtime")->all();
    			$orderres = true;
    			if($order){
    				foreach ($order as $od){
    					$orderobj = Order::findOne(['id'=>$od['id']]);
    					if($orderobj){
    						$orderobj->pid = 14;
    						$res = $orderobj->save();
    						if(!$res){
    							$orderres = false;
    						}
    					}
    				}
    			}
    			if($orderres){
    				$connection->commit();//事物提交
    				return  json_encode([
    						'errorcode'=>0,
    						'msg'=>'修改成功',
    						]);
    			}else{
    				$connection->rollBack();//事物回滚
    				return  json_encode([
    						'errorcode'=>0,
    						'msg'=>'修改失败',
    						]);
    			}
    			
    		}else{
    			$connection->rollBack();//事物回滚
    			return  json_encode([
    					'errorcode'=>1001,
    					'msg'=>'保存失败',
    					]);
    		}
    	}else{
    		return  json_encode([
					    'errorcode'=>1001,
    				    'msg'=>'参数错误',
    				]);
    	}
    }
    
    /**
     * 用户充值排行榜导出
     */
    public function actionRankingoutput(){
    	//分页
    	$curPage = Yii:: $app->request->get( 'page',1);
    	$pageSize = yii::$app->params['pagenum'];
    	//搜索
    	$pid = Helper::filtdata(yii::$app->request->get('pid',''));  //选择的pid
    	$value = Helper::filtdata(Yii:: $app->request->get('value',''));
    	$Unique_ID = Helper::filtdata(Yii:: $app->request->get('Unique_ID',''));
    	$startdate = yii::$app->request->get('starttime','');
    	$enddate =   yii::$app->request->get('endtime','');
    	$starttime = $startdate?strtotime($startdate):strtotime('1970-01-01');
    	$endtime = $enddate?strtotime($enddate)+86400:time();
    	$query = (new \yii\db\Query())
    	->select('sum(price) AS Sprice,username,count(O.id) AS Cid,Unique_ID')
    	->from('g_order AS O')
    	->leftJoin('g_user AS U','U.id = O.uid')
    	->where('O.state=2')
    	->andWhere("O.createtime between $starttime and $endtime")
    	->groupBy('O.uid')
    	->orderBy('Sprice desc')
    	->limit(100);
    	$pid && $query = $query->andWhere(["O.pid"=>$pid]);
    	$value && $query = $query->andWhere(['like','username',$value]);
    	$Unique_ID && $query = $query->andWhere(['like','Unique_ID',$Unique_ID]);
    
    	$data = ($query)?$query->all():'';
    	if(!$data){
    		return '没有数据需要导出';
    	}
    	 
    	$header = ['编号','用户ID','用户名称','总订单数','总付款金额'];
    	foreach ($data as $k => $v) {
    		$arr[$k]['v'] = $k+1;
    		$arr[$k]['Unique_ID'] = $v['Unique_ID'];
    		$arr[$k]['username'] = ( $v['username'] && strpos($v['username'],'=') === 0 )? "'".$v['username']:$v['username']; //过滤名字"="字符
    		$arr[$k]['Cid'] = $v['Cid'].' ';
    		$arr[$k]['Sprice'] = '￥'.$v['Sprice'];
    	}
    	$time = date('Y-m-d',$starttime).' - '.date('Y-m-d',$endtime-86400);
    	Phpexcelr::exportData($arr,$header,$time."_排行榜导出",$time."_排行榜导出");
    	exit;//阻止跳转，一定要写，不写会跳转
    }
    
}
