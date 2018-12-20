<?php

namespace backend\controllers;

use Yii;
use backend\controllers\BaseController;
use yii\data\Pagination;
use common\common\Helper;
use common\models\Retain;
use common\models\Count;
use common\models\Playgameuser;
use common\models\Plateform;
use common\models\Game;
use common\models\Continuorder;
use common\models\Decontinuorder;

/**
 * 持续付费 汇总统计
 */
class ContinueorderController extends BaseController{
	/**
	 * 持续付费汇总统计
	 * @return Ambigous <string, string>
	 */
	public function actionIndex(){
		$manage_pid = yii::$app->session->get('pid'); //权限管理
		if($manage_pid){//平台管理则或者平台商
			$p_where = ['id'=>$manage_pid];
		}else{//超级管理员
			$p_where = '';
		}
		$plate = Plateform::find()->where($p_where)->andWhere(['state'=>1])->select(['id','pname'])->orderBy('sort desc')->all();  //查找所有平台
		$pid = yii::$app->request->get('pid',''); // 查询平台
		if(!$pid){ //没有查询时，则从数据库查找一条
			$pid = ($plate)? $plate['0']['id'] :'-1';
		}
		//分页
		$curPage = Yii:: $app->request->get( 'page',1);
		$pageSize = yii::$app->params['pagenum'];
		//搜索的条件
		$selectpid = ($pid)?['pid'=>$pid]:'';  //搜索条件  选择的平台id
		$gid = yii::$app->request->get('gid','');  //搜索条件 选择的游戏id
		$startdate = yii::$app->request->get('starttime','');  //搜索条件 开始时间
		$enddate =   yii::$app->request->get('endtime','');    //搜索条件  结束时间
		$starttime = $startdate?strtotime($startdate):strtotime(date('1970-01-01'));  //开始的时间戳
		$endtime = $enddate?strtotime($enddate):time();   //结束的时间戳
		
		$time = strtotime(date('Y-m-d',strtotime('-6 days')));  //六天前的时间戳
		$start = strtotime(date('Y-m-d'));  //今天0点的时间戳
		$end = strtotime(date('Y-m-d 23:59:59'));  //今天24点的时间戳
		$orderuserarr = array(); //七天的订单数据
		$data = array(); //最终数据存放
		$where = $gid?"and gid=$gid":'';
		$newnum = 0;//今天的新增付费人数
		$pricedata = 0;//今天新增的付费金额
		if($curPage==1){//从数据库读取今天的记录
				//查出六天内该平台的统计数据
				$newarr = Count::find()
				    ->where(['>=','count_time',$time])
					->andwhere(['pid'=>$pid])
					->andWhere("count_time between $time and $end")
					->select('count_time,sum(play_user) as play_user')
					->groupBy('pid,count_time')
					->orderBy('count_time desc')
					->asArray()
					->all();
				//查出六天内该平台的付款成功的数据
				$orderuserarr = (new \yii\db\Query())
				->select('go.id,go.utype,go.gtype,go.first_time,go.gfirst_time,go.createtime,go.price,go.num')
				->from(' g_order as go')
				->where(['state'=>2,'go.pid'=>$pid])
				->andWhere("go.createtime between $time and $end")
				->groupBy('go.pid,go.uid,go.createtime')
				->all();
				if($orderuserarr){
						foreach ($orderuserarr as $k=>$o){
								$ocreatetime = strtotime(date('Y-m-d',$o['createtime']));
							if( $ocreatetime==$start ){
								$o['utype']==2 && $newnum = $newnum+1;  // 当日付款人数 新增用户
								$o['utype']==2 && $pricedata = $pricedata+($o['price']*$o['num']);  // 当日付费金额
							}
						}
				}
				if($newarr){
					foreach ($newarr as $k1 => $v1) {
						$date_time = date('Y-m-d',$v1['count_time']);
						$datatotime = strtotime($date_time);
						$newarr[$k1]['count_time'] = $date_time;   //日期
						$newarr[$k1]['new_user'] = 0;         //激活数
						$newarr[$k1]['pay_price'] = 0;        //当日支付金额
						$newarr[$k1]['retain'] = ['2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0];  //持续支付数
						$newarr[$k1]['price'] = ['2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0];   //持续支付金额
						if($orderuserarr){
							foreach ($orderuserarr as $key=>$ouser){
								$strtotime = strtotime(date('Y-m-d',$ouser['createtime']));  //付款时间去掉时分秒
								if(($v1['count_time']==$ouser['first_time'])){//前六天的统计时间
									$ouser['utype']==2 &&	$newarr[$k1]['new_user'] = $newarr[$k1]['new_user']+1;  // 当日付款人数 新增用户
									$ouser['utype']==2 &&   $newarr[$k1]['pay_price'] = $newarr[$k1]['pay_price'] +($ouser['price']*$ouser['num']);
									$num = ($strtotime-$ouser['first_time'])/(3600*24)+1;//第几日
									($num>1 && $num <8) && $newarr[$k1]['retain'][$num] = $newarr[$k1]['retain'][$num]+1 ;// $numdataarr[$ouser['createtime']]:0;
									($num>1 && $num <8) && $newarr[$k1]['price'][$num] = $newarr[$k1]['price'][$num]+($ouser['price']*$ouser['num']);   /// isset($pricedataarr[$orderuserarr[$k]['createtime']])?$pricedataarr[$orderuserarr[$k]['createtime']]:0;
								}
							}
						}
					}
				}
				if(($starttime == $start || $endtime == $start)||($starttime==strtotime(date('1970-01-01'))&& $endtime==time())){ //查询日期属于今天
					//今天游戏有效玩家记录数
					$playuserarr = (new \yii\db\Query())
					->select('gp.state,gp.createtime')
					->from(' g_playgameuser as gp')
					->where(['pid'=>$pid])
					->andWhere("gp.createtime between $start and $end")
					->groupBy('gp.pid,gp.uid,gp.createtime')
					->all();
					if($playuserarr){
						$playtime['count_time'] = date('Y-m-d',$start);
						$playtime['play_user'] = 0;
						$playtime['new_user'] = $newnum;
						$playtime['pay_price'] = $pricedata;
						$playtime['retain'] = ['2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0];
						$playtime['price'] = ['2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0];
						 foreach ($playuserarr as $pu){
							$playtime['play_user']++;
						} 
					}
				}
		    if(isset($playtime)){
				array_unshift($newarr,$playtime);
			} //合并数组 数组索引值从0递增
			 
			$dcount = count($newarr);
			yii::$app->session['datacount'] = $dcount; //今天数据数
			yii::$app->session['pagenum'] = ($dcount<=$pageSize)?1:(($dcount%$pageSize==0)?floor($dcount/$pageSize):floor($dcount/$pageSize)+1); //需要补数据的页数
			yii::$app->session['num'] = yii::$app->session['pagenum']*$pageSize-$dcount; //补数
			yii::$app->session['newarr'] = $newarr;
		}else{
			if(yii::$app->session['newarr']){
				$newarr = yii::$app->session['newarr'];
			}
		}
		$startdata =  $pageSize * ($curPage-1); //当前页面的显示数量
		$newarrdata = array_slice($newarr,$startdata,$pageSize); //截取当前页面的数据
		$newarrcount = count($newarrdata);  //当前页面今天的数量
		$continuorder = new Continuorder();
		$countdata = 0;//统计总共的数量
		if($newarrcount>=$pageSize){ //数据数大于显示数 则无需查数据库
			$data['curPage'] = $curPage;
			//每页显示条数
			$data['pageSize'] = $pageSize;
			//起始页
			//	$data['start'] = ($curPage-1)*$pageSize+1;
			$data['data'] = $newarrdata;
			$data['count'] = $newarrcount;
			//末页
			$data['end'] = (ceil($data['count']/$pageSize) == $curPage)?$data['count']:($curPage-1)*$pageSize+$pageSize;
			$countdata = $continuorder->find()->count();
		}else{  //数据小于显示数 则从数据库读取补齐
			$pagenum = $pageSize - $newarrcount;  //需要查询数
			if(yii::$app->session['pagenum']==$curPage){//查询需要补数页需要的数据
				$type = true;
				$num  = 0;
			}else{
				$type = false;
				$num = (yii::$app->session['num'])?yii::$app->session['num']:0;//补数
			}
			//$gidarray['gid'] = $gidarr;
			$countarr = $continuorder->find()->asArray()->where($selectpid)->andWhere("count_time between $starttime and $endtime")->orderBy('count_time desc') ;
			//$countarr = $gid?$countarr->andWhere(['gid'=>$gid]):$countarr;
			$data = Helper::getPages($countarr,$curPage,$pagenum,$search = null,$num,false);
			$data['data'] =  ($data['data'])?$data['data']->all():array();
			$newdata='';
			if($data['data']){
				foreach ($data['data'] as $kq => $vq) {
					$new_count = count($newarrdata);
					$new_data[$new_count]['new_user'] = $vq['pay_user'];
					$new_data[$new_count]['count_time'] = date('Y-m-d',$data['data'][$kq]['count_time']);
					$new_data[$new_count]['play_user'] = $vq['play_user'];
					$new_data[$new_count]['pay_price'] = $vq['pay_price'];
					$new_data[$new_count]['retain'] = ['2'=>$vq['second'],'3'=>$vq['third'],'4'=>$vq['fourth'],'5'=>$vq['fifth'],'6'=>$vq['sixth'],'7'=>$vq['seventh']];
					$new_data[$new_count]['price']= ['2'=>$vq['secondprice'],'3'=>$vq['thirdprice'],'4'=>$vq['fourthprice'],'5'=>$vq['fifthprice'],'6'=>$vq['sixthprice'],'7'=>$vq['seventhprice']];
					$newdata[] = $new_data;
				}
			}
		//print_r($newdata);exit;
			//查询之前的统计
			if($newdata){
				foreach ($newdata as $k=>$a){
					foreach ($a as $adata){
						array_push($newarr,$adata);
					}
				}
			}
			$countdata = $data['count'];
			$data['data'] = $newarr;
		}
		$datacount = (!yii::$app->session['datacount'])?0+$countdata:yii::$app->session['datacount']+$countdata;
		$data['count'] = $datacount;
		//末页
		$data['end'] = (ceil($data['count']/$pageSize) == $curPage)?$data['count']:($curPage-1)*$pageSize+$pageSize;
		//起始页
		$data['start'] = ($curPage-1)*$pageSize+1;
		$pages = new Pagination([ 'totalCount' =>$datacount, 'pageSize' => $pageSize]);
		$gamearr = Game::find()->orderBy('sort desc')->asArray()->limit('10000')->select('id,name,descript,label,head_img,game_url,type')->all();//游戏
		//菜单定位
		unset(yii::$app->session['localfirsturl']);
		yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/continueorder/index.html';
		
		return $this->render('continuecount',[
				'data'=>$data,
				'starttime'=>$startdate,
				'endtime'=>$enddate,
				'pages' => $pages,
				'game' => $gamearr,
				'gid' => $gid,
				'pid' => $pid,
				'plate' => $plate,
				]);
	}
	
	
	/**
	 * 持续付费详情统计
	 */
	public function actionDetaindex(){
			$manage_pid = yii::$app->session->get('pid'); //权限管理
			if($manage_pid){//平台管理则或者平台商
				$p_where = ['id'=>$manage_pid];
			}else{//超级管理员
				$p_where = '';
			}
			$plate = Plateform::find()->where($p_where)->andWhere(['state'=>1])->select(['id','pname'])->orderBy('sort desc')->all();  //查找所有平台
			$pid = yii::$app->request->get('pid',''); // 查询平台
			if(!$pid){ //没有查询时，则从数据库查找一条
				$pid = ($plate)? $plate['0']['id'] :'-1';
			}
			//分页
			$curPage = Yii:: $app->request->get('page',1);
			$pageSize = yii::$app->params['pagenum'];
			//搜索的条件
			$selectpid = ($pid)?['pid'=>$pid]:'';  //搜索条件  选择的平台id
			$gid = yii::$app->request->get('gid','');  //搜索条件 选择的游戏id
			$selectgid = ($gid)?['gid'=>$gid]:'';
			$startdate = yii::$app->request->get('starttime','');  //搜索条件 开始时间
			$enddate =   yii::$app->request->get('endtime','');    //搜索条件  结束时间
			$starttime = $startdate?strtotime($startdate):strtotime(date('1970-01-01'));  //开始的时间戳
			$endtime = $enddate?strtotime($enddate):time();   //结束的时间戳
			
			$time = strtotime(date('Y-m-d',strtotime('-6 days')));  //六天前的时间戳
			$start = strtotime(date('Y-m-d'));  //今天0点的时间戳
			$end = strtotime(date('Y-m-d 23:59:59'));  //今天24点的时间戳
			$orderuserarr = array(); //七天的订单数据
			$data = array(); //最终数据存放
			$where = $gid?"and gid=$gid":'';
			$resarr = array();  //最终结果存储
			if($curPage==1){//从数据库读取今天的记录
				//查出六天内该平台激活数的统计数据
				$newarr = Count::find()
				->where(['>=','count_time',$time])
				->andwhere(['pid'=>$pid])
				->andWhere($selectgid)
				->andWhere("count_time between $time and $end")
				->select('count_time,sum(play_user) as play_user,gid,gamename')
				->groupBy('pid,gid,count_time')
				->orderBy('count_time desc')
				->asArray()
				->all();
				if($newarr){
					//查出六天内该平台的付款成功的数据
					$orderuserarr = (new \yii\db\Query())
					->select('go.id,go.utype,go.gtype,go.first_time,go.gfirst_time,go.createtime ,go.price,go.gid,g.name,go.num')
					->from(' g_order as go')
					->leftJoin('g_game as g','go.gid=g.id')
					->where(['go.state'=>2,'go.pid'=>$pid])
					->andWhere("go.createtime between $time and $end")
					->andWhere($selectgid)
					->groupBy('go.pid,go.uid,go.createtime')
					->orderBy('go.first_time desc')
					->all();
					if($orderuserarr){
						 foreach ($orderuserarr as $k=>$ou){
							$orderuserarr[$k]['createtime'] =  strtotime(date('Y-m-d',$ou['createtime']));  //付款时间去掉时分秒
						} 
						 foreach ($newarr as $k1 => $v1) {
								$date_time = date('Y-m-d',$v1['count_time']);
								$datatotime = strtotime($date_time);
								$newarr[$k1]['count_time'] = isset($newarr[$k1]['count_time'])?$newarr[$k1]['count_time']:$v1['count_time'];   //日期
								$newarr[$k1]['new_user'] = isset($newarr[$k1]['new_user'])?$newarr[$k1]['new_user']:0;         //激活数
								$newarr[$k1]['pay_price'] = isset($newarr[$k1]['pay_price'])?$newarr[$k1]['pay_price']: 0;        //当日支付金额
								$newarr[$k1]['retain'] = isset($newarr[$k1]['retain'] )?$newarr[$k1]['retain'] :['2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0];  //持续支付数
								$newarr[$k1]['price'] = isset($newarr[$k1]['price'])?$newarr[$k1]['price']:['2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0];   //持续支付金额
								
								if($orderuserarr){
								  foreach ($orderuserarr as $key=>$ouser){
									if(($v1['count_time']==$ouser['first_time'])){//前六天的统计时间
										($ouser['gtype']==2 && $v1['gid']==$ouser['gid']) &&  $newarr[$k1]['new_user'] = isset($newarr[$k1]['new_user'])?$newarr[$k1]['new_user']+1:1;  // 当日付款人数 新增用户
										($ouser['gtype']==2 && $v1['gid']==$ouser['gid']) &&  $newarr[$k1]['pay_price'] = isset($newarr[$k1]['pay_price'])?$newarr[$k1]['pay_price'] +($ouser['price']*$ouser['num']):$ouser['price']*$ouser['num'];
										$num = ($ouser['createtime']-$ouser['first_time'])/(3600*24)+1;//第几日
										($num>1 && $num <8) && $newarr[$k1]['retain'][$num] = isset($newarr[$k1]['retain'][$num])?$newarr[$k1]['retain'][$num]+1:1 ;//2到7天的付费人数
									    ($num>1 && $num <8) && $newarr[$k1]['price'][$num] = isset($newarr[$k1]['price'][$num])?$newarr[$k1]['price'][$num]+($ouser['price']*$ouser['num']):$ouser['price']*$ouser['num'];  // 2到7的付费金额
									}
								} 
							}
						}  
				   } 
				}
				$dcount = count($newarr);
				yii::$app->session['datacount'] = $dcount; //今天数据数
				yii::$app->session['pagenum'] = ($dcount<=$pageSize)?1:(($dcount%$pageSize==0)?floor($dcount/$pageSize):floor($dcount/$pageSize)+1); //需要补数据的页数
				yii::$app->session['num'] = yii::$app->session['pagenum']*$pageSize-$dcount; //补数
				yii::$app->session['newarr'] = $newarr;
			}else{
				if(yii::$app->session['newarr']){
					$newarr = yii::$app->session['newarr'];
				}
			}
			$startdata =  $pageSize * ($curPage-1); //当前页面的显示数量
			$newarrdata = array_slice($newarr,$startdata,$pageSize); //截取当前页面的数据
			$newarrcount = count($newarrdata);  //当前页面今天的数量
			$decontinuorder = new Decontinuorder();  
			$countdata = 0;//统计总共的数量
			if($newarrcount>=$pageSize){ //数据数大于显示数 则无需查数据库
				$data['curPage'] = $curPage;
				//每页显示条数
				$data['pageSize'] = $pageSize;
				//起始页
				//	$data['start'] = ($curPage-1)*$pageSize+1;
				$data['data'] = $newarrdata;
				
				$data['count'] = $newarrcount;
				//末页
				$data['end'] = (ceil($data['count']/$pageSize) == $curPage)?$data['count']:($curPage-1)*$pageSize+$pageSize;
				$decontinuorder = $decontinuorder->find()->asArray()->where($selectpid)->andWhere("count_time between $starttime and $endtime")->andWhere($selectgid)->orderBy('count_time desc') ;
				$decontinuorder = $gid?$decontinuorder->andWhere(['gid'=>$gid]):$decontinuorder;
				$countdata = $decontinuorder->count();
			}else{  //数据小于显示数 则从数据库读取补齐
				$pagenum = $pageSize - $newarrcount;  //需要查询数
				if(yii::$app->session['pagenum']==$curPage){//查询需要补数页需要的数据
					$type = true;
					$num  = 0;
				}else{
					$type = false;
					$num = (yii::$app->session['num'])?yii::$app->session['num']:0;//补数
				}
				//$gidarray['gid'] = $gidarr;
				$countarr = $decontinuorder->find()->asArray()->where($selectpid)->andWhere("count_time between $starttime and $endtime")->andWhere($selectgid)->orderBy('count_time desc') ;
				$countarr = $gid?$countarr->andWhere(['gid'=>$gid]):$countarr;
				$data = Helper::getPages($countarr,$curPage,$pagenum,$search = null,$num,false);
				$data['data'] =  ($data['data'])?$data['data']->all():array();
				$newdata='';
				if($data['data']){
					foreach ($data['data'] as $kq => $vq) {
						$new_count = count($newarrdata);
						$newdata[$new_count]['new_user'] = $vq['pay_user'];
						$newdata[$new_count]['gamename'] = $vq['gamename'];
						$newdata[$new_count]['count_time'] = $data['data'][$kq]['count_time'];
						$newdata[$new_count]['play_user'] = $vq['play_user'];
						$newdata[$new_count]['pay_price'] = $vq['pay_price'];
						$newdata[$new_count]['retain'] = ['2'=>$vq['second'],'3'=>$vq['third'],'4'=>$vq['fourth'],'5'=>$vq['fifth'],'6'=>$vq['sixth'],'7'=>$vq['seventh']];
						$newdata[$new_count]['price'] = ['2'=>$vq['secondprice'],'3'=>$vq['thirdprice'],'4'=>$vq['fourthprice'],'5'=>$vq['fifthprice'],'6'=>$vq['sixthprice'],'7'=>$vq['seventhprice']];
					}
				}
				//查询之前的统计
				if($newdata){
					foreach ($newdata as $a){
						array_push($newarr,$a);
					}
				}
				$countdata = $data['count'];
				$data['data'] = $newarr;
			}
			$datacount = (yii::$app->session['datacount'])?yii::$app->session['datacount']+$countdata:$countdata;
			$data['count'] = $datacount;
			//末页
			$data['end'] = (ceil($data['count']/$pageSize) == $curPage)?$data['count']:($curPage-1)*$pageSize+$pageSize;
			//起始页
			$data['start'] = ($curPage-1)*$pageSize+1;
			$pages = new Pagination([ 'totalCount' =>$datacount, 'pageSize' => $pageSize]);
			$gamearr = Game::find()->orderBy('id desc')->asArray()->limit('10000')->select('id,name,descript,label,head_img,game_url,type')->all();//游戏
			//菜单定位
			unset(yii::$app->session['localfirsturl']);
			yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/continueorder/detaindex.html';
			$data['data'] = isset($data['data']) ?array_values($data['data']):'';
			
			$gname = Helper::filtdata(Yii:: $app->request->get( 'gname'));
			return $this->render('detacontinuecount',[
					'data'=>$data,
					'starttime'=>$startdate,
					'endtime'=>$enddate,
					'pages' => $pages,
					'game' => $gamearr,
					'gid' => $gid,
					'pid' => $pid,
					'plate' => $plate,
					'gname' => $gname,
					]);
		}
		
		
		
		

		/**
		 * 持续付费详情统计
		 */
		public function actionDetaindex2(){
			$manage_pid = yii::$app->session->get('pid'); //权限管理
			if($manage_pid){//平台管理则或者平台商
				$p_where = ['id'=>$manage_pid];
			}else{//超级管理员
				$p_where = '';
			}
			$plate = Plateform::find()->where($p_where)->andWhere(['state'=>1])->select(['id','pname'])->all();  //查找所有平台
			$pid = yii::$app->request->get('pid',''); // 查询平台
			if(!$pid){ //没有查询时，则从数据库查找一条
				$pid = ($plate)? $plate['0']['id'] :'-1';
			}
			//分页
			$curPage = Yii:: $app->request->get('page',1);
			$pageSize = yii::$app->params['pagenum'];
			//搜索的条件
			$selectpid = ($pid)?['pid'=>$pid]:'';  //搜索条件  选择的平台id
			$gid = yii::$app->request->get('gid','');  //搜索条件 选择的游戏id
			$selectgid = ($gid)?['gid'=>$gid]:'';
			$startdate = yii::$app->request->get('starttime','');  //搜索条件 开始时间
			$enddate =   yii::$app->request->get('endtime','');    //搜索条件  结束时间
			$starttime = $startdate?strtotime($startdate):strtotime(date('1970-01-01'));  //开始的时间戳
			$endtime = $enddate?strtotime($enddate):time();   //结束的时间戳
				
			$time = strtotime(date('Y-m-d',strtotime('-6 days')));  //六天前的时间戳
			$start = strtotime(date('Y-m-d'));  //今天0点的时间戳
			$end = strtotime(date('Y-m-d 23:59:59'));  //今天24点的时间戳
			$orderuserarr = array(); //七天的订单数据
			$data = array(); //最终数据存放
			$where = $gid?"and gid=$gid":'';
			$resarr = array();  //最终结果存储
			if($curPage==1){//从数据库读取今天的记录
				//查出六天内该平台的日激活数，游戏名称，游戏id，平台id
				$newarr = Count::find()
				->where(['>=','count_time',$time])
				->andwhere(['pid'=>$pid])
				->andWhere($selectgid)
				->andWhere("count_time between $time and $end")
				->select('count_time,sum(play_user) as play_user,gid,gamename')
				->groupBy('pid,gid,count_time')
				->orderBy('count_time desc')
				->asArray()
				->all();
				
				
				if($newarr){  //如果不存在日激活数，说明该款游戏无人玩，无需查询充值记录
					//查出六天内该平台的付款成功的数据
					$orderuserarr = (new \yii\db\Query())
					->select('go.id,go.utype,go.gtype,go.first_time,go.gfirst_time,go.createtime ,go.price,go.gid,g.name,go.num')
					->from(' g_order as go')
					->leftJoin('g_game as g','go.gid=g.id')
					->where(['go.state'=>2,'go.pid'=>$pid])
					->andWhere("go.createtime between $time and $end")
					->andWhere($selectgid)
					->groupBy('go.pid,go.uid,go.createtime')
					->orderBy('go.first_time desc')
					->all();
					if($orderuserarr){
						foreach ($orderuserarr as $k=>$ou){
							$orderuserarr[$k]['createtime'] =  strtotime(date('Y-m-d',$ou['createtime']));  //付款时间去掉时分秒
						}
						foreach ($newarr as $k1 => $v1) {
							$date_time = date('Y-m-d',$v1['count_time']);
							$datatotime = strtotime($date_time);
							
							
							$newarr[$k1]['count_time'] = isset($newarr[$k1]['count_time'])?$newarr[$k1]['count_time']:$v1['count_time'];   //日期
							$newarr[$k1]['new_user'] = isset($newarr[$k1]['new_user'])?$newarr[$k1]['new_user']:0;         //激活数
							$newarr[$k1]['pay_price'] = isset($newarr[$k1]['pay_price'])?$newarr[$k1]['pay_price']: 0;        //当日支付金额
							$newarr[$k1]['retain'] = isset($newarr[$k1]['retain'] )?$newarr[$k1]['retain'] :['2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0];  //持续支付数
							$newarr[$k1]['price'] = isset($newarr[$k1]['price'])?$newarr[$k1]['price']:['2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0];   //持续支付金额
		
							if($orderuserarr){
								foreach ($orderuserarr as $key=>$ouser){
									if(($v1['count_time']==$ouser['first_time'])){//前六天的统计时间
										($ouser['gtype']==2 && $v1['gid']==$ouser['gid']) &&  $newarr[$k1]['new_user'] = isset($newarr[$k1]['new_user'])?$newarr[$k1]['new_user']+1:1;  // 当日付款人数 新增用户
										($ouser['gtype']==2 && $v1['gid']==$ouser['gid']) &&  $newarr[$k1]['pay_price'] = isset($newarr[$k1]['pay_price'])?$newarr[$k1]['pay_price'] +($ouser['price']*$ouser['num']):$ouser['price']*$ouser['num'];
										$num = ($ouser['createtime']-$ouser['first_time'])/(3600*24)+1;//第几日
										($num>1 && $num <8) && $newarr[$k1]['retain'][$num] = isset($newarr[$k1]['retain'][$num])?$newarr[$k1]['retain'][$num]+1:1 ;//2到7天的付费人数
										($num>1 && $num <8) && $newarr[$k1]['price'][$num] = isset($newarr[$k1]['price'][$num])?$newarr[$k1]['price'][$num]+($ouser['price']*$ouser['num']):$ouser['price']*$ouser['num'];  // 2到7的付费金额
									}
								}
							}
						}
					}
				}
				$dcount = count($newarr);
				yii::$app->session['datacount'] = $dcount; //今天数据数
				yii::$app->session['pagenum'] = ($dcount<=$pageSize)?1:(($dcount%$pageSize==0)?floor($dcount/$pageSize):floor($dcount/$pageSize)+1); //需要补数据的页数
				yii::$app->session['num'] = yii::$app->session['pagenum']*$pageSize-$dcount; //补数
				yii::$app->session['newarr'] = $newarr;
			}else{
				if(yii::$app->session['newarr']){
					$newarr = yii::$app->session['newarr'];
				}
			}
			$startdata =  $pageSize * ($curPage-1); //当前页面的显示数量
			$newarrdata = array_slice($newarr,$startdata,$pageSize); //截取当前页面的数据
			$newarrcount = count($newarrdata);  //当前页面今天的数量
			$decontinuorder = new Decontinuorder();
			$countdata = 0;//统计总共的数量
			if($newarrcount>=$pageSize){ //数据数大于显示数 则无需查数据库
				$data['curPage'] = $curPage;
				//每页显示条数
				$data['pageSize'] = $pageSize;
				//起始页
				//	$data['start'] = ($curPage-1)*$pageSize+1;
				$data['data'] = $newarrdata;
		
				$data['count'] = $newarrcount;
				//末页
				$data['end'] = (ceil($data['count']/$pageSize) == $curPage)?$data['count']:($curPage-1)*$pageSize+$pageSize;
				$decontinuorder = $decontinuorder->find()->asArray()->where($selectpid)->andWhere("count_time between $starttime and $endtime")->andWhere($selectgid)->orderBy('count_time desc') ;
				$decontinuorder = $gid?$decontinuorder->andWhere(['gid'=>$gid]):$decontinuorder;
				$countdata = $decontinuorder->count();
			}else{  //数据小于显示数 则从数据库读取补齐
				$pagenum = $pageSize - $newarrcount;  //需要查询数
				if(yii::$app->session['pagenum']==$curPage){//查询需要补数页需要的数据
					$type = true;
					$num  = 0;
				}else{
					$type = false;
					$num = (yii::$app->session['num'])?yii::$app->session['num']:0;//补数
				}
				//$gidarray['gid'] = $gidarr;
				$countarr = $decontinuorder->find()->asArray()->where($selectpid)->andWhere("count_time between $starttime and $endtime")->andWhere($selectgid)->orderBy('count_time desc') ;
				$countarr = $gid?$countarr->andWhere(['gid'=>$gid]):$countarr;
				$data = Helper::getPages($countarr,$curPage,$pagenum,$search = null,$num,false);
				$data['data'] =  ($data['data'])?$data['data']->all():array();
				$newdata='';
				if($data['data']){
					foreach ($data['data'] as $kq => $vq) {
						$new_count = count($newarrdata);
						$newdata[$new_count]['new_user'] = $vq['pay_user'];
						$newdata[$new_count]['gamename'] = $vq['gamename'];
						$newdata[$new_count]['count_time'] = $data['data'][$kq]['count_time'];
						$newdata[$new_count]['play_user'] = $vq['play_user'];
						$newdata[$new_count]['pay_price'] = $vq['pay_price'];
						$newdata[$new_count]['retain'] = ['2'=>$vq['second'],'3'=>$vq['third'],'4'=>$vq['fourth'],'5'=>$vq['fifth'],'6'=>$vq['sixth'],'7'=>$vq['seventh']];
						$newdata[$new_count]['price'] = ['2'=>$vq['secondprice'],'3'=>$vq['thirdprice'],'4'=>$vq['fourthprice'],'5'=>$vq['fifthprice'],'6'=>$vq['sixthprice'],'7'=>$vq['seventhprice']];
					}
				}
				//查询之前的统计
				if($newdata){
					foreach ($newdata as $a){
						array_push($newarr,$a);
					}
				}
				$countdata = $data['count'];
				$data['data'] = $newarr;
			}
			$datacount = (yii::$app->session['datacount'])?yii::$app->session['datacount']+$countdata:$countdata;
			$data['count'] = $datacount;
			//末页
			$data['end'] = (ceil($data['count']/$pageSize) == $curPage)?$data['count']:($curPage-1)*$pageSize+$pageSize;
			//起始页
			$data['start'] = ($curPage-1)*$pageSize+1;
			$pages = new Pagination([ 'totalCount' =>$datacount, 'pageSize' => $pageSize]);
			$gamearr = Game::find()->orderBy('id desc')->asArray()->limit('10000')->select('id,name,descript,label,head_img,game_url,type')->all();//游戏
			//菜单定位
			unset(yii::$app->session['localfirsturl']);
			yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/continueorder/detaindex.html';
			$data['data'] = isset($data['data']) ?array_values($data['data']):'';
				
			$gname = Helper::filtdata(Yii:: $app->request->get( 'gname'));
			return $this->render('detacontinuecount',[
					'data'=>$data,
					'starttime'=>$startdate,
					'endtime'=>$enddate,
					'pages' => $pages,
					'game' => $gamearr,
					'gid' => $gid,
					'pid' => $pid,
					'plate' => $plate,
					'gname' => $gname,
					]);
		}
}