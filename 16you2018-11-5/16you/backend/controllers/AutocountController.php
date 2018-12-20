<?php
namespace backend\controllers;

use yii;
use yii\web\Controller;
use common\models\Count;
use common\models\Game;
use common\models\Retain;
use common\models\Cretain;
use common\models\Recycle;
use common\models\Crecycle;
use common\models\Continuorder;
use common\models\Decontinuorder;
use common\models\Playgameuser;
use common\models\Monthcount;
use common\redismodel\PlaygameuserRedis;
use common\models\Downloadrecord;
use common\models\Daydownload;
use common\models\Newservice;
 

class AutocountController extends Controller{

	/**
	 *  每天自动统计
	 *  已pid（平台id）进行分组
	 *  gid 游戏id
	 *  pid 平台id
	 *  count_time 统计的日期
	 *  gamename 游戏名称
	 *  new_user 新增用户数 
	 *  play_user 日活跃用户数 (激活数) 
	 *  pay_user 总付费人数
	 *  pay_probability 付费率 （百分比的形式 %）
	 *  pay_sum 总付费金额
	 *  ARPU = 充值总流水/日活跃用户数 (元)
	 *  ARPPU = 即充值流水/付费用户数 （元）
	 *  cuser 新增付费人数 
	 *  cprice 新增付费金额
	 *  old_user 老活跃用户数
	 *  pay_num 付费次数
	 *  @return [type] [description]
	 */
	public function actionDaycount(){
		$date = strtotime(date('Y-m-d',strtotime('-1 day')));//昨天的时间
		//昨天的付费用户数和充值流水
		$gameorder = \Yii::$app->db->createCommand("SELECT T.date,T.gid,T.gamename, T.pid,T.createtime as count_time,COUNT(*) AS pay_user,SUM(S_price) as pay_sum,SUM(pay_num) AS pay_num FROM (SELECT date_sub(curdate(),interval 1 day) AS date,SUM(price) S_price,O.createtime,O.uid,O.gid,O.pid, G.name AS gamename,COUNT(O.id) as pay_num FROM g_game G LEFT JOIN g_order O ON G.id = O.gid WHERE O.state = 2 and FROM_UNIXTIME(O.createtime,'%Y-%m-%d') = date_sub(curdate(),interval 1 day) GROUP BY O.uid,O.gid,O.pid) T GROUP BY T.gid,T.pid")->queryAll();
		if($gameorder){
			$gameorder1 = \Yii::$app->db->createCommand("SELECT COUNT(*) cuser,SUM(price) cprice,gid,pid FROM g_order where utype=2 and state=2 and FROM_UNIXTIME(createtime,'%Y-%m-%d') = date_sub(curdate(),interval 1 day) group By gid,uid,pid")->queryAll();//查出每天新增用户数和新增金额总数
			foreach ($gameorder as $k => $v) {
				$gameorder[$k]['cuser'] = 0;
				$gameorder[$k]['cprice'] = 0;
				if($gameorder1){
					foreach ($gameorder1 as $k1 => $v1) {
						if($v1['gid']==$v['gid'] && ($v1['pid']==$v['pid'])){
							$gameorder[$k]['cuser'] = $v1['cuser'];
							$gameorder[$k]['cprice'] = $v1['cprice'];
						}
					}
				}
			}
		}
		//昨天游戏玩家记录
		$playuserarr = (new \yii\db\Query())
			->select('gg.id as gid,gg.name as gamename,gp.type,gp.pid')
			->from(' g_game as gg')
			->leftJoin('g_playgameuser as gp','gg.id=gp.gid')
			->where(['gp.createtime'=>$date])
			->all();
		$userdataarr = array();//日活跃记录和日新增用户记录
		if($playuserarr){
			foreach($playuserarr as $playuser){ //分别封装日活跃记录和日新增记录，并统计
				if(!isset($userdataarr[$playuser['gid'].$playuser['pid']])){
					$playuser['play_user'] = 1;
					$playuser['new_user'] = 0;
					$playuser['old_user'] = 0;
					$playuser['pay_user'] = 0;
					$playuser['pay_sum'] = 0.00;
					$playuser['pay_num'] = 0;
					$playuser['cuser'] = 0;
					$playuser['cprice'] = 0.00;
					$playuser['count_time'] = strtotime(date('Y-m-d'));
					$userdataarr[$playuser['gid'].$playuser['pid']]=$playuser;
				}else{
					$userdataarr[$playuser['gid'].$playuser['pid']]['play_user']+=1;
				}
				if($playuser['type']==1){
					$userdataarr[$playuser['gid'].$playuser['pid']]['new_user']+=1;
				}else{
					$userdataarr[$playuser['gid'].$playuser['pid']]['old_user']+=1;
				}
			}
		}
		$newarr = array_values($this->mergearr($gameorder, $userdataarr)); //合并数组 
		$file = dirname(dirname(__FILE__)).'/runtime/autocount_log.txt';
		$myfile = fopen($file,'a+');//打开autocount.txt文件
		if($newarr){
			$a_count = count($newarr);
			$i = 0;
			$a = 0;
			foreach ($newarr as $v) {
				$count = new Count();;//克隆
				$count->gid = (int)$v['gid'];
				$count->pid = (int)$v['pid'];
				$count->count_time = $date;
				$count->gamename = $v['gamename'];
				$count->new_user = $v['new_user'];
				$count->old_user = $v['old_user'];
				$count->play_user = $v['play_user'];
				$count->pay_user = (int)$v['pay_user'];
				$count->pay_sum = $v['pay_sum']; //充值总流水
				$count->pay_probability = ($v['play_user']==0)?0 :$v['pay_user']/$v['play_user'];
				$count->pay_num = $v['pay_num'];
				$count->ARPU = ($v['play_user']==0)?0.00:$v['pay_sum']/$v['play_user'];
				$count->ARPPU = ($v['pay_user']==0)?0.00:$v['pay_sum']/$v['pay_user'];
				$count->cuser = $v['cuser'];
				$count->cprice = $v['cprice'];
				if($count->save()){
					$i++;
				}
			}
			if($i==$a_count){
				$text = date('Y-m-d H:i:s')."   导入了".$i."条数据\r\n";
			}else{
				$text = date('Y-m-d H:i:s').'   导入了'.$i.'条数据，有'.$a_count-$i."条数据没能成功导入数据库\r\n";
			}
		}else{
			$text = date('Y-m-d H:i:s')."   没有查询到数据\r\n";
		}
		fwrite($myfile, $text);
		fclose($myfile);
	}
	
	
	/**
	 * 月汇总统计
	 */
	public function actionMonthcount(){
		ini_set ('memory_limit', '300M');
		$date = strtotime(date('Y-m',strtotime('-1 day')));//昨天的时间
        $timestamp=strtotime(date("y-m-d"));
        $firstday=date('Y-m-01',strtotime(date('Y',$timestamp).'-'.(date('m',$timestamp)-1).'-01'));
        $lastday= date('Y-m-d',strtotime("$firstday +1 month -1 day"));
        $firsttime = strtotime($firstday);
        $lasttime = strtotime($lastday);
        
        
		//昨天的付费用户数和充值流水 
		$gameorder = \Yii::$app->db->createCommand("SELECT T.date,T.gid,T.gamename, T.pid,T.createtime as count_time,COUNT(*) AS pay_user,SUM(S_price) as pay_sum, pay_num FROM (SELECT date_format(date_sub(curdate(), interval 1 month),'%Y-%m') AS date,SUM(price) S_price,O.createtime,O.uid,O.gid,O.pid, G.name AS gamename,COUNT(distinct O.uid) as pay_num FROM g_game G LEFT JOIN g_order O ON G.id = O.gid WHERE O.state = 2 and FROM_UNIXTIME(O.createtime,'%Y-%m') = date_format(date_sub(curdate(), interval 1 month),'%Y-%m') GROUP BY O.uid,O.pid) T GROUP BY T.gid,T.pid")->queryAll();//游戏的月支付数和支付金额
		if($gameorder){
			$gameorder1 = \Yii::$app->db->createCommand("SELECT COUNT(*) cuser,SUM(price) cprice,gid,pid FROM g_order where utype=2 and state=2 and FROM_UNIXTIME(createtime,'%Y-%m') = date_format(date_sub(curdate(), interval 1 month),'%Y-%m') group By gid,uid,pid")->queryAll();//查出每月新增用户数和新增金额总数
			foreach ($gameorder as $k => $v) { 
				$gameorder[$k]['cuser'] = 0;
				$gameorder[$k]['cprice'] = 0;
				if($gameorder1){
					foreach ($gameorder1 as $k1 => $v1) {
						if($v1['gid']==$v['gid'] && ($v1['pid']==$v['pid'])){
							$gameorder[$k]['cuser'] = $v1['cuser'];
							$gameorder[$k]['cprice'] = $v1['cprice'];
						}
					}
				}
			}
		}
		//上月游戏玩家记录
		$playuserarr = (new \yii\db\Query())
		->select('gg.id as gid,gg.name as gamename,gp.type,gp.pid')
		->from(' g_game as gg')
		->leftJoin('g_playgameuser as gp','gg.id=gp.gid') 
		->where("gp.createtime between $firsttime and $lasttime")
		->all();
		$userdataarr = array();//日活跃记录和日新增用户记录
		if($playuserarr){
			foreach($playuserarr as $playuser){ //分别封装日活跃记录和日新增记录，并统计
				if(!isset($userdataarr[$playuser['gid'].$playuser['pid']])){
					$playuser['play_user'] = 1;
					$playuser['new_user'] = 0;
					$playuser['old_user'] = 0;
					$playuser['pay_user'] = 0;
					$playuser['pay_sum'] = 0.00;
					$playuser['pay_num'] = 0;
					$playuser['cuser'] = 0;
					$playuser['cprice'] = 0.00;
					$playuser['count_time'] = strtotime(date('Y-m-d'));
					$userdataarr[$playuser['gid'].$playuser['pid']]=$playuser;
				}else{
					$userdataarr[$playuser['gid'].$playuser['pid']]['play_user']+=1;
				}
				if($playuser['type']==1){
					$userdataarr[$playuser['gid'].$playuser['pid']]['new_user']+=1;
				}else{
					$userdataarr[$playuser['gid'].$playuser['pid']]['old_user']+=1;
				}
			}
		}
		$newarr = array_values($this->mergearr($gameorder, $userdataarr)); //合并数组
		$file = dirname(dirname(__FILE__)).'/runtime/autocount_log.txt';
		$myfile = fopen($file,'a+');//打开autocount.txt文件
		if($newarr){
			$a_count = count($newarr);
			$i = 0;
			$a = 0;
			foreach ($newarr as $v) {
				$monthcount = new Monthcount();
				$monthcount->gid = (int)$v['gid'];
				$monthcount->pid = (int)$v['pid'];
				$monthcount->count_time = $date;
				$monthcount->gamename = $v['gamename'];
				$monthcount->new_user = $v['new_user'];
				$monthcount->old_user = $v['old_user'];
				$monthcount->play_user = $v['play_user'];
				$monthcount->pay_user = (int)$v['pay_user'];
				$monthcount->pay_sum = $v['pay_sum']; //充值总流水
				$monthcount->pay_probability = ($v['play_user']==0)?0 :$v['pay_user']/$v['play_user'];
				$monthcount->pay_num = $v['pay_num'];
				$monthcount->ARPU = ($v['play_user']==0)?0.00:$v['pay_sum']/$v['play_user'];
				$monthcount->ARPPU = ($v['pay_user']==0)?0.00:$v['pay_sum']/$v['pay_user'];
				$monthcount->cuser = $v['cuser'];
				$monthcount->cprice = $v['cprice'];
				if($monthcount->save()){
					$i++;
				}
			}
			if($i==$a_count){
				$text = date('Y-m-d H:i:s')."   导入了".$i."条数据\r\n";
			}else{
				$text = date('Y-m-d H:i:s').'   导入了'.$i.'条数据，有'.$a_count-$i."条数据没能成功导入数据库\r\n";
			}
		}else{
			$text = date('Y-m-d H:i:s')."   没有查询到数据\r\n";
		}
		fwrite($myfile, $text);
		fclose($myfile);
		
		
		
	}
	
	
	/**
	 *  每月自动统计
	 *  以pid（平台id）进行分组
	 *  gid 游戏id
	 *  pid 平台id
	 *  count_time 统计的日期
	 *  gamename 游戏名称
	 *  new_user 新增用户数
	 *  play_user 日活跃用户数 (激活数)
	 *  pay_user 总付费人数
	 *  pay_probability 付费率 （百分比的形式 %）
	 *  pay_sum 总付费金额
	 *  ARPU = 充值总流水/日活跃用户数 (元)
	 *  ARPPU = 即充值流水/付费用户数 （元）
	 *  cuser 新增付费人数
	 *  cprice 新增付费金额
	 *  old_user 老活跃用户数
	 *  pay_num 付费次数
	 *  @return [type] [description]
	 */
	public function actionTestmonthcount(){
		$timestamp=strtotime(date('Y-m'));
		$monthfirst=strtotime(date('Y-m-01',strtotime(date('Y',$timestamp).'-'.(date('m',$timestamp)-1).'-01')));//上月的第一天
		$monthlast=strtotime(date('Y-m-t',strtotime(date('Y',$timestamp).'-'.(date('m',$timestamp)-1).'-01')))+86400;//上月的最后一 天 
		//付费率和充值流水
		$gameorder =  \Yii::$app->db->createCommand("SELECT gid,pid,gamename, sum(new_user)as new_user,sum(play_user) as play_user ,sum(pay_sum),sum(pay_user) as pay_user,sum(cuser) as cuser,sum(cprice) as cprice ,sum(old_user) as old_user,sum(pay_sum) as pay_sum ,sum(pay_num) as pay_num FROM g_count WHERE count_time BETWEEN $monthfirst AND $monthlast GROUP BY pid,gid")->queryAll();
		if($gameorder){
			foreach ($gameorder as $g){
				$monthcount = new Monthcount();
				$monthcount->gid = $g['gid'];
				$monthcount->pid = $g['pid'];
				$monthcount->count_time =$monthfirst;
				$monthcount->gamename = $g['gamename'];
				$monthcount->new_user = $g['new_user'];
				$monthcount->play_user = $g['play_user'];
				$monthcount->pay_user = $g['pay_user'];
				$monthcount->pay_probability = round($g['pay_user']/$g['play_user'],2);
				$monthcount->pay_sum = $g['pay_sum'];
				$monthcount->ARPU = ($g['play_user'])? round($g['pay_sum']/$g['play_user'],2):0;
				$monthcount->ARPPU = ($g['pay_user'])?round($g['pay_sum']/$g['pay_user'],2):0;
				$monthcount->cuser = $g['cuser'];
				$monthcount->cprice = $g['cprice'];
				$monthcount->old_user = $g['old_user'];
				$monthcount->pay_num = $g['pay_num'];
				$monthcount->save();
			}
		}
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
		  				if(($vg['gid']==$val['gid'])&&($vg['pid']==$val['pid'])){
		  					$gameorder[$kg]['new_user'] = $val['new_user'];
		  					$gameorder[$kg]['play_user'] = $val['play_user'];
		  					$gameorder[$kg]['old_user'] = $val['old_user'];
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
	 * 详情留存统计 自动统计
	 */
	public function actionRetain(){
		$date = strtotime(date('Y-m-d',strtotime('-7 day')));//7天前的时间
		//前7天游戏有效玩家记录数
		$playuserarr = (new \yii\db\Query())
		->select('gp.type,gp.createtime,gp.gid,g.name as gamename,gp.pid,gp.first_playtime')
		->from(' g_playgameuser as gp')
		->leftJoin('g_game as g','gp.gid=g.id')
		->where(['gp.first_playtime'=>$date])
		->groupBy('gp.uid,gp.gid,gp.pid,gp.createtime')
		->all();
		if($playuserarr){
			$data = array();
			foreach ($playuserarr as $pu){
				if(!isset($data[$pu['gid'].$pu['pid']])){
					$data[$pu['gid'].$pu['pid']]['_new'] = 0;
					$data[$pu['gid'].$pu['pid']]['_play'] = 0;
					$data[$pu['gid'].$pu['pid']]['gid'] = $pu['gid'];
					$data[$pu['gid'].$pu['pid']]['pid'] = $pu['pid'];
					$data[$pu['gid'].$pu['pid']]['gamename'] = $pu['gamename'];
					$data[$pu['gid'].$pu['pid']]['count_time'] = $pu['createtime'];
					$data[$pu['gid'].$pu['pid']]['data'] = ['2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0];
				}
				if($pu['first_playtime']==$pu['createtime']){//先统计7天前每款游戏的新增用户数
					$data[$pu['gid'].$pu['pid']]['_new']++;
				}
				if($pu['type']==1){//先每款游戏的激活数
					$data[$pu['gid'].$pu['pid']]['_play']++;
				}
				$_day = ($pu['createtime']-$pu['first_playtime'])/(3600*24)+1;//第几日
				(isset($data[$pu['gid'].$pu['pid']]['data'][$_day]))&&$data[$pu['gid'].$pu['pid']]['data'][$_day]++;//第几天的
			}
			$a_count = count($data);
			$retain = new Retain();
			$j = 0;
			$file = dirname(dirname(__FILE__)).'/runtime/autocount_retain.txt';
			$myfile = fopen($file,'a+');//打开autocount_retain.txt文件
			foreach ($data as $kd => $vd) {
				if($vd['_new']!=0){
					$_retain = clone $retain;
					$_retain->gid = $vd['gid'];
					$_retain->pid = $vd['pid'];
					$_retain->gamename = $vd['gamename'];
					$_retain->count_time = $vd['count_time'];
					$_retain->new_user = $vd['_new'];
					$_retain->play_user = $vd['_play'];
					$_data = $vd['data'];
					$_retain->second = $_data['2'].'（'.(round(($_data['2']/$vd['_new'])*100,2)).'）';
					$_retain->third = $_data['3'].'（'.(round(($_data['3']/$vd['_new'])*100,2)).'）';
					$_retain->fourth = $_data['4'].'（'.(round(($_data['4']/$vd['_new'])*100,2)).'）';
					$_retain->fifth = $_data['5'].'（'.(round(($_data['5']/$vd['_new'])*100,2)).'）';
					$_retain->sixth = $_data['6'].'（'.(round(($_data['6']/$vd['_new'])*100,2)).'）';
					$_retain->seventh = $_data['7'].'（'.(round(($_data['7']/$vd['_new'])*100,2)).'）';
					if($_retain->save()){
						$j++;
					}
				}
			}
			if($j==$a_count){
				$text = '详情留存统计 '.date('Y-m-d H:i:s')."   导入了".$j."条数据\r\n";
			}else{
				$text = '详情留存统计 '.date('Y-m-d H:i:s').'   导入了'.$j.'条数据，有'.$a_count-$j."条数据没能成功导入数据库\r\n";
			}
			fwrite($myfile, $text);
			fclose($myfile);
		}
	}
	
	/**
	 * 汇总留存统计 自动统计
	 */
	public function actionCretain(){
		$date = strtotime(date('Y-m-d',strtotime('-7 day')));//7天前的时间
		//前7天游戏有效玩家记录数
		$puarr = (new \yii\db\Query())
		->select('createtime,pid,first_time,createtime,type')
		->from(' g_playgameuser')
		->where(['first_time'=>$date])
		->groupBy('pid,createtime,uid')
		->all();
		if($puarr){
			$data = array();
			foreach ($puarr as $pu) {
				if(!isset($data[$pu['pid']])){
					$data[$pu['pid']]['_new'] = 0;
					$data[$pu['pid']]['_play'] = 0;
					$data[$pu['pid']]['pid'] = $pu['pid'];
					$data[$pu['pid']]['count_time'] = $pu['createtime'];
					$data[$pu['pid']]['data'] = ['2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0];
				}
				if($pu['first_time']==$pu['createtime']){//先统计7天前每款游戏的新增用户数
					$data[$pu['pid']]['_new']++;
				}
				if($pu['type']==1){//先每款游戏的激活数
					$data[$pu['pid']]['_play']++;
				}
				$_day = ($pu['createtime']-$pu['first_time'])/(3600*24)+1;//第几日
				(isset($data[$pu['pid']]['data'][$_day]))&&$data[$pu['pid']]['data'][$_day]++;//第几天的
			}
			$a_count = count($data);
			$retain = new Cretain();
			$j = 0;
			$file = dirname(dirname(__FILE__)).'/runtime/autocount_retain.txt';
			$myfile = fopen($file,'a+');//打开autocount_retain.txt文件
			foreach ($data as $kd => $vd) {
				if($vd['_new']!=0){//新增用户为0时，不存进留存表
					$_retain = clone $retain;
					$_retain->pid = $vd['pid'];
					$_retain->count_time = $vd['count_time'];
					$_retain->new_user = $vd['_new'];
					$_retain->play_user = $vd['_play'];
					$_data = $vd['data'];
					$_retain->second = $_data['2'].'（'.(round(($_data['2']/$vd['_new'])*100,2)).'）';
					$_retain->third = $_data['3'].'（'.(round(($_data['3']/$vd['_new'])*100,2)).'）';
					$_retain->fourth = $_data['4'].'（'.(round(($_data['4']/$vd['_new'])*100,2)).'）';
					$_retain->fifth = $_data['5'].'（'.(round(($_data['5']/$vd['_new'])*100,2)).'）';
					$_retain->sixth = $_data['6'].'（'.(round(($_data['6']/$vd['_new'])*100,2)).'）';
					$_retain->seventh = $_data['7'].'（'.(round(($_data['7']/$vd['_new'])*100,2)).'）';
					if($_retain->save()){
						$j++;
					}
				}
			}
			if($j==$a_count){
				$text = '汇总留存统计 '.date('Y-m-d H:i:s')."   导入了".$j."条数据\r\n";
			}else{
				$text = '汇总留存统计 '.date('Y-m-d H:i:s').'   导入了'.$j.'条数据，有'.$a_count-$j."条数据没能成功导入数据库\r\n";
			}
			fwrite($myfile, $text);
			fclose($myfile);
		}
	}
	
	
	/**
	 * 详情回收统计 自动统计
	 */
	public function actionRecycle(){
		$date1 = strtotime(date('Y-m-d'));
		$date = strtotime(date('Y-m-d',strtotime('-7 day')));//7天前的时间
		//前7天游戏有效玩家记录数
		$playuserarr = (new \yii\db\Query())
		->select('o.createtime,o.gid,g.name as gamename,o.pid,o.gfirst_time,price')
		->from(' g_order as o')
		->leftJoin('g_game as g','o.gid=g.id')
		->where(['between','o.gfirst_time',$date,$date1])
		// ->groupBy('o.uid,o.gid,o.pid,o.createtime')
		->all();
		if($playuserarr){
			$data = array();
			foreach ($playuserarr as $pu){
				if(!isset($data[$pu['gid'].$pu['pid']])){//创建每个平台下的每款游戏的信息
					$data[$pu['gid'].$pu['pid']]['_play'] = 0;
					$data[$pu['gid'].$pu['pid']]['gid'] = $pu['gid'];
					$data[$pu['gid'].$pu['pid']]['pid'] = $pu['pid'];
					$data[$pu['gid'].$pu['pid']]['gamename'] = $pu['gamename'];
					$data[$pu['gid'].$pu['pid']]['count_time'] = $pu['createtime'];
					$data[$pu['gid'].$pu['pid']]['data'] = ['1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0];
					$data[$pu['gid'].$pu['pid']]['price'] = ['1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0];
				}
				$data[$pu['gid'].$pu['pid']]['_play']++;//激活数
				$_day = ($pu['createtime']-$pu['gfirst_time'])/(3600*24)+1;//第几日
				if(isset($data[$pu['gid'].$pu['pid']]['data'][$_day])){
					$data[$pu['gid'].$pu['pid']]['data'][$_day]++;//第几天的
					$data[$pu['gid'].$pu['pid']]['price'][$_day]+=$pu['price'];//第几天的
				}
			}
			$a_count = count($data);
			$recycle = new Recycle();
			$j = 0;
			$file = dirname(dirname(__FILE__)).'/runtime/autocount_recycle.txt';
			$myfile = fopen($file,'a+');//打开autocount_retain.txt文件
			foreach ($data as $kd => $vd) {
				$_data = $vd['data'];
				if($_data['1']!=0){
					$_recycle = clone $recycle;
					$_recycle->gid = $vd['gid'];
					$_recycle->pid = $vd['pid'];
					$_recycle->gamename = $vd['gamename'];
					$_recycle->count_time = $vd['count_time'];
					$_recycle->pay_user = $_data['1'];
					$_recycle->second = $_data['2']+$_data['1'];
					$_recycle->third = $_data['3']+$_recycle->second;
					$_recycle->fourth = $_data['4']+$_recycle->third;
					$_recycle->fifth = $_data['5']+$_recycle->fourth;
					$_recycle->sixth = $_data['6']+$_recycle->fifth;
					$_recycle->seventh = $_data['7']+$_recycle->sixth;
					$_price = $vd['price'];
					$_recycle->price = $_price['1'];
					$_recycle->psecond = $_price['2']+$_price['1'];
					$_recycle->pthird = $_price['3']+$_recycle->psecond;
					$_recycle->pfourth = $_price['4']+$_recycle->pthird;
					$_recycle->pfifth = $_price['5']+$_recycle->pfourth;
					$_recycle->psixth = $_price['6']+$_recycle->pfifth;
					$_recycle->pseventh = $_price['7']+$_recycle->psixth;
					if($_recycle->save()){
						$j++;
					}
				}
			}
			if($j==$a_count){
				$text = '汇总收回统计 '.date('Y-m-d H:i:s')."   导入了".$j."条数据\r\n";
			}else{
				$text = '汇总收回统计 '.date('Y-m-d H:i:s').'   导入了'.$j.'条数据，有'.$a_count-$j."条数据没能成功导入数据库\r\n";
			}
		}
	}
	
	/**
	 * 汇总回收统计 自动统计
	 */
	public function actionCrecycle(){
		$date1 = strtotime(date('Y-m-d'));
		$date = strtotime(date('Y-m-d',strtotime('-7 day')));//7天前的时间
		$play = Playgameuser::find()->where(['state'=>1,'createtime'=>$date])->select('pid,createtime')->asArray()->all();
		if($play){
			$newarr = array();
			foreach ($play as $kp => $vp) {
				if(!isset($newarr[$vp['pid']])){//计算出激活数
					$newarr[$vp['pid']]['pid'] = $vp['pid'];
					$newarr[$vp['pid']]['data'] = [1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0];
					$newarr[$vp['pid']]['price'] = [1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0];
					$newarr[$vp['pid']]['play'] = 1;
				}else{
					$newarr[$vp['pid']]['play']++;
				}
			}
			//前7天游戏有效玩家记录数
			$puarr = \Yii::$app->db->createCommand("SELECT uid,price,createtime,pid,first_time,FROM_UNIXTIME(createtime,'%Y-%m-%d') AS daytime from g_order where createtime BETWEEN :date0 AND :date1 and state=2 order By pid",[':date0'=>$date,':date1'=>$date1])->queryAll();
			if($puarr){
				$data = array();
				$arrtest = array();
				foreach ($puarr as $pu) {
					$daytime = strtotime($pu['daytime']);
					if(!isset($data[$pu['pid'].$daytime])){
						$data[$pu['pid'].$daytime]['pid'] = $pu['pid'];
						$data[$pu['pid'].$daytime]['count_time'] = $pu['daytime'];
						$data[$pu['pid'].$daytime]['data'] = 0;
						$data[$pu['pid'].$daytime]['price'] = 0;
					}
					$_day = ($daytime-$pu['first_time'])/(3600*24)+1;//第几日
					if(!isset($arrtest[$pu['uid'].'%#'.$_day])){
						$arrtest[$pu['uid'].'%#'.$_day] = $pu['uid'];
						$data[$pu['pid'].$daytime]['data']++;//第几天的
					}
					$data[$pu['pid'].$daytime]['price'] += $pu['price'];//第几天的
				}
				foreach ($data as $key => $val) {	
					$daytime = strtotime($val['count_time']);			
					$_day = intval(($daytime-$date)/(3600*24))+1;
					if($_day>0 && $_day<8){
						if(isset($newarr[$val['pid']])){
							$newarr[$val['pid']]['data'][$_day]+=$val['data'];
							$newarr[$val['pid']]['price'][$_day]+=$val['price'];
						}
					}
				}
				$a_count = count($newarr);
				$recycle = new Crecycle();
				$j = 0;
				$file = dirname(dirname(__FILE__)).'/runtime/autocount_recycle.txt';
				$myfile = fopen($file,'a+');//打开autocount_retain.txt文件
				foreach ($newarr as $kd => $vd) {
					$_data = $vd['data'];
					if($_data['1']!=0){
						$_recycle = clone $recycle;
						$_recycle->pid = $vd['pid'];
						$_recycle->count_time = $date;
						$_recycle->play_user = $vd['play'];
						$_recycle->pay_user = $_data['1'];
						$_recycle->second = $_data['2']+$_data['1'];
						$_recycle->third = $_data['3']+$_recycle->second;
						$_recycle->fourth = $_data['4']+$_recycle->third;
						$_recycle->fifth = $_data['5']+$_recycle->fourth;
						$_recycle->sixth = $_data['6']+$_recycle->fifth;
						$_recycle->seventh = $_data['7']+$_recycle->sixth;
						$_price = $vd['price'];
						$_recycle->price = $_price['1'];
						$_recycle->psecond = $_price['2']+$_price['1'];
						$_recycle->pthird = $_price['3']+$_recycle->psecond;
						$_recycle->pfourth = $_price['4']+$_recycle->pthird;
						$_recycle->pfifth = $_price['5']+$_recycle->pfourth;
						$_recycle->psixth = $_price['6']+$_recycle->pfifth;
						$_recycle->pseventh = $_price['7']+$_recycle->psixth;
						if($_recycle->save()){
							$j++;
						}
					}
				}
				if($j==$a_count){
					$text = '汇总回收统计 '.date('Y-m-d H:i:s')."   导入了".$j."条数据\r\n";
				}else{
					$text = '汇总回收统计 '.date('Y-m-d H:i:s').'   导入了'.$j.'条数据，有'.$a_count-$j."条数据没能成功导入数据库\r\n";
				}
				fwrite($myfile, $text);
				fclose($myfile);
			}
		}
	}
	
	
	/**
	 * 每日汇总持续付费自动统计
	 */
	public function actionAutocontorder(){
		$time = strtotime(date('Y-m-d',strtotime('-7 days')));  //七天前的时间戳
		$dataarr = array();
		//查出七天前一天的各个平台的统计数据
		$newarr    =  Count::find()
		->where(['count_time'=>$time])
		->select('count_time,sum(play_user) as play_user,pid')
		->groupBy('pid,count_time')
		->orderBy('count_time desc')
		->asArray()
		->all();
		if($newarr){
			//查出六天内该平台的付款成功的数据
			$orderuserarr = (new \yii\db\Query())
			->select('go.id,go.utype,go.gtype,go.first_time,go.gfirst_time,go.createtime,go.price,go.pid')
			->from(' g_order as go')
			->where(['state'=>2,'go.first_time'=>$time])
			->andWhere("go.first_time")
			->groupBy('go.pid,go.uid,go.createtime')
			->all();
			if($orderuserarr){
				//print_r($orderuserarr);
				foreach ($orderuserarr as $k=>$ou){
					$createtime =  strtotime(date('Y-m-d',$ou['createtime'])); //付款时间去掉时分秒
					$orderuserarr[$k]['createtime'] = $createtime;
					$numdataarr [$ou['pid']][$createtime] = isset($numdataarr[$ou['pid']][$createtime])? $numdataarr[$ou['pid']][$createtime]+1 :1;   //激活数
					if($ou['utype']==1){ //新增用户
						$newnumarr[$ou['pid']][$createtime] = isset($newnumarr[$ou['pid']][$createtime]) ?$newnumarr[$ou['pid']][$createtime] +1:1;  //新增用户数
					}
					$pricedataarr[$ou['pid']][$createtime] = isset($numdataarr[$ou['pid']][$createtime])?$numdataarr[$ou['pid']][$createtime]+$ou['price']:$ou['price'];  //当日付款金额
				}
				foreach ($newarr as $k1 => $v1) {
					$dataarr[$v1['pid']]['play_user'] = $v1['play_user'];
					$dataarr[$v1['pid']]['pid'] = $v1['pid'];
					$dataarr[$v1['pid']]['count_time'] = $v1['count_time'];   //日期
					$dataarr[$v1['pid']]['new_user'] = 0;         //当日付费人数
					$dataarr[$v1['pid']]['pay_price'] = 0;        //当日支付金额
					$dataarr[$v1['pid']]['retain'] = ['2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0];  //持续支付数
					$dataarr[$v1['pid']]['price'] = ['2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0];   //持续支付金额
					if($orderuserarr){
						foreach ($orderuserarr as $key=>$ouser){
							if(($v1['count_time']==$ouser['first_time'])&&($ouser['first_time']!=$ouser['createtime'])){
								$dataarr[$v1['pid']]['new_user'] = isset($newnumarr[$v1['pid']][$ouser['first_time']])?$newnumarr[$v1['pid']][$ouser['first_time']]:0;
								$dataarr[$v1['pid']]['pay_price'] = isset($pricedataarr[$v1['pid']][$ouser['first_time']])?$pricedataarr[$v1['pid']][$ouser['first_time']]:0;
								$num = ($ouser['createtime']-$ouser['first_time'])/(3600*24)+1;//第几日
								($num>1) && $dataarr[$v1['pid']]['retain'][$num] = (isset($numdataarr[$v1['pid']][$ouser['createtime']]))? $numdataarr[$v1['pid']][$ouser['createtime']]:0;
								($num>1) && $dataarr[$v1['pid']]['price'][$num] = isset($pricedataarr[$v1['pid']][$ouser['createtime']])?$pricedataarr[$v1['pid']][$ouser['createtime']]:0;
							}
						}
					}
				}
			}
			if($dataarr){
				$j = 0;
				$a_count = count($dataarr);
				$file = dirname(dirname(__FILE__)).'/runtime/autocount_countinuorder.txt';
				$myfile = fopen($file,'a+');//打开autocount_retain.txt文件
				foreach ($dataarr as $n){
					$continuorder = new Continuorder();
					$continuorder->pid =  $n['pid'];
					$continuorder->play_user = $n['play_user'];
					$continuorder->pay_user = isset($n['new_user'])?$n['new_user'] : 0;
					$continuorder->second = isset($n['retain'][2])?$n['retain'][2] : 0;
					$continuorder->third = isset($n['retain'][3])?$n['retain'][3] : 0;
					$continuorder->fourth = isset($n['retain'][4])?$n['retain'][4] : 0;
					$continuorder->fifth = isset($n['retain'][5])?$n['retain'][5] : 0;
					$continuorder->sixth = isset($n['retain'][6])?$n['retain'][6] : 0;
					$continuorder->seventh = isset($n['retain'][7])?$n['retain'][7] : 0;
					$continuorder->pay_price = isset($n['pay_price'])?$n['pay_price'] : 0;
					$continuorder->secondprice = isset($n['price'][2])?$n['price'][2] : 0;
					$continuorder->thirdprice = isset($n['price'][3])?$n['price'][3] : 0;
					$continuorder->fourthprice = isset($n['price'][4])?$n['price'][4] : 0;
					$continuorder->fifthprice = isset($n['price'][5])?$n['price'][5] : 0;
					$continuorder->sixthprice = isset($n['price'][6])?$n['price'][6] : 0;
					$continuorder->seventhprice = isset($n['price'][7])?$n['price'][7] : 0;
					$continuorder->count_time = isset($n['count_time'])?$n['count_time'] : 0;
					if($continuorder->save()){
						$j++;
					}
				}
				if($j==$a_count){
					$text = '详情留存统计 '.date('Y-m-d H:i:s')."   导入了".$j."条数据\r\n";
				}else{
					$text = '详情留存统计 '.date('Y-m-d H:i:s').'   导入了'.$j.'条数据，有'.$a_count-$j."条数据没能成功导入数据库\r\n";
				}
				fwrite($myfile, $text);
				fclose($myfile);
			}
	
		}
	}
	
	
	/**
	 * 每日详情自动统计
	 */
	public function actionAutodetcontorder(){
		$date = strtotime(date('Y-m-d',strtotime('-7 day')))-86400*3;//7天前的时间
		//查出七天前一天的各个平台的统计数据
		$newarr    = Count::find()
		->where(['count_time'=>$date])
		->select('count_time,sum(play_user) as play_user,pid,gid,gamename')
		->groupBy('gid,count_time')
		->orderBy('count_time desc')
		->asArray()
		->all();
		if($newarr){
			//前7天游戏有效玩家记录数 同一个用户在同一天充值则只记为一次
			$orderuserarr = \Yii::$app->db->createCommand("SELECT go.propname,go.id,go.utype,go.gtype,go.first_time,go.gfirst_time,go.createtime ,go.price,go.gid,g.name FROM g_order as go LEFT JOIN g_game as g ON go.gid=g.id WHERE go.state=2 AND gfirst_time = :createtime GROUP BY go.gid,go.uid,FROM_UNIXTIME(go.createtime,'%Y-%m-%d')",[':createtime'=>$date])->queryAll();
			if($orderuserarr){
				foreach ($orderuserarr as $k=>$ou){
					$createtime =  strtotime(date('Y-m-d',$ou['createtime'])); //付款时间去掉时分秒
					$orderuserarr[$k]['createtime'] =  $createtime;  //付款时间去掉时分秒
					$numdataarr[$ou['gid']][$createtime] = isset($numdataarr[$ou['gid']][$createtime])? $numdataarr[$ou['gid']][$createtime]+1 :1;   //激活数
					if($ou['gtype']==1){ //下单时间等于第一个付款时间则为新增用户
						$newnumarr[$ou['gid']][$createtime] = isset($newnumarr[$ou['gid']][$createtime])?$newnumarr[$ou['gid']][$createtime]+1:1;  //新增用户数
					}
					$pricedataarr[$ou['gid']][$createtime] = isset($numdataarr[$ou['gid']][$createtime])?$numdataarr[$ou['gid']][$createtime]+$ou['price']:$ou['price'];  //当日付款金额
				}
				foreach ($newarr as $k1 => $v1) {
					$newarr[$k1]['gid'] = $v1['gid'];              //游戏id
					$newarr[$k1]['gamename'] = $v1['gamename'];    //游戏名称
					//$newarr[$k1]['count_time'] = date('Y-m-d',$v1['count_time']);   //日期
					$newarr[$k1]['new_user'] = 0;         //激活数
					$newarr[$k1]['pay_price'] = 0;        //当日支付金额
					$newarr[$k1]['retain'] = ['2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0];  //持续支付数
					$newarr[$k1]['price'] = ['2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0];   //持续支付金额
					foreach ($orderuserarr as $key=>$ouser){
						if(($v1['count_time']==$ouser['gfirst_time'])&&($ouser['gfirst_time']!=$ouser['createtime'])){
							$newarr[$k1]['new_user'] = isset($newnumarr[$ouser['gid']][$ouser['createtime']])?$newnumarr[$ouser['gid']][$ouser['createtime']]:0;
							$newarr[$k1]['pay_price'] = isset($pricedataarr[$ouser['gid']][$ouser['createtime']])?$pricedataarr[$ouser['gid']][$ouser['createtime']]:0;
							$num = ($ouser['createtime']-$ouser['first_time'])/(3600*24)+1;//第几日
							($num>1 && $num<8) && $newarr[$k1]['retain'][$num] = (isset($numdataarr[$ouser['gid']][$ouser['createtime']]))? $numdataarr[$ouser['gid']][$ouser['createtime']]:0;
							($num>1 && $num<8) && $newarr[$k1]['price'][$num] = isset($pricedataarr[$ouser['gid']][$ouser['createtime']])?$pricedataarr[$ouser['gid']][$ouser['createtime']]:0;
						}
					}
				}
			}
			$a_count = count($newarr);
			$j = 0;
			$file = dirname(dirname(__FILE__)).'/runtime/autocount_decountinuorder.txt';
			$myfile = fopen($file,'a+');//打开autocount_retain.txt文件
			foreach ($newarr as $n){
				$decontinuorder = new Decontinuorder();
				$decontinuorder->gamename =  $n['gamename'];
				$decontinuorder->gid =  $n['gid'];
				$decontinuorder->pid =  $n['pid'];
				$decontinuorder->play_user = $n['play_user'];
				$decontinuorder->pay_user = isset($n['new_user'])?$n['new_user'] : 0;
				$decontinuorder->second = isset($n['retain'][2])?$n['retain'][2] : 0;
				$decontinuorder->third = isset($n['retain'][3])?$n['retain'][3] : 0;
				$decontinuorder->fourth = isset($n['retain'][4])?$n['retain'][4] : 0;
				$decontinuorder->fifth = isset($n['retain'][5])?$n['retain'][5] : 0;
				$decontinuorder->sixth = isset($n['retain'][6])?$n['retain'][6] : 0;
				$decontinuorder->seventh = isset($n['retain'][7])?$n['retain'][7] : 0;
				$decontinuorder->pay_price = isset($n['pay_price'])?$n['pay_price'] : 0;
				$decontinuorder->secondprice = isset($n['price'][2])?$n['price'][2] : 0;
				$decontinuorder->thirdprice = isset($n['price'][3])?$n['price'][3] : 0;
				$decontinuorder->fourthprice = isset($n['price'][4])?$n['price'][4] : 0;
				$decontinuorder->fifthprice = isset($n['price'][5])?$n['price'][5] : 0;
				$decontinuorder->sixthprice = isset($n['price'][6])?$n['price'][6] : 0;
				$decontinuorder->seventhprice = isset($n['price'][7])?$n['price'][7] : 0;
				$decontinuorder->count_time = isset($n['count_time'])?$n['count_time'] : 0;
				if($decontinuorder->save()){
					$j++;
				}
			}
			if($j==$a_count){
				$text = '汇总留存统计 '.date('Y-m-d H:i:s')."   导入了".$j."条数据\r\n";
			}else{
				$text = '汇总留存统计 '.date('Y-m-d H:i:s').'   导入了'.$j.'条数据，有'.$a_count-$j."条数据没能成功导入数据库\r\n";
			}
			fwrite($myfile, $text);
			fclose($myfile);
		}
	}
	
	/*
	 * 统计每天的下载次数
	*/
	public function actionAutodaydownload(){
		$starttime = strtotime(date('Y-m-d',strtotime('-1 day')));//昨天的时间
		$endtime = $starttime+86400-1;
		$download = Downloadrecord::find()->where(['between','createtime',$starttime,$endtime])->select('count(id) as num')->asArray()->one();
		$order = \Yii::$app->db->createCommand("SELECT sum(price) zprice,count(distinct uid) paynum from g_order where state=2 and payclient=3 and createtime between $starttime and $endtime")->queryOne();//总付费用户数和总付费金额(元)
		$daydownload = new Daydownload();
		$daydownload->createtime = $starttime;
		$daydownload->num = isset($download['num'])?$download['num']:0;
		$daydownload->pay_num = isset($order['paynum'])?$order['paynum']:0;
		$daydownload->pay_price =isset($order['zprice'])?$order['zprice']:0;
		if($daydownload->save()){
			Downloadrecord::deleteAll(['between','createtime',$starttime,$endtime]);  //删除昨天的记录
		}	
	}

	
	/**
	 * 删除三个月前用户玩游戏的数据
	 */
	public function actionAutodelplaygameuser(){
		$limttime = strtotime("-3 month");//三个月前的时间戳
		$res = Playgameuser::deleteAll(['<','createtime',$limttime]);
		echo "执行完毕";exit;
	}
	
	/**
	 * 删除两个个月开服记录
	 */
	public function actionAutodelnewservice(){
		$limttime = strtotime("-2 month");//2个月前的时间戳
		$res = Newservice::deleteAll(['<','createtime',$limttime]);
		echo "执行完毕";exit;
	}
}