<?php
namespace backend\controllers;
use yii;
use backend\controllers\BaseController;
use common\models\Game;
use common\models\Count;
use common\models\Playgameuser;
use common\models\Plateform;
use common\models\Order;
use common\common\Helper;
use yii\data\Pagination;
use common\common\Phpexcelr;
use common\models\Touristplaygameuser;

class TouristcountController extends BaseController{
	public function actionIndex(){
		$managertype = yii::$app->session->get('managetype'); //管理角色
		$manage_pid = yii::$app->session->get('pid'); //权限管理
		if($manage_pid){//平台管理则或者平台商
			$p_where = ['id'=>$manage_pid];
		}else{//超级管理员
			$p_where = '';
		}
		$plate = Plateform::find()->where($p_where)->andWhere(['state'=>1])->select(['id','pname'])->asArray()->all();  //查找所有平台
		$pidarr = array();
		if($plate){
			foreach ($plate as $p){
				$pidarr[] = $p['id'];
			}
		}
		$pidstr = implode(',',$pidarr);
		$pid = yii::$app->request->get('pid',''); // 查询平台
		$selectpid = ($pid)?['pid'=>$pid]:'';
		$gid = yii::$app->request->get('gid','');
		$date = strtotime(date('Y-m-d')); 
		//分页
		$curPage = Yii:: $app->request->get( 'page',1);
		$pageSize = yii::$app->params['pagenum'];
		//搜索
		$startdate = yii::$app->request->get('starttime','');
		$enddate =   yii::$app->request->get('endtime','');
		$starttime = $startdate?strtotime($startdate):strtotime(date('Y-m-d',time()));
		$endtime = $enddate?strtotime($enddate):time();
		$gameorder = array();//付费用户数
		$playuserarr = array(); //今天游戏玩家记录
		$userdataarr = array();//日活跃记录和日新增用户记录
		$newarr = array();//存放合并数组
		$data = array(); //最终数据存放
		$where = ($gid)?((Helper::filtdata($gid,'INT')!=false)?"and gid=$gid":''):'';
		$pgwhere = ($pid)?((Helper::filtdata($pid,'INT')!=false)?"and O.pid = $pid":''):'';
		$mhide = ($manage_pid)?' and is_hide=1':'';
		if($curPage==1){//从数据库读取今天的记录
			if(($starttime == $date || $endtime == $date)||($starttime==strtotime(date('1970-01-01'))&& $endtime==time())){ //查询日期不属于今天
				//付费用户数
				$gameorder = \Yii::$app->db->createCommand("SELECT T.createtime as count_time,T.gid,T.gamename, COUNT(*) AS pay_user,SUM(S_price) as pay_sum,T.date,COUNT(pay_num) as pay_num FROM (SELECT curdate() AS date,SUM(price) S_price,O.createtime,O.uid, O.gid,COUNT(O.id) as pay_num, G.name AS gamename FROM g_game G LEFT JOIN g_order O ON G.id = O.gid WHERE O.state = 2 and O.logintype=2 and FROM_UNIXTIME(O.createtime,'%Y-%m-%d') = curdate() $pgwhere $where $mhide and O.pid in($pidstr) GROUP BY date, O.uid, O.gid ) T GROUP BY T.Date, T.gamename")->queryAll();
				if($gameorder){
					$gameorder1 = \Yii::$app->db->createCommand("SELECT curdate() AS date,COUNT(*) cuser,SUM(O.price) cprice,O.gid FROM g_order as O where O.utype=2 and O.state=2 and O.logintype=2 and O.pid in($pidstr) $pgwhere $mhide and FROM_UNIXTIME(O.createtime,'%Y-%m-%d') = curdate() group By O.gid,O.uid")->queryAll();//查出每天新增用户数和新增金额总数
					foreach ($gameorder as $k => $v) {
						$gameorder[$k]['cuser'] = 0;
						$gameorder[$k]['cprice'] = 0;
						 if($gameorder1){
							foreach ($gameorder1 as $k1 => $v1) {
								if($v1['gid']==$v['gid']){
									$gameorder[$k]['cuser'] = $v1['cuser'];
									$gameorder[$k]['cprice'] = $v1['cprice'];
								}
							}
						} 
					}
				}
				$today = strtotime(date('Y-m-d')); //今天的时间戳
				
				//今天游戏玩家记录
				$playuserarr = (new \yii\db\Query())
				->select("gg.id as gid,gg.name as gamename,gp.state,gp.type")
				->from(' g_game as gg')
				->leftJoin('g_touristplaygameuser as gp','gg.id=gp.gid')
				->where(['gp.createtime'=>$today])
				->andWhere($selectpid)
				->andWhere(" gp.pid in($pidstr)")
				->orderBy('gg.sort desc');
				$playuserarr = $gid?$playuserarr->andWhere(['gid'=>$gid])->all():$playuserarr->all();
				if($playuserarr){
					$userdataarr = $this->packcount($playuserarr);  //分别封装日活跃记录和日新增记录，并统计
				}
			}
			$newarr = array_values($this->mergearr($gameorder, $userdataarr)); //合并数组 数组索引值从0递增
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
		$count = new Count();
		$countdata = 0;//统计总共的数量
		if($newarrcount>=$pageSize){ //数据数大于显示数 则无需查数据库
			$data['curPage'] = $curPage;
			//每页显示条数
			$data['pageSize'] = $pageSize; 
			//起始页
			$data['data'] = $newarrdata;
			$data['count'] = $newarrcount;
			//末页
			$data['end'] = (ceil($data['count']/$pageSize) == $curPage)?$data['count']:($curPage-1)*$pageSize+$pageSize;
			$countdata = $count->find()->count();
		}else{  //数据小于显示数 则从数据库读取补齐
			$pagenum = $pageSize - $newarrcount;  //需要查询数
			if(yii::$app->session['pagenum']==$curPage){//查询需要补数页需要的数据
				$type = true;
				$num  = 0;
			}else{
				$type = false;
				$num = (yii::$app->session['num'])?yii::$app->session['num']:0;//补数
			}
			//数据库统计表的数据
			$countarr = (new \yii\db\Query())
			->select('FROM_UNIXTIME(count_time) as date ,gamename,count_time,sum(pay_user) as pay_user ,sum(pay_probability) as pay_probability ,sum(pay_sum) as pay_sum,sum(pay_num) as pay_num,gid')
			->from('g_touristcount')
			->where("count_time between $starttime and $endtime and pid in($pidstr)")
			->groupBy('gid,count_time')
			->orderBy('count_time desc');
			$countarr = $gid?$countarr->andWhere(['gid'=>$gid]):$countarr;
			$countarr = $pid?$countarr->andWhere(['pid'=>$pid]):$countarr;
			$data = Helper::getPages($countarr,$curPage,$pagenum,$search = null,$num,false);
			$data['data'] =  ($data['data'])?$data['data']->all():array();
			if($data['data']){
				foreach ($data['data'] as $key => $value) {
					$data['data'][$key]['ARPU'] = ($value['play_user'])?round($value['pay_sum']/$value['play_user'],2):'';
					$data['data'][$key]['ARPPU'] = ($value['pay_user'])?round($value['pay_sum']/$value['pay_user'],2):'';
					$data['data'][$key]['pay_probability'] = ($value['play_user'])?round($value['pay_user']/$value['play_user'],2):'';
				}
			}
			//查询之前的统计
			if($newarrdata){//如果今天的记录不为空 则合并
				foreach ($newarrdata as $na){
					array_unshift($data['data'] ,$na);
				}
			}
			$countdata = $data['count'];
		}
		$datacount = (!yii::$app->session['datacount'])?0+$countdata:yii::$app->session['datacount']+$countdata;
		$data['count'] = $datacount;
		//末页
		$data['end'] = (ceil($data['count']/$pageSize) == $curPage)?$data['count']:($curPage-1)*$pageSize+$pageSize;
		//起始页
		$data['start'] = ($curPage-1)*$pageSize+1;
		$pages = new Pagination([ 'totalCount' =>$datacount, 'pageSize' => $pageSize]);
		$gamearr = Game::find()->orderBy('id desc')->asArray()->limit('10000')->select('id,name,descript,label,head_img,game_url,type')->all();//游戏
		//菜单定位
		unset(yii::$app->session['localfirsturl']);
		yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/touristcount/index.html';
		if($data['data'] && ($endtime-$starttime<=86400)){
			$data['data'] = $this->sigcol_arrsort($data['data'],'play_user',SORT_DESC);//SORT_ASC
		}
		//减去隐藏的订单金钱
		if($manage_pid && !empty($data['data'])){
			$stime = $data['data'][$data['end']-1]['count_time']; 
			$etime = $data['data']['0']['count_time']+3600*24; 
			$hide = \Yii::$app->db->createCommand("SELECT sum(price) as price,pid,createtime,gid FROM g_order_hide O where createtime between :stime and :etime and pid in ($pidstr) $pgwhere group By createtime,gid",[':stime'=>$stime,':etime'=>$etime])->queryAll();
			if($hide){
				foreach ($data['data'] as $kd => $vd) {
					foreach ($hide as $kh => $vh) {
						if(($vh['createtime']==$vd['count_time']) && ($vd['gid']==$vh['gid'])){
							$data['data'][$kd]['pay_sum'] = $vd['pay_sum']-$vh['price'];
							if($data['data'][$kd]['pay_sum']==0){
								unset($data['data'][$kd]);
							}
						}
					}
				}
			}
		}
		$gname = Helper::filtdata(Yii:: $app->request->get( 'gname'));
		return $this->render('touristdetailcount',[
				'data'=>$data,
				'starttime'=>$startdate,
				'endtime'=>$enddate,
				'pages' => $pages,
				'game' => $gamearr,
				'gid' => $gid,
				'gname' => $gname,
				'pid' => $pid,
				'plate' => $plate,
				'managertype'=>$managertype,
				]);
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
	
	/**
	 * 合并数组
	 */
	public function mergearr($gameorder,$userdataarr){
		  $resarr = array();
		  $order = array();
		  if($gameorder){
		  	foreach ($gameorder as $kg => $vg) {
		  		$gameorder[$kg]['play_user'] = 0;
		  		$gameorder[$kg]['new_user'] = 0;
		  		$gameorder[$kg]['old_user'] = 0;
		  		if($userdataarr){
		  			foreach ($userdataarr as $key => $val) {
		  				if($vg['gid']==$val['gid']){
		  					$gameorder[$kg]['new_user'] = $val['new_user'];
		  					$gameorder[$kg]['play_user'] = $val['play_user'];
		  					$gameorder[$kg]['old_user'] = $val['old_user'];
		  				}
		  				if(in_array($key, $vg)){
		  					unset($userdataarr[$key]);
		  				}
		  			}
		  		}
		  	}
		}
		$resarr = $gameorder+$userdataarr;
		return $resarr;
	}
	
	/**
	 * 分别封装日活跃记录和日新增记录，并统计
	 */
	private function packcount($dataarr){
		$actarr = array();//日活跃和新增记录
		foreach($dataarr as $playuser){ //封装日活跃记录，并统计
			if(!isset($actarr[$playuser['gid']])){
				$playuser['play_user'] = 1;        //激活数
				$playuser['new_user'] = 0;         //新增用户数
				$playuser['old_user'] = 0;        //老活跃用户数
				$playuser['pay_user'] = 0;       //支付人数
				$playuser['pay_sum'] = 0.00;        //支付金额
				$playuser['cuser'] = 0;   //
				$playuser['cprice'] = 0.00;   //
				$playuser['pay_num'] = 0;   //付费次数
				$playuser['count_time'] = strtotime(date('Y-m-d'));
				$actarr[$playuser['gid']]=$playuser;
			}else{
				$actarr[$playuser['gid']]['play_user']+=1;
			}
			if($playuser['type']==1){ //新增
				$actarr[$playuser['gid']]['new_user']+=1;
			}else{
				$actarr[$playuser['gid']]['old_user']+=1;
			}
		}
		return  $actarr;
	}

	/**
	 * 游客汇总统计
	 * @return [type] [description]
	 */
	public function actionTocount(){
		$managertype = yii::$app->session->get('managetype'); //管理角色
		$manage_pid = yii::$app->session->get('pid'); //权限管理
		if($manage_pid){//平台管理则或者平台商
			$p_where = ['id'=>$manage_pid];
		}else{//超级管理员
			$p_where = '';
		}
		$plate = Plateform::find()->where($p_where)->andWhere(['state'=>1])->select(['id','pname'])->asArray()->all();  //查找所有平台
		$pidarr = array();
		if($plate){
			foreach ($plate as $p){
				$pidarr[] = $p['id'];
			}
		}
		$pidstr = implode(',',$pidarr);
		$pidstr1 = $manage_pid?" AND pid in ($pidstr)":'';
		$pid = Helper::filtdata(yii::$app->request->get('pid','')); // 查询平台
		$start_date = yii::$app->request->get('starttime','');
		$end_date =   yii::$app->request->get('endtime','');
		$starttime = $start_date?strtotime($start_date):strtotime('1970-01-01');
		$endtime = $end_date?strtotime($end_date)+3600*24:time();
		$curPage = Yii:: $app->request->get( 'page',1);
    	$pageSize = 50;
		//数据库统计表的数据
		$query = (new \yii\db\Query())
		->select('FROM_UNIXTIME(count_time),count_time,sum(play_user) as play_user,sum(pay_user) as pay_user ,sum(pay_probability) as pay_probability ,sum(pay_sum) as pay_sum,sum(pay_num) as pay_num')
		->from('g_touristcount')
		->where("count_time between $starttime and $endtime and pid in ($pidstr)")
		->groupBy('count_time')
		->orderBy('count_time desc');
		$pid && $query = $query->andWhere(['pid'=>$pid]);
		$data = Helper::getPages($query,$curPage,$pageSize,'');
		if($data['data']){
			$data['data'] = $data['data']->all();
			foreach ($data['data'] as $key => $value) {
				$data['data'][$key]['ARPU'] = ($value['play_user'])?round($value['pay_sum']/$value['play_user'],2):0;
				$data['data'][$key]['ARPPU'] = ($value['pay_user'])?round($value['pay_sum']/$value['pay_user'],2):0;
				$data['data'][$key]['pay_probability'] = ($value['play_user'])?round($value['pay_user']/$value['play_user'],2):0;
			}
		}
		$gameorder = '';
		$date = strtotime(date('Y-m-d'));
		$wpid = $pid?"AND pid = $pid":'';
		$mhide = ($manage_pid)?' and is_hide=1':'';
		if($curPage==1){
			if($starttime <= $date && $endtime > $date){ //查询日期不属于今天
				$pwhere = $pid ?['pid'=>$pid] :'';
				//今天游戏有效玩家记录数
				//今天游戏有效玩家记录数
				$playuserarr = (new \yii\db\Query())
					->select('gid,state,createtime')
					->from(' g_touristplaygameuser')
					->where(['createtime'=>$date])
					->andWhere("pid in ($pidstr)")
					->andWhere($pwhere)
					->groupBy('pid,uid,createtime')
					->all();

				$playusernum = 0;
				if($playuserarr){
					foreach ($playuserarr as $pu){
						$playusernum ++;
					}
				}
				//付费率和充值流水
				$gameorder = \Yii::$app->db->createCommand("SELECT count(distinct uid) as pay_user,count(id) AS pay_num,SUM(price) AS pay_sum,createtime as count_time,(SELECT count(distinct uid) FROM g_order WHERE logintype=2 and utype=2 and state=2 $mhide and createtime>=:time1 $wpid and pid in ($pidstr)) AS cuser,(SELECT sum(price) FROM g_order WHERE utype=2 and state=2 $mhide and createtime>=:time1 $wpid and pid in ($pidstr)) AS cprice FROM g_order WHERE logintype=2 and  state = 2 $mhide AND createtime>=:time1 $wpid and pid in ($pidstr)",[':time1'=>$date])->queryOne();
				if($gameorder&&$gameorder['count_time']){//判断是否有数据
					if(!$gameorder['cprice']){$gameorder['cprice']='0.00';}
					$gameorder['play_user'] = $playusernum;
					$gameorder['ARPU'] = ($gameorder['play_user'])?round($gameorder['pay_sum']/$gameorder['play_user'],2):0.00;
					$gameorder['ARPPU'] = ($gameorder['pay_user'])?round($gameorder['pay_sum']/$gameorder['pay_user'],2):0.00;
					$gameorder['pay_probability'] = ($gameorder['play_user'])?round($gameorder['pay_user']/$gameorder['play_user'],2):0.00;
				}else{
					$gameorder = '';
					if($playuserarr){
						$gameorder['play_user'] = $playusernum;
						$gameorder['pay_sum'] = 0.00;
						$gameorder['pay_user'] = 0;
						$gameorder['pay_num'] = 0;
						$gameorder['ARPPU'] = 0;
						$gameorder['ARPU'] = 0;
						$gameorder['pay_probability'] = 0;
						$gameorder['count_time'] = strtotime(date('Y-m-d')); 
					}
				}
			}
		}
		//减去隐藏的订单金钱
		if($manage_pid && !empty($data['data'])){
			$stime = $data['data'][count($data['data'])-1]['count_time']; 
			$etime = $data['data']['0']['count_time']; 
			$hide = \Yii::$app->db->createCommand("SELECT sum(price) as price,pid,createtime FROM g_order_hide where createtime between :stime and :etime $pidstr1 $wpid group By createtime",[':stime'=>$stime,':etime'=>$etime])->queryAll();
			if($hide){
				foreach ($data['data'] as $kd => $vd) {
					foreach ($hide as $kh => $vh) {
						if($vh['createtime']==$vd['count_time']){
							$data['data'][$kd]['pay_sum'] = $vd['pay_sum']-$vh['price'];
						}
					}
				}
			}
		}
		if(isset($gameorder)&&$gameorder){
			$data['count'] = $data['count'] + 1;
			$data['end'] = (ceil($data['count']/$pageSize) == $curPage)?$data['count']:($curPage)*$pageSize+1;
		}
		$pages = new Pagination([ 'totalCount'=>$data[ 'count'], 'pageSize' => $pageSize]);


		//菜单定位
		unset(yii::$app->session['localfirsturl']);
		yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/touristcount/tocount.html';
		return $this->render('touristcollectcount',[
			'data'=>$data,
			'starttime'=>$start_date,
			'endtime'=>$end_date,
			'pages'=>$pages,
			'gameorder'=>$gameorder,
			'plate'=>$plate,
			'pid'=>$pid,
			'managertype'=>$managertype,
		]);
	}

	//导出订单数据页面
    public function actionDownload(){
    	$pid =	Helper::filtdata($_GET['pid'],'INT');
    	$plate = Plateform::find()->andWhere(['state'=>1])->select(['id','pname'])->all();
        return $this->renderPartial("download",['plate'=>$plate,'pid'=>$pid]);
    }

    //导出游客汇总统计数据
    public function actionOutput(){
        $pid = Helper::filtdata(yii::$app->request->get('pid',''));
        $start_time = Helper::filtdata(Yii:: $app->request->get('start_time',''));
        $end_time = Helper::filtdata(Yii:: $app->request->get('end_time'));
        if($start_time=='undefined'){$start_time = '';}
        if($end_time=='undefined'){$end_time = '';}
        $starttime = $start_time?strtotime($start_time):strtotime('2000-01-01');
        $endtime = $end_time?strtotime($end_time)+3600*24:time();
        $date = strtotime(date('Y-m-d')); 
        $wpid = $pid?"AND pid = $pid":'';
        if($starttime <= $date && $endtime > $date){ //查询日期不属于今天
			//今天游戏有效玩家记录数
			$playuserarr = (new \yii\db\Query())
				->select('gid,state,createtime,type')
				->from(' g_touristplaygameuser')
				->where(['createtime'=>$date,'pid'=>$pid])
				->groupBy('pid,uid,createtime')
				->all();
			$newarr = array();
			$dataarr = array();
			$playusernum = 0;
			if($playuserarr){
				foreach ($playuserarr as $pu){
					$playusernum ++;
				}
			}
			//付费率和充值流水
			$gameorder = \Yii::$app->db->createCommand("SELECT count(distinct uid) as pay_user,count(id) AS pay_num,SUM(price) AS pay_sum,createtime as count_time,(SELECT count(distinct uid) FROM g_order WHERE logintype=2 and utype=2 and state=2 and createtime>=:time1 $wpid) AS cuser,(SELECT sum(price) FROM g_order WHERE logintype=2 and utype=2 and state=2 and createtime>=:time1 $wpid) AS cprice FROM g_order WHERE logintype=2 and state = 2 AND createtime>=:time1 $wpid",[':time1'=>$date])->queryOne();
			if($gameorder){
				$gameorder['play_user'] = $playusernum;
			}else{
				if($playuserarr){
					$gameorder['play_user'] = $playusernum;
					$gameorder['pay_sum'] = 0.00;
					$gameorder['pay_user'] = 0;
					$gameorder['pay_num'] = 0;
					$gameorder['count_time'] = strtotime(date('Y-m-d')); 
				}
			}
		}
        //数据库统计表的数据
		$data = (new \yii\db\Query())
		->select('count_time,sum(play_user) as play_user,sum(pay_user) as pay_user ,sum(pay_sum) as pay_sum,sum(pay_num) as pay_num')
		->from('g_touristcount')
		->where("count_time between $starttime and $endtime")
		->groupBy('pid,count_time')
		->orderBy('count_time desc');
		$pid && $data = $data->andWhere(['pid'=>$pid]);
		$data = $data->all();
        if(!$data){
            return '没有数据需要导出';  
        } 
        $header = ['编号','日期','激活数','总付费人数','总付费金额(元)','付费次数','ARPU(元),ARPPU(元),付费率(%)'];
        $arr = array();
        if(isset($gameorder)&&$gameorder){
        	array_unshift($data,$gameorder);
        } 
        foreach ($data as $k => $v) {
            $arr[$k]['v'] = $k+1;
			$arr[$k]['count_time'] = date('Y-m-d',$v['count_time']);
            $arr[$k]['play_user'] = $v['play_user'];
            $arr[$k]['pay_user'] = $v['pay_user'];
            $arr[$k]['pay_sum'] = $v['pay_sum'];
            $arr[$k]['pay_num'] = $v['pay_num'];
        	$arr[$k]['ARPU'] = ($v['play_user'])?round($v['pay_sum']/$v['play_user'],2):'0.00';
			$arr[$k]['ARPPU'] = ($v['pay_user'])?round($v['pay_sum']/$v['pay_user'],2):'0.00';
			$arr[$k]['pay_probability'] = ($v['play_user'])?round($v['pay_user']/$v['play_user'],2):'0';
        }
       	$time = $start_time?($start_time.' - '.$end_time.'_'):'';
        Phpexcelr::exportData($arr,$header,$time."游客汇总导出",$time."游客汇总导出");
        exit;//阻止跳转，一定要写，不写会跳转
    }
    
    /**
     * 游客月汇总统计
     */
    public function actionMonthcount(){
    	$managertype = yii::$app->session->get('managetype'); //管理角色
    	$manage_pid = yii::$app->session->get('pid'); //权限管理
    	$gcreatime1 = '';
    	if($manage_pid){//平台管理则或者平台商
    		$p_where = ['id'=>$manage_pid];
    		if(yii::$app->session->get('platepid')==6){
            	$gcreatime1 = 'and createtime>=1501516800';
            }
    		$mhide = 'and is_hide=1';
            $gp_where1 = 'and pid in ('.implode(',',$manage_pid).')';
		}else{//超级管理员
			$p_where = '';
            $gp_where1 ='';
    		$mhide ='';
		}
    	$plate = Plateform::find()->where($p_where)->andWhere(['state'=>1])->select(['id','pname'])->asArray()->all();  //查找所有平台
    	$pidarr = array();
    	if($plate){
    		foreach ($plate as $p){
    			$pidarr[] = $p['id'];
    		}
    	}
    	$pidstr = implode(',',$pidarr);
    	$pidstr1 = $manage_pid?"pid in ($pidstr)":'';
    	$pid = Helper::filtdata(yii::$app->request->get('pid',''),'INT'); // 查询平台
    	$wpid = $pid?"AND pid = $pid":'';
    	$yearmonth = Helper::filtdata(yii::$app->request->get('yearmonth',''));//查询的日期
    	$starttime = $yearmonth ?strtotime(date('Y-m-01', strtotime($yearmonth))) :''; //当月开始的时间戳
    	$endtime = $yearmonth ?strtotime(date('Y-m-t', strtotime($yearmonth)))+86400 : '';  //当月结束的时间戳
    	$month = strtotime(date('Y-m'));//当月的时间戳
    	$curPage = Yii:: $app->request->get( 'page',1);
    	$pageSize = 50;
    	$data= '';
    	$data['data'] =array();
    	$gameorder = '';
    	$where =  $starttime ? "and createtime between $starttime and $endtime" : '';
    
    	//之前月份的数据查询数据库统计表的数据
    	$query = (new \yii\db\Query())
    	->select('FROM_UNIXTIME(count_time),count_time,sum(play_user) as play_user,sum(pay_user) as pay_user ,sum(pay_probability) as pay_probability ,sum(pay_sum) as pay_sum,sum(pay_num) as pay_num')
    	->from('g_touristmonthcount')
    	->where("$pidstr1")
    	->groupBy('count_time')
    	->orderBy('count_time desc');
    	$where && $query = $query->andWhere("count_time between $starttime and $endtime");
    	$pid && $query = $query->andWhere(['pid'=>$pid]);
    	$data = Helper::getPages($query,$curPage,$pageSize,'');
    	if($data['data']){
    		$data['data'] = $data['data']->all();
    		foreach ($data['data'] as $key => $value) {
    			$data['data'][$key]['ARPU'] = ($value['play_user'])?round($value['pay_sum']/$value['play_user'],2):'';
    			$data['data'][$key]['ARPPU'] = ($value['pay_user'])?round($value['pay_sum']/$value['pay_user'],2):'';
    			$data['data'][$key]['pay_probability'] = ($value['play_user'])?round($value['pay_user']/$value['play_user'],2):'';
    		}
    	}
    
    
    	if($curPage==1 && ($starttime == $month || $starttime=='')){//当月的数据
    		$monthfirst = strtotime(date('y-m-01',$month));//当月的第一天
    		$monthlast = strtotime(date('y-m-t',$month))+86400;//当月的最后一 天
    		$pwhere = $pid ?['pid'=>$pid] :'';
    		//今天游戏有效玩家记录数
    		$playuserarr = (new \yii\db\Query())
    		->select('gid,state,createtime')
    		->from(' g_touristplaygameuser')
    		->where("createtime between $monthfirst and $monthlast")
    		->andWhere("$pidstr1")
    		->andWhere($pwhere)
    		->groupBy('pid,uid,createtime');
    		$playuserarr = $playuserarr->all();
    		$playusernum = 0;
    		if($playuserarr){
    			foreach ($playuserarr as $pu){
    				$playusernum ++;
    			}
    		}
    		$date = strtotime(date('Y-m')); 
    		//付费率和充值流水
    		$gameorder = \Yii::$app->db->createCommand("SELECT count(distinct uid) as pay_user,count(id) AS pay_num,SUM(price) AS pay_sum,createtime as count_time,(SELECT count(distinct uid) FROM g_order WHERE logintype=2 and  utype=2 and state=2 $mhide and createtime>=:time1 $wpid and pid in ($pidstr)) AS cuser,(SELECT sum(price) FROM g_order WHERE logintype=2 and utype=2 and state=2 $mhide and createtime>=:time1 $wpid and pid in ($pidstr)) AS cprice FROM g_order WHERE logintype=2 and state = 2 $mhide AND createtime>=:time1 $wpid and pid in ($pidstr)",[':time1'=>$date])->queryOne();
    		if($gameorder&&$gameorder['count_time']){//判断是否有数据
    			if(!$gameorder['cprice']){$gameorder['cprice']='0.00';}
    			$gameorder['play_user'] = $playusernum;
    			$gameorder['ARPU'] = ($gameorder['play_user'])?round($gameorder['pay_sum']/$gameorder['play_user'],2):0.00;
    			$gameorder['ARPPU'] = ($gameorder['pay_user'])?round($gameorder['pay_sum']/$gameorder['pay_user'],2):0.00;
    			$gameorder['pay_probability'] = ($gameorder['play_user'])?round($gameorder['pay_user']/$gameorder['play_user'],2):0.00;
    		}else{
    			$gameorder = '';
    			if($playuserarr){
    				$gameorder['play_user'] = $playusernum;
    				$gameorder['new_user'] = $newusernum;
    				$gameorder['old_user'] = $oldusernum;
    				$gameorder['pay_sum'] = 0.00;
    				$gameorder['pay_user'] = 0;
    				$gameorder['pay_num'] = 0;
    				$gameorder['ARPPU'] = 0;
    				$gameorder['ARPU'] = 0;
    				$gameorder['pay_probability'] = 0;
    				$gameorder['count_time'] = strtotime(date('Y-m-d'));
    			}
    		}
    	}
    	 
    	//查询之前的统计
    	if($gameorder){//如果今天的记录不为空 则合并
    		$data['end']++;
    		($data['start']==0)?$data['start']++:$data['start'];
     		array_unshift($data['data'] ,$gameorder);//合并数据
    	}
    	$data[ 'count'] = count($data['data']);
    	//减去隐藏的订单金钱
		$pidstr1 = $pidstr1?'and '.$pidstr1:'';
		if($manage_pid && !empty($data['data'])){
			$stime = $data['data'][$data[ 'count']-1]['count_time']; 
			$etime = $data['data']['0']['count_time']; 
			$hide = \Yii::$app->db->createCommand("SELECT sum(price) as price,pid,createtime FROM g_order_hide where createtime between :stime and :etime $pidstr1 $wpid group By createtime",[':stime'=>$stime,':etime'=>$etime])->queryAll();
			if($hide){
				foreach ($data['data'] as $kd => $vd) {
					foreach ($hide as $kh => $vh) {
						$mtime = strtotime(date('Y-m-01',$vh['createtime']));
						if(($mtime==$vd['count_time'])){
							$data['data'][$kd]['pay_sum'] = $vd['pay_sum']-$vh['price'];
							$vd['pay_sum'] = $data['data'][$kd]['pay_sum'];
						}
					}
				}
			}
		}
		if($starttime){
			$start_time = $starttime;
			$end_time = $endtime;
		}else{
			$start_time = strtotime(date('Y-m-01'));
			$end_time = strtotime(date('Y-m-t'))+86400;
		}
			$time_s = '';
			$time_ss = '';
		//游戏总览
        $zcount = \Yii::$app->db->createCommand("SELECT sum(price) zprice,count(distinct uid) zuid from g_order where logintype=2 and state=2 $mhide $gcreatime1 $wpid $gp_where1 ")->queryOne();//总付费用户数和总付费金额(元)

        $cplay = Touristplaygameuser::find();
		$pid && $cplay = $cplay->where(['pid'=>$pid]);
		$cplay = $cplay->groupBy('uid')->count();
    	$pages = new Pagination([ 'totalCount'=>$data[ 'count'], 'pageSize' => $pageSize]);
    	//菜单定位
    	unset(yii::$app->session['localfirsturl']);
    	yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/touristcount/monthcount.html';
    	return $this->render('touristmonthcount',[
    			'data'=>$data,
    			'starttime'=>$starttime,
    			'endtime'=>$endtime,
    			'pages'=>$pages,
    			'plate'=>$plate,
    			'pid'=>$pid,
    			'managertype'=>$managertype,
    			'zcount'=>$zcount,
    			'cplay'=>$cplay
    			]);
    }
    
    
    /**
     * 导出详细游客统计的数据
     * @return [type] [description]
     */
    public function actionDetailsoutput(){
    	set_time_limit(0);
    	$pid = Helper::filtdata(yii::$app->request->get('pid',''));//平台id
    	$gid = Helper::filtdata(yii::$app->request->get('gid',''));//游戏id
    	$start_time = Helper::filtdata(Yii:: $app->request->get('start_time',''));
    	$end_time = Helper::filtdata(Yii:: $app->request->get('end_time'));
    	if($start_time=='undefined'){$start_time = '';}
    	if($end_time=='undefined'){$end_time = '';}
    	$starttime = $start_time?strtotime($start_time):strtotime(date('Y-m-d'));
    	$endtime = $end_time?strtotime($end_time)+3600*24:time();
    	$date = strtotime(date('Y-m-d'));
    	$newarr = array();
    	if($endtime>$date){
    		if(yii::$app->session['newarr']){
    			$newarr = yii::$app->session['newarr'];
    		}else{
    			return $this->redirect('index.html');
    		}
    	}
    
    	$countarr = (new \yii\db\Query())
    	->select('FROM_UNIXTIME(count_time) as date ,gamename,count_time,sum(pay_user) as pay_user,sum(pay_probability) as pay_probability ,sum(pay_sum) as pay_sum,sum(old_user) as old_user,sum(pay_num) as pay_num,gid')
    	->from('g_count')
    	->where("count_time between $starttime and $endtime")
    	->groupBy('gid,count_time')
    	->orderBy('count_time desc');
    	$countarr = $gid?$countarr->andWhere(['gid'=>$gid]):$countarr;
    	$countarr = $pid?$countarr->andWhere(['pid'=>$pid]):$countarr;
    	$data =  $countarr->all();
    	if($data){
    		foreach ($data as $key => $value) {
    			$data[$key]['ARPU'] = ($value['play_user'])?round($value['pay_sum']/$value['play_user'],2):0;
    			$data[$key]['ARPPU'] = ($value['pay_user'])?round($value['pay_sum']/$value['pay_user'],2):0;
    			$data[$key]['pay_probability'] = ($value['play_user'])?round($value['pay_user']/$value['play_user'],2):0;
    		}
    	}
    	//查询之前的统计
    	if($newarr){//如果今天的记录不为空 则合并
    		foreach ($newarr as $na){
    			$na['ARPU'] = ($na['play_user'])?round($na['pay_sum']/$na['play_user'],2):0;
    			$na['ARPPU'] = ($na['pay_user'])?round($na['pay_sum']/$na['pay_user'],2):0;
    			$na['pay_probability'] = ($na['play_user'])?round($na['pay_user']/$na['play_user'],2):0;
    			array_unshift($data ,$na);
    		}
    	}
    	if(!$data){
    		return '没有数据需要导出';
    	}
    	$header = ['编号','日期','游戏名称','日DAU','付费人数','总付费金额(元)','付费次数','ARPU(元),ARPPU(元),付费率(%)'];
    	foreach ($data as $k => $v) {
    		$arr[$k]['v'] = $k+1;
    		$arr[$k]['count_time'] = date('Y-m-d',$v['count_time']);
    		$arr[$k]['gamename'] = $v['gamename'];
    		$arr[$k]['play_user'] = $v['play_user'];
    		$arr[$k]['pay_user'] = $v['pay_user'];
    		$arr[$k]['pay_sum'] = $v['pay_sum'];
    		$arr[$k]['pay_num'] = $v['pay_num'];
    		$arr[$k]['ARPU'] = $v['ARPU'];
    		$arr[$k]['ARPPU'] = $v['ARPPU'];
    		$arr[$k]['pay_probability'] = $v['pay_probability'];
    	}
    	$time = $start_time?($start_time.' - '.$end_time.'_'):'';
    	Phpexcelr::exportData($arr,$header,$time."游客详细统计导出",$time."游客详细统计导出");
    	exit;//阻止跳转，一定要写，不写会跳转
    }
    
    
    /**
     * 游客月汇总导出
     */
    public function actionMdataexport(){
    	$managertype = yii::$app->session->get('managetype'); //管理角色
    	$manage_pid = yii::$app->session->get('pid'); //权限管理
    	if($manage_pid){//平台管理则或者平台商
    		$p_where = ['id'=>$manage_pid];
    	}else{//超级管理员
    		$p_where = '';
    	}
    	$plate = Plateform::find()->where($p_where)->andWhere(['state'=>1])->select(['id','pname'])->asArray()->all();  //查找所有平台
    	$pidarr = array();
    	if($plate){
    		foreach ($plate as $p){
    			$pidarr[] = $p['id'];
    		}
    	}
    	$pidstr = implode(',',$pidarr);
    	$pid = Helper::filtdata(yii::$app->request->get('pid',''),'INT'); // 查询平台
    	$wpid = $pid?"AND pid = $pid":'';
    	$yearmonth = Helper::filtdata(yii::$app->request->get('yearmonth',''));//查询的日期
    	$starttime = $yearmonth ?strtotime(date('Y-m-01', strtotime($yearmonth))) :''; //当月开始的时间戳
    	$endtime = $yearmonth ?strtotime(date('Y-m-t', strtotime($yearmonth))) : '';  //当月结束的时间戳
    	$month = strtotime(date('Y-m'));//当月的时间戳
    	$curPage = Yii:: $app->request->get( 'page',1);
    	$pageSize = 50;
    	$data= '';
    	$data['data'] =array();
    	$gameorder = '';
    	$where =  $starttime ? "and createtime between $starttime and $endtime" : '';
    	 
    	//之前月份的数据查询数据库统计表的数据
    	$query = (new \yii\db\Query())
    	->select('FROM_UNIXTIME(count_time),count_time,sum(play_user) as play_user,sum(pay_user) as pay_user ,sum(pay_probability) as pay_probability ,sum(pay_sum) as pay_sum,sum(pay_num) as pay_num')
    	->from('g_touristmonthcount')
    	->where("pid in ($pidstr)")
    	->groupBy('count_time')
    	->orderBy('count_time desc');
    	$where && $query = $query->andWhere("count_time between $starttime and $endtime");
    	$pid && $query = $query->andWhere(['pid'=>$pid]);
    	$data = Helper::getPages($query,$curPage,$pageSize,'');
    	if($data['data']){
    		$data['data'] = $data['data']->all();
    		foreach ($data['data'] as $key => $value) {
    			$data['data'][$key]['ARPU'] = ($value['play_user'])?round($value['pay_sum']/$value['play_user'],2):0;
    			$data['data'][$key]['ARPPU'] = ($value['pay_user'])?round($value['pay_sum']/$value['pay_user'],2):0;
    			$data['data'][$key]['pay_probability'] = ($value['play_user'])?round($value['pay_user']/$value['play_user'],2):0;
    		}
    	}
    	 
    	 
    	if($curPage==1 && ($starttime == $month || $starttime=='')){//当月的数据
    		$monthfirst = strtotime(date('y-m-01',$month));//当月的第一天
    		$monthlast = strtotime(date('y-m-t',$month));//当月的最后一 天
    		$pwhere = $pid ?['pid'=>$pid] :'';
    		//今天游戏有效玩家记录数
    		$playuserarr = (new \yii\db\Query())
    		->select('gid,state,createtime')
    		->from(' g_touristplaygameuser')
    		->where("createtime between $monthfirst and $monthlast and pid in ($pidstr)")
    		->andWhere($pwhere)
    		->groupBy('pid,uid,createtime');
    		$playuserarr = $playuserarr->all();
    		$playusernum = 0;
    		if($playuserarr){
    			foreach ($playuserarr as $pu){
    				$playusernum ++;
    			}
    		}
    		//付费率和充值流水
    		$gameorder = \Yii::$app->db->createCommand("SELECT count(distinct uid) as pay_user,count(id) AS pay_num,SUM(price) AS pay_sum,createtime as count_time,(SELECT count(distinct uid) FROM g_order WHERE logintype=2 and utype=2 and state=2 and createtime between $monthfirst and $monthlast $wpid) AS cuser,(SELECT sum(price) FROM g_order WHERE logintype=2 and utype=2 and state=2 and createtime between $monthfirst and $monthlast $wpid) AS cprice FROM g_order WHERE logintype=2 and state = 2 and createtime between $monthfirst and $monthlast $wpid and pid in ($pidstr)")->queryOne();
    		if($gameorder&&$gameorder['count_time']){//判断是否有数据
    			if(!$gameorder['cprice']){$gameorder['cprice']='0.00';}
    			$gameorder['play_user'] = $playusernum;
    			$gameorder['ARPU'] = ($gameorder['play_user'])?round($gameorder['pay_sum']/$gameorder['play_user'],2):0.00;
    			$gameorder['ARPPU'] = ($gameorder['pay_user'])?round($gameorder['pay_sum']/$gameorder['pay_user'],2):0.00;
    			$gameorder['pay_probability'] = ($gameorder['play_user'])?round($gameorder['pay_user']/$gameorder['play_user'],2):0.00;
    		}else{
    			$gameorder = '';
    			if($playuserarr){
    				$gameorder['play_user'] = $playusernum;
    				$gameorder['pay_sum'] = 0.00;
    				$gameorder['pay_user'] = 0;
    				$gameorder['pay_num'] = 0;
    				$gameorder['ARPPU'] = 0;
    				$gameorder['ARPU'] = 0;
    				$gameorder['pay_probability'] = 0;
    				$gameorder['count_time'] = strtotime(date('Y-m-d'));
    			}
    		}
    	}
    
    	//查询之前的统计
    	if($gameorder){//如果今天的记录不为空 则合并
    		array_unshift($data['data'] ,$gameorder);//合并数据
    	}
    	 
    	if(!$data['data']){
    		return '没有数据需要导出';
    	}
    	$header = ['编号','日期','月DAU','付费人数','总付费金额(元)','付费次数','ARPU(元)','ARPPU(元)','付费率'];
    	foreach ($data['data'] as $k => $v) {
    		$arr[$k]['v'] = $k+1;
    		$arr[$k]['count_time'] = isset($v['count_time'])?date('Y-m',$v['count_time']):0;
    		$arr[$k]['play_user'] = isset($v['play_user'])?$v['play_user']:0;
    		$arr[$k]['pay_user'] =  isset($v['pay_user'])?$v['pay_user']:0;
    		$arr[$k]['pay_sum'] =  isset($v['pay_sum'])?$v['pay_sum']:0;
    		$arr[$k]['pay_num'] =  isset($v['pay_num'])?$v['pay_num']:0;
    		$arr[$k]['ARPU'] =  isset($v['ARPU'])?$v['ARPU']:0;
    		$arr[$k]['ARPPU'] =  isset($v['ARPPU'])?$v['ARPPU']:0;
    		$arr[$k]['pay_probability'] =  isset($v['pay_probability'])?$v['pay_probability']:0;
    	}
    	//$arr = $this->sigcol_arrsort($arr,'reprice',SORT_DESC);
    	//$time = date("Y-m-d",$starttime).' - '.date('Y-m-d',$endtime).'_';
    	Phpexcelr::exportData($arr,$header,"游客月汇总导出","游客月统计导出");
    	exit;//阻止跳转，一定要写，不写会跳转
       
    }

    /**
     * 数据统计页面
     * 折线图数据
     */
    public function actionCountfoleline() {
        $managemodel = yii::$app->session['tomodel'];
        $pid = yii::$app->session->get('pid'); //权限管理  
        $nowtime = date('Y-m-d');
        $qyear = substr($nowtime,0,4); //当前年份
        $qmonth = substr($nowtime,5,2);//当前月份
        $time = date('Y-m',strtotime($nowtime));
        if(isset($_GET['time'])){
            $qyear = htmlspecialchars(trim(($_GET['time'])));
            $qmonth = isset($_GET['time1'])?htmlspecialchars(trim(($_GET['time1']))):$qmonth;
            $time = $qyear.'-'.$qmonth;
        }
        $time1 = strtotime($time);
        $months1 = strtotime("+1months",$time1);
        $count = Count::find()->andWhere("count_time between $time1 and $months1");
        
        $pid && $count = $count->andWhere(['pid'=>$pid]);   
        $re_data = array();
        $re_count = $count->count();
        if($re_count){
            $re_data = $count->select("sum(pay_sum) as pay_sum,sum(pay_user) as pay_user,sum(new_user) as new_user,sum(play_user) as play_user,count_time")->groupBy('count_time')->asArray()->all();
            $aq = date('Y-m');//今天的时间
            if($time==$aq){
            	$nowarr = array();
            	$n_time = strtotime($nowtime);
            	$playuserarr = Playgameuser::find()->select('state,createtime')->where(['createtime'=>$n_time])->groupBy('pid,uid,createtime')->all();
				$nowarr['play_user'] = 0;
				$nowarr['new_user'] = 0;
				if($playuserarr){
					foreach ($playuserarr as $pu){
						$nowarr['play_user'] ++;
						if($pu['state']==1){
							$nowarr['new_user']++;
						}
					}
				}
				$mhide = ($pid)?['is_hide'=>1]:'';
				$gameorder = Order::find()->where(['>=','createtime',$n_time])->andWhere('state=2')->andWhere($mhide)->andWhere(['pid'=>$pid])->select('sum(price) pay_sum,count(distinct uid) pay_user')->asArray()->one();
				$nowarr['pay_user'] = $gameorder['pay_user'];
				$nowarr['pay_sum'] = $gameorder['pay_sum'];
				$nowarr['count_time'] = $n_time;
            }
        }
        $darr = array();//显示天数1-31天
        $now_time = substr($nowtime,8,2);
        for($index=1;$index<=$now_time;$index++){
            $darr[]=$index;
        }
        $darr = implode(',',$darr); //数组转字符串
        $valarr=array();//统计新增用户
        $valarr1=array();//统计活跃用户
        $valarr2=array();//统计付费用户
        $valarr3=array();//统计充值流水
        if($pid && !empty($re_data)){
			$etime = $re_data[count($re_data)-1]['count_time']; 
			$stime = $re_data['0']['count_time'];
			$pidstr = implode(',',$pid);
			$mpid = $pid?"pid in ($pidstr)":'';
			$hide = \Yii::$app->db->createCommand("SELECT sum(price) as price,createtime FROM g_order_hide where $mpid and createtime between :stime and :etime group By createtime",[':stime'=>$stime,':etime'=>$etime])->queryAll();
		}
        foreach ($re_data as $kd=>$vc){
        	if(isset($hide)&&$hide){
        		foreach ($hide as $kh => $vh) {
					if($vh['createtime']==$vc['count_time']){
						$re_data[$kd]['cprice'] = $vc['pay_sum']-$vh['price'];
					}
				}
        	}
            $count_time = date('Y-m-d',$vc['count_time']);
            $cr_day = substr($count_time,8,2);
            if($cr_day<10){
                $cr_day = substr($cr_day,1,1);
            }
            if(substr($count_time,0,4) == $qyear){  //查询的年份
                if(substr($count_time,5,2) == $qmonth){//查询的月份
                	$valarr[$cr_day] = $vc['new_user'];
                	$valarr1[$cr_day] = $vc['play_user'];
                	$valarr2[$cr_day] = $vc['pay_user'];
                	$valarr3[$cr_day] = $vc['pay_sum'];
                    $arr = substr($count_time,8,2);//获取具体的日期
                    if($arr<10){
                        $arr = substr($arr,1,1);
                    }
                    $dayarr[] = $arr;
                }
            }
        }
        if(isset($nowarr)){//今天的数据
        	$count_time = date('Y-m-d',$nowarr['count_time']);
            $cr_day = substr($count_time,8,2);
            if($cr_day<10){
                $cr_day = substr($cr_day,1,1);
            }
        	$valarr[$cr_day] = $nowarr['new_user'];
        	$valarr1[$cr_day] = $nowarr['play_user'];
        	$valarr2[$cr_day] = $nowarr['pay_user'];
        	$valarr3[$cr_day] = $nowarr['pay_sum'];
        }
        $val_s['valdata'] = implode(',',$valarr);
        $val_s['valdata1'] = implode(',',$valarr1);
        $val_s['valdata2'] = implode(',',$valarr2);
        $val_s['valdata3'] = implode(',',$valarr3);
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/count/countfoldline.html';
        return $this->render('countfoleline',[
            'valdata'=>$val_s,
            'darr'=>$darr,
            'time'=>$qyear,
            'time1'=>$qmonth
        ]);
    }
}