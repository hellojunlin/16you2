<?php

namespace backend\controllers;

use Yii;
use backend\controllers\BaseController;
use yii\data\Pagination;
use common\common\Helper;
use common\models\Playgameuser;
use common\models\Order;
use common\models\Recycle;
use common\models\Crecycle;
use common\models\Plateform;
use common\models\Game;
use common\models\Count;

/**
 * 回收统计表
 */
class RecycleController extends BaseController{

	//列表
	public function actionIndex(){
		$managemodel = yii::$app->session['tomodel'];
		$manage_pid = yii::$app->session->get('pid'); //权限管理
		if($manage_pid){//平台管理则或者平台商
			$p_where = ['id'=>$manage_pid];
		}else{//超级管理员
			$p_where = '';
		}
		$plate = Plateform::find()->where($p_where)->andWhere(['state'=>1])->select(['id','pname'])->orderBy('sort desc')->all();  //查找所有平台
		$pid = Helper::filtdata(yii::$app->request->get('pid',''),'INT'); // 查询平台
		if(!$pid){ //没有查询时，则从数据库查找一条
			$pid = ($plate)? $plate['0']['id'] :'-1';
		}
		$selectpid = ($pid)?['pid'=>$pid]:'';
		$start_date = yii::$app->request->get('starttime','');
		$end_date =   yii::$app->request->get('endtime','');
		$starttime = $start_date?strtotime($start_date):strtotime(date('1970-01-01'));
		$endtime = $end_date?strtotime($end_date):time();
		$curPage = Yii:: $app->request->get( 'page',1);
    	$pageSize = 100;
		$time = strtotime(date('Y-m-d',strtotime('-6 days')));
		$date = strtotime(date('Y-m-d'));
		if($curPage==1){//查询日期属于今天
			$time1 = ($starttime<$time)?$time:$starttime;
			//查出六天内该平台的统计数据（激活数、统计的时间）
			$newarrdata1 = Count::find()
			->andwhere(['pid'=>$pid])
			->andWhere("count_time between $time1 and $endtime")
			->select('count_time,sum(play_user) as play_user')
			->groupBy('pid,count_time')
			->orderBy('count_time desc')
			->asArray()
			->all();
			if(($starttime == $date || $endtime == $date)||($starttime==strtotime(date('1970-01-01'))&& $endtime==time())){ //查询日期属于今天
				//查出今天的激活数
				$playuserarr = Playgameuser::find()
						->where(['createtime'=>$date,'pid'=>$pid])
						->groupBy('pid,uid,createtime')
						->count();
				if($playuserarr>0){
					$playuserarr1['play_user'] = $playuserarr;
					$playuserarr1['count_time'] = $date;
					array_unshift($newarrdata1,$playuserarr1);
				}
			}
			if ($newarrdata1) {
				$endtime = $endtime+3600*24;
				$order = \Yii::$app->db->createCommand("SELECT uid,first_time,price,FROM_UNIXTIME(createtime,'%Y-%m-%d') AS daytime,createtime FROM g_order where state=2 and pid=:pid and createtime between $time1 and $endtime and first_time!='null' order By daytime",[':pid'=>$pid])->queryAll();//查出七天内该平台下付费的用户
				foreach ($newarrdata1 as $k1 => $v1) {
					$newarrdata1[$k1]['count_time'] = date('Y-m-d',$v1['count_time']);
					$newarrdata1[$k1]['recycle'] = ['1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0];
					$newarrdata1[$k1]['repay'] = ['1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0];
					if($order){
						$arrtest = array();
						foreach ($order as $k => $v) {
							$daytime = strtotime($v['daytime']);
							$num = intval(($daytime-$v['first_time'])/(3600*24))+1;//第几日
							!isset($arrtest[$num]) && $arrtest[$num] = array();
							if($v1['count_time']==$daytime){
								if(!in_array($v['uid'],$arrtest[$num])){
									$arrtest[$num][] = $v['uid'];
									$newarrdata1[$k1]['recycle']['1']++;
								}
								$newarrdata1[$k1]['repay']['1']+=$v['price'];
							}else{
								//统计的时间和首次付费的时间相等
								if($v1['count_time']==$v['first_time']){
									if(isset($newarrdata1[$k1]['recycle'][$num])){
										if(!in_array($v['uid'],$arrtest[$num])){
											$arrtest[$num][] = $v['uid'];
											$newarrdata1[$k1]['recycle'][$num]++;
										}
										$newarrdata1[$k1]['repay'][$num]+=$v['price'];
									}
								}
							}
						}
						$m_num = (strtotime(date('Y-m-d'))-$v1['count_time'])/(3600*24)+1;
						if($m_num>1){
							for($m=2;$m<=$m_num;$m++){
								$newarrdata1[$k1]['recycle'][$m] = $newarrdata1[$k1]['recycle'][$m]+$newarrdata1[$k1]['recycle'][$m-1];
								$newarrdata1[$k1]['repay'][$m] = $newarrdata1[$k1]['repay'][$m]+$newarrdata1[$k1]['repay'][$m-1];
							}
						}
					}
				}
			}
			$newarrcount = count($newarrdata1);
			yii::$app->session['datacount1'] = $newarrcount;
			yii::$app->session['pagenum1'] = ($newarrcount<=$pageSize)?1:(($newarrcount%$pageSize==0)?floor($newarrcount/$pageSize):floor($newarrcount/$pageSize)+1); //需要补数据的页数
			yii::$app->session['num1'] = yii::$app->session['pagenum1']*$pageSize-$newarrcount; //补数
			yii::$app->session['newarrdata1'] = $newarrdata1;
		}else{
			$newarrdata1 = yii::$app->session['newarrdata1'];
			$newarrcount = yii::$app->session['datacount1'];
		}
		$recycle = new Crecycle();
		$countdata = 0;//统计总共的数量
		$startdata =  $pageSize * ($curPage-1); //当前页面的显示数量
		$newarrdata = array_slice($newarrdata1,$startdata,$pageSize); //截取当前页面的数据
		if($newarrcount>=($pageSize*$curPage)){ //数据数大于显示数 则无需查数据库
			$data['data'] = $newarrdata;
			$countdata = $recycle->find()->count();
			$data['count'] = $countdata+$newarrcount;
			$data['end'] = (ceil($data['count']/$pageSize) == $curPage)?$data['count']:($curPage-1)*$pageSize+$pageSize;//末页
		}else{  //数据小于显示数 则从数据库读取补齐
			$new_count = count($newarrdata);
			$pagenum = $pageSize - $new_count;  //需要查询数
			if(yii::$app->session['pagenum1']==$curPage){//查询需要补数页需要的数据
				$num  = 0;
			}else{
				$num = (yii::$app->session['num1'])?yii::$app->session['num1']:0;//补数
			}
			$countarr = $recycle->find()->where($selectpid)->andWhere("count_time between $starttime and $endtime")->groupBy('count_time')->orderBy('count_time desc');
			$data['count'] = $countarr->count();
			$curPage1 = (ceil($data['count']/$pagenum)<$curPage)?ceil($data['count']/$pagenum):$curPage;//第几页
	        //数据
	        $data['data'] = $countarr->offset(($curPage1-1)*$pagenum)->limit($pagenum)->asArray()->all();
			if($data['data']){
				foreach ($data['data'] as $kq => $vq) {
					$new_count = count($newarrdata);
					$newarrdata[$new_count]['count_time'] = date('Y-m-d',$data['data'][$kq]['count_time']);
					$newarrdata[$new_count]['play_user'] = $vq['play_user'];
					$newarrdata[$new_count]['recycle'] = ['1'=>$vq['pay_user'],'2'=>$vq['second'],'3'=>$vq['third'],'4'=>$vq['fourth'],'5'=>$vq['fifth'],'6'=>$vq['sixth'],'7'=>$vq['seventh']];
					$newarrdata[$new_count]['repay'] = ['1'=>$vq['price'],'2'=>$vq['psecond'],'3'=>$vq['pthird'],'4'=>$vq['pfourth'],'5'=>$vq['pfifth'],'6'=>$vq['psixth'],'7'=>$vq['pseventh']];
				}
			}
			$data['data'] = $newarrdata;
			$data['count'] = yii::$app->session['datacount1']+$data['count'];
			$data['end'] = (ceil($data['count']/$pageSize) == $curPage)?$data['count']:($curPage-1)*$pageSize+$pageSize;
		}
		//起始页
		$data['start'] = ($curPage-1)*$pageSize+1;
		$pages = new Pagination([ 'totalCount'=>$data['count'], 'pageSize' => $pageSize]);

		//菜单定位
		unset(yii::$app->session['localfirsturl']);
		yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/recycle/index.html';
		return $this->render('index',[
			'data'=>$data,
			'starttime'=>$start_date,
			'endtime'=>$end_date,
			'pages'=>$pages,
			'pid'=>$pid,
			'plate'=>$plate,
		]);
	}

	//详情收回列表
	public function actionGindex(){
		$manage_pid = yii::$app->session->get('pid'); //权限管理
		if($manage_pid){//平台管理则或者平台商
			$p_where = ['id'=>$manage_pid];
		}else{//超级管理员
			$p_where = '';
		}
		$plate = Plateform::find()->where($p_where)->andWhere(['state'=>1])->select(['id','pname'])->orderBy('sort desc')->all();  //查找所有平台
		$pid = Helper::filtdata(yii::$app->request->get('pid',''),'INT'); // 查询平台
		if(!$pid){ //没有查询时，则从数据库查找一条
			$pid = ($plate)? $plate['0']['id'] :'-1';
		}
		$gid = Helper::filtdata(yii::$app->request->get('gid',''),'INT');
		$selectpid = ($gid)?['pid'=>$pid]:'';
		$selectgid = ($gid)?['gid'=>$gid]:'';
		$start_date = Helper::filtdata(yii::$app->request->get('starttime',''));
		$end_date =   Helper::filtdata(yii::$app->request->get('endtime',''));
		$starttime = $start_date?strtotime($start_date):strtotime(date('1970-01-01'));
		$endtime = $end_date?strtotime($end_date):time();
		$curPage = Yii:: $app->request->get( 'page',1);
    	$pageSize = 100;
		$time = strtotime(date('Y-m-d',strtotime('-6 days')));
		$date = strtotime(date('Y-m-d'));
		$where = $gid?"and gid=$gid":'';
		if($curPage==1){//查询日期属于今天
			$time1 = ($starttime<$time)?$time:$starttime;
			//查出六天内该平台的统计数据（激活数、统计的时间）
			$newarrdata1 = Count::find()
			->where(['pid'=>$pid])
			->andWhere("count_time between $time1 and $endtime")
			->andWhere($selectgid)
			->select('count_time,sum(play_user) as play_user,gid,gamename')
			->groupBy('gid,pid,count_time')
			->orderBy('count_time desc')
			->asArray()
			->all();
			if(($starttime == $date || $endtime == $date)||($starttime==strtotime(date('1970-01-01'))&& $endtime==time())){ //查询日期属于今天
				//今天游戏有效玩家记录数
				$playuserarr = (new \yii\db\Query())
					->select('gp.type,gp.createtime as count_time,gp.gid,g.name as gamename,count(distinct uid) as play_user')
					->from(' g_playgameuser as gp')
					->leftJoin('g_game as g','gp.gid=g.id')
					->where(['gp.createtime'=>$date,'gp.pid'=>$pid])
					->andWhere($selectgid)
					->groupBy('gp.gid,gp.pid,gp.createtime')
					->all();
				if($playuserarr){
					foreach ($playuserarr as $key => $value) {
						array_unshift($newarrdata1,$value);
					}
				}
			}
			if($newarrdata1){
				$endtime = $endtime+3600*24;
				$order = \Yii::$app->db->createCommand("SELECT price,uid,FROM_UNIXTIME(O.createtime,'%Y-%m-%d') AS daytime,gfirst_time,O.createtime, O.gid, G.name AS gamename FROM g_order O LEFT JOIN g_game G ON G.id = O.gid WHERE O.state = 2 and O.pid = :pid $where and O.createtime between $time1 and $endtime",[':pid'=>$pid])->queryAll();//查出七天内该平台每款游戏付费的用户
				foreach ($newarrdata1 as $k1 => $v1) {
					$newarrdata1[$k1]['count_time'] = date('Y-m-d',$v1['count_time']);
					$newarrdata1[$k1]['recycle'] = ['1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0];
					$newarrdata1[$k1]['repay'] = ['1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0];
					if($order){
						$arrtest = array();
						foreach ($order as $k => $v) {
							$daytime = strtotime($v['daytime']);
							$num = intval(($daytime-$v['gfirst_time'])/(3600*24))+1;//第几日
							if(isset($newarrdata1[$k1]['recycle'][$num])){
								!isset($arrtest[$num.'9999'.$v['gid']]) && $arrtest[$num.'9999'.$v['gid']] = array();
								if($v1['count_time']==$daytime&&($v['gid']==$v1['gid'])){
									if(!in_array($v['uid'],$arrtest[$num.'9999'.$v['gid']])){
										$arrtest[$num.'9999'.$v['gid']][] = $v['uid'];
										$newarrdata1[$k1]['recycle']['1']++;
									}
									$newarrdata1[$k1]['repay'][$num]+=$v['price'];
								}else{
									if(($v1['count_time']==$v['gfirst_time'])&&($v['gid']==$v1['gid'])){
										if(!in_array($v['uid'],$arrtest[$num.'9999'.$v['gid']])){
											$arrtest[$num.'9999'.$v['gid']][] = $v['uid'];
											$newarrdata1[$k1]['recycle'][$num]++;
										}
										$newarrdata1[$k1]['repay'][$num]+=$v['price'];
									}
								}
							}
						}
						$m_num = (strtotime(date('Y-m-d'))-$v1['count_time'])/(3600*24)+1;
						if($m_num>1){
							for($m=2;$m<=$m_num;$m++){
								$newarrdata1[$k1]['recycle'][$m] = $newarrdata1[$k1]['recycle'][$m]+$newarrdata1[$k1]['recycle'][$m-1];
								$newarrdata1[$k1]['repay'][$m] = $newarrdata1[$k1]['repay'][$m]+$newarrdata1[$k1]['repay'][$m-1];
							}
						}
					}
				}
			}
			$newarrcount = count($newarrdata1);
			yii::$app->session['datacount3'] = $newarrcount;
			yii::$app->session['pagenum3'] = ($newarrcount<=$pageSize)?1:(($newarrcount%$pageSize==0)?floor($newarrcount/$pageSize):floor($newarrcount/$pageSize)+1); //需要补数据的页数
			yii::$app->session['num3'] = yii::$app->session['pagenum3']*$pageSize-$newarrcount; //补数
			yii::$app->session['newarrdata3'] = $newarrdata1;
		}else{
			$newarrdata1 = yii::$app->session['newarrdata3'];
			$newarrcount = yii::$app->session['datacount3'];
		}
		$retain = new Recycle();
		$countdata = 0;//统计总共的数量
		$startdata =  $pageSize * ($curPage-1); //当前页面的显示数量
		$newarrdata = array_slice($newarrdata1,$startdata,$pageSize); //截取当前页面的数据
		if($newarrcount>=($pageSize*$curPage)){ //数据数大于显示数 则无需查数据库
			$data['curPage'] = $curPage;
			//每页显示条数
			$data['pageSize'] = $pageSize; 
			//起始页
			$data['data'] = $newarrdata;
			$countdata = $retain->find()->where($selectpid)->andWhere($selectgid)->count();
		}else{  //数据小于显示数 则从数据库读取补齐
			$pagenum = $pageSize - $newarrcount;  //需要查询数
			if(yii::$app->session['pagenum3']==$curPage){//查询需要补数页需要的数据
				$num  = 0;
			}else{
				$num = (yii::$app->session['num3'])?yii::$app->session['num3']:0;//补数
			}
			$countarr = $retain->find()->where($selectpid)->andWhere($selectgid)->andWhere("count_time between $starttime and $endtime")->orderBy('count_time desc');
			$data = Helper::getPages($countarr,$curPage,$pagenum,$search = null,$num,false);
			if($data['data']){
				$data['data'] = $data['data']->asArray()->all();
				foreach ($data['data'] as $kq => $vq) {
					$new_count = count($newarrdata);
					$newarrdata[$new_count]['new_user'] = $vq['new_user'];
					$newarrdata[$new_count]['gamename'] = $vq['gamename'];
					$newarrdata[$new_count]['count_time'] = date('Y-m-d',$data['data'][$kq]['count_time']);
					$newarrdata[$new_count]['play_user'] = $vq['play_user'];
					$newarrdata[$new_count]['recycle'] = ['1'=>$vq['pay_user'],'2'=>$vq['second'],'3'=>$vq['third'],'4'=>$vq['fourth'],'5'=>$vq['fifth'],'6'=>$vq['sixth'],'7'=>$vq['seventh']];
					$newarrdata[$new_count]['repay'] = ['1'=>$vq['price'],'2'=>$vq['psecond'],'3'=>$vq['pthird'],'4'=>$vq['pfourth'],'5'=>$vq['pfifth'],'6'=>$vq['psixth'],'7'=>$vq['pseventh']];
				}
			}
			$data['data'] = $newarrdata;
			$countdata = $data['count'];
		}
		$datacount = (yii::$app->session['datacount3'])?yii::$app->session['datacount3']+$countdata:$countdata;
		$data['count'] = $datacount;
		//末页
		$data['end'] = (ceil($data['count']/$pageSize) == $curPage)?$data['count']:$curPage*$pageSize;
		//起始页
		$data['start'] = ($curPage-1)*$pageSize+1;
		$pages = new Pagination([ 'totalCount' =>$datacount, 'pageSize' => $pageSize]); 

		$gamearr = Game::find()->orderBy('sort desc')->asArray()->limit('1000')->select('id,name,descript,label,head_img,game_url,type')->all();//游戏

		//菜单定位
		unset(yii::$app->session['localfirsturl']);
		yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/recycle/gindex.html';
		
		$gname = Helper::filtdata(Yii:: $app->request->get( 'gname'));
		return $this->render('gindex',[
			'data'=>$data,
			'starttime'=>$start_date,
			'endtime'=>$end_date,
			'pages'=>$pages,
			'gid'=>$gid,
			'pid'=>$pid,
			'plate'=>$plate,
			'game'=>$gamearr,
			'gname'=>$gname,
		]);
	}
}