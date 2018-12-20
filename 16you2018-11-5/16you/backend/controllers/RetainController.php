<?php

namespace backend\controllers;

use Yii;
use backend\controllers\BaseController;
use yii\data\Pagination;
use common\common\Helper;
use common\models\Retain;
use common\models\Cretain;
use common\models\Count;
use common\models\Playgameuser;
use common\models\Plateform;
use common\models\Game;

/**
 * 留存表
 */
class RetainController extends BaseController{

	//列表
	public function actionIndex(){
		$manage_pid = yii::$app->session->get('pid'); //权限管理
		if($manage_pid){//平台管理则或者平台商
			$p_where = ['id'=>$manage_pid];
		}else{//超级管理员
			$p_where = '';
		}
		$plate = Plateform::find()->where($p_where)->andWhere(['state'=>1])->select(['id','pname'])->orderBy('sort desc')->all();  //查找所有平台
		$pid = yii::$app->request->get('pid',''); // 查询平台
		// if(!$pid){ //没有查询时，则从数据库查找一条
		// 	$pid = ($plate)? $plate['0']['id'] :'-1';
		// }
		$selectpid = ($pid)?['pid'=>$pid]:'';
		$start_date = yii::$app->request->get('starttime','');
		$end_date =   yii::$app->request->get('endtime','');
		$starttime = $start_date?strtotime($start_date):strtotime(date('1970-01-01'));
		$endtime = $end_date?strtotime($end_date):time();
		$curPage = Yii:: $app->request->get( 'page',1);
    	$pageSize = 100;
		$time = strtotime(date('Y-m-d',strtotime('-6 days')));
		$date = strtotime(date('Y-m-d'));
		if($curPage==1){
			$time1 = ($starttime<$time)?$time:$starttime;
			//查出六天内该平台的统计数据
			$newarrdata1 = Count::find()
			->where(['pid'=>$pid])
			->andWhere("count_time between $time1 and $endtime")
			->select('sum(new_user) as new_user,count_time,sum(play_user) as play_user')
			->groupBy('pid,count_time')
			->orderBy('count_time desc')
			->asArray()
			->all();
			if($newarrdata1){
				//查出六天内该平台下的用户
				$play = Playgameuser::find()
				->where(['state'=>2,'pid'=>$pid])
				->andWhere("createtime between $time1 and $endtime")
				->groupBy('createtime,first_time')
				->select('count(distinct uid) as num1,first_time,createtime')
				->orderBy('first_time desc')//以某一天的数据分组
				->asArray()
				->all();
				foreach ($newarrdata1 as $k1 => $v1) {
					$newarrdata1[$k1]['count_time'] = date('Y-m-d',$v1['count_time']);
					$newarrdata1[$k1]['retain'] = ['2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0];
					if($play){
						foreach ($play as $k => $v) {
							if(($v1['count_time']==$v['first_time'])&&($v['first_time']!=$v['createtime'])){//统计的时间（count）等于开始统计的时间（first）
								$num = ($v['createtime']-$v['first_time'])/(3600*24)+1;//第几日
								if($num>=2){
									$newarrdata1[$k1]['retain'][$num] = $v['num1'].'（'.($v1['new_user']?round(($v['num1']/($v1['new_user']))*100,2):0).'）';
								}
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
		$retain = new Cretain();
		$countdata = 0;//统计总共的数量
		$startdata =  $pageSize * ($curPage-1); //当前页面的显示数量
		$newarrdata = array_slice($newarrdata1,$startdata,$pageSize); //截取当前页面的数据
		if($newarrcount>=($pageSize*$curPage)){ //数据数大于显示数 则无需查数据库
			$data['data'] = $newarrdata;
			$countdata = $retain->find()->count();
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
			$countarr = $retain->find()->where($selectpid)->andWhere("count_time between $starttime and $endtime")->groupBy('count_time')->orderBy('count_time desc');
			$data['count'] = $countarr->count();
			$curPage1 = (ceil($data['count']/$pagenum)<$curPage)?ceil($data['count']/$pagenum):$curPage;//第几页
	        //数据
	        $data['data'] = $countarr->offset(($curPage1-1)*$pagenum)->limit($pagenum)->asArray()->all();
			if($data['data']){
				foreach ($data['data'] as $kq => $vq) {
					$new_count = count($newarrdata);
					$newarrdata[$new_count]['new_user'] = $vq['new_user'];
					$newarrdata[$new_count]['count_time'] = date('Y-m-d',$data['data'][$kq]['count_time']);
					$newarrdata[$new_count]['play_user'] = $vq['play_user'];
					$newarrdata[$new_count]['retain'] = ['2'=>$vq['second'],'3'=>$vq['third'],'4'=>$vq['fourth'],'5'=>$vq['fifth'],'6'=>$vq['sixth'],'7'=>$vq['seventh']];
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
		yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/retain/index.html';
		return $this->render('index',[
			'data'=>$data,
			'starttime'=>$start_date,
			'endtime'=>$end_date,
			'pages'=>$pages,
			'pid'=>$pid,
			'plate'=>$plate,
		]);
	}

	//详情留存列表
	public function actionGindex(){
		$manage_pid = yii::$app->session->get('pid'); //权限管理
		if($manage_pid){//平台管理则或者平台商
			$p_where = ['id'=>$manage_pid];
		}else{//超级管理员
			$p_where = '';
		}
		$plate = Plateform::find()->where($p_where)->andWhere(['state'=>1])->select(['id','pname'])->orderBy('sort desc')->all();  //查找所有平台
		$pid = yii::$app->request->get('pid',''); // 查询平台
		// if(!$pid){ //没有查询时，则从数据库查找一条
		// 	$pid = ($plate)? $plate['0']['id'] :'-1';
		// }
		$gid = yii::$app->request->get('gid','');
		$selectpid = ($pid)?['pid'=>$pid]:'';
		$selectgid = ($gid)?['gid'=>$gid]:'';
		$start_date = yii::$app->request->get('starttime','');
		$end_date =   yii::$app->request->get('endtime','');
		$starttime = $start_date?strtotime($start_date):strtotime(date('1970-01-01'));
		$endtime = $end_date?strtotime($end_date):time();
		$curPage = Yii:: $app->request->get( 'page',1);
    	$pageSize = 100;
		$time = strtotime(date('Y-m-d',strtotime('-6 days')));
		$date = strtotime(date('Y-m-d'));
		if($curPage==1){
			//查出六天内该平台的统计数据
			$newarrdata1 = Count::find()
			->where(['>=','count_time',$time])
			->andwhere(['pid'=>$pid])
			->andWhere($selectgid)
			->andWhere("count_time between $starttime and $endtime")
			->select('sum(new_user) as new_user,count_time,sum(play_user) as play_user,gid,gamename')
			->groupBy('gid,pid,count_time')
			->orderBy('count_time desc')
			->asArray()
			->all();
			if($newarrdata1){
				//查出六天内该平台下的用户
				$play = (new \yii\db\Query())
				->from('g_playgameuser as p')
				->leftJoin('g_game as g','p.gid=g.id')
				->where(['p.type'=>2,'pid'=>$pid])
				->andWhere($selectgid)
				->andWhere(['>=','p.createtime',$time])
				->groupBy('p.createtime,first_playtime,gid')
				->select('count(p.uid) as num1,p.first_playtime,p.createtime,p.gid,g.name as gamename')
				->orderBy('first_playtime desc')
				->all();
				foreach ($newarrdata1 as $k1 => $v1) {
					$newarrdata1[$k1]['count_time'] = date('Y-m-d',$v1['count_time']);
					$newarrdata1[$k1]['retain'] = ['2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0];
					if($play){
						foreach ($play as $k => $v) {
							if(($v1['count_time']==$v['first_playtime'])&&($v['first_playtime']!=$v['createtime'])&&($v['gid']==$v1['gid'])){
								$num = ($v['createtime']-$v['first_playtime'])/(3600*24)+1;//第几日
								if($num>=2){
									$newarrdata1[$k1]['retain'][$num] = $v['num1'].'（'.($v1['new_user']?round(($v['num1']*100)/($v1['new_user']),2):0).'）';
								}
							}
						}
					}
				}
			}
			$dcount = count($newarrdata1);
			yii::$app->session['datacounte'] = $dcount;
			yii::$app->session['pagenume'] = ($dcount<=$pageSize)?1:(($dcount%$pageSize==0)?floor($dcount/$pageSize):floor($dcount/$pageSize)+1); //需要补数据的页数
			yii::$app->session['nume'] = yii::$app->session['pagenume']*$pageSize-$dcount; //补数
			yii::$app->session['newarrdatae'] = $newarrdata1;
		}else{
			$newarrdata1 = yii::$app->session['newarrdatae'];//今天的数据
		}
		$startdata =  $pageSize * ($curPage-1); //当前页面的显示数量
		$newarrdata = array_slice($newarrdata1,$startdata,$pageSize); //截取当前页面的数据
		$newarrcount = count($newarrdata);  //当前页面今天的数量
		$retain = new Retain();
		$countdata = 0;//统计总共的数量
		if(count($newarrdata1)>=($pageSize*$curPage)){ //数据数大于显示数 则无需查数据库
			$data['curPage'] = $curPage;
			//每页显示条数
			$data['pageSize'] = $pageSize; 
			//起始页
			$data['data'] = $newarrdata;
			$countdata = $retain->find()->where($selectpid)->andWhere($selectgid)->count();
		}else{  //数据小于显示数 则从数据库读取补齐
			$pagenum = $pageSize - $newarrcount;  //需要查询数
			if(yii::$app->session['pagenume']==$curPage){//查询需要补数页需要的数据
				$num  = 0;
			}else{
				$num = (yii::$app->session['nume'])?yii::$app->session['nume']:0;//补数
			}
			$countarr = $retain->find()->where($selectpid)->andWhere("count_time between $starttime and $endtime")->andWhere($selectgid)->orderBy('count_time desc');
			$data = Helper::getPages($countarr,$curPage,$pagenum,$search = null,$num,false);
			if($data['data']){
				$data['data'] = $data['data']->asArray()->all();
				foreach ($data['data'] as $kq=>$vq) {
					$new_count = count($newarrdata);
					$newarrdata[$new_count]['new_user'] = $vq['new_user'];
					$newarrdata[$new_count]['gamename'] = $vq['gamename'];
					$newarrdata[$new_count]['count_time'] = date('Y-m-d',$data['data'][$kq]['count_time']);
					$newarrdata[$new_count]['play_user'] = $vq['play_user'];
					$newarrdata[$new_count]['retain'] = ['2'=>$vq['second'],'3'=>$vq['third'],'4'=>$vq['fourth'],'5'=>$vq['fifth'],'6'=>$vq['sixth'],'7'=>$vq['seventh']];
				}
			}
			$data['data'] = $newarrdata;
			$countdata = $data['count'];
		}
		$datacount = (yii::$app->session['datacounte'])?yii::$app->session['datacounte']+$countdata:$countdata;
		$data['count'] = $datacount;
		//末页
		$data['end'] = (ceil($data['count']/$pageSize) == $curPage)?$data['count']:$curPage*$pageSize;
		//起始页
		$data['start'] = ($curPage-1)*$pageSize+1;
		$pages = new Pagination([ 'totalCount' =>$datacount, 'pageSize' => $pageSize]); 

		$gamearr = Game::find()->orderBy('sort desc')->asArray()->limit('1000')->select('id,name,descript,label,head_img,game_url,type')->all();//游戏

		//菜单定位
		unset(yii::$app->session['localfirsturl']);
		yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/retain/gindex.html';
		
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