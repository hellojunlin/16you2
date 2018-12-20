<?php
namespace backend\controllers;

use yii;
use yii\web\Controller;
use common\models\Touristcount;
use common\models\Game;
use common\models\Touristmonthcount;
 

class TautocountController extends Controller{

	/**
	 *  游客每天自动统计
	 *  已pid（平台id）进行分组
	 *  gid 游戏id
	 *  pid 平台id
	 *  count_time 统计的日期
	 *  gamename 游戏名称
	 *  play_user 日活跃用户数 (激活数) 
	 *  pay_user 总付费人数
	 *  pay_probability 付费率 （百分比的形式 %）
	 *  pay_sum 总付费金额
	 *  ARPU = 充值总流水/日活跃用户数 (元)
	 *  ARPPU = 即充值流水/付费用户数 （元）
	 *  pay_num 付费次数
	 *  @return [type] [description]
	 */
	public function actionTouristdaycount(){
		$date = strtotime(date('Y-m-d',strtotime('-1 day')));//昨天的时间
		//昨天的付费用户数和充值流水
		$gameorder = \Yii::$app->db->createCommand("SELECT T.date,T.gid,T.gamename, T.pid,T.createtime as count_time,COUNT(*) AS pay_user,SUM(S_price) as pay_sum,SUM(pay_num) AS pay_num FROM (SELECT date_sub(curdate(),interval 1 day) AS date,SUM(price) S_price,O.createtime,O.uid,O.gid,O.pid, G.name AS gamename,COUNT(O.id) as pay_num FROM g_game G LEFT JOIN g_order O ON G.id = O.gid WHERE O.logintype=2 and O.state = 2 and FROM_UNIXTIME(O.createtime,'%Y-%m-%d') = date_sub(curdate(),interval 1 day) GROUP BY O.uid,O.gid,O.pid) T GROUP BY T.gid,T.pid")->queryAll();
		//昨天游戏玩家记录
		$playuserarr = (new \yii\db\Query())
			->select('gg.id as gid,gg.name as gamename,gp.type,gp.pid')
			->from(' g_game as gg')
			->leftJoin('g_touristplaygameuser as gp','gg.id=gp.gid')
			->where(['gp.createtime'=>$date])
			->all();
		$userdataarr = array();//日活跃记录和日新增用户记录
		if($playuserarr){
			foreach($playuserarr as $playuser){ //分别封装日活跃记录和日新增记录，并统计
				if(!isset($userdataarr[$playuser['gid'].$playuser['pid']])){
					$playuser['play_user'] = 1;
					$playuser['pay_user'] = 0;
					$playuser['pay_sum'] = 0.00;
					$playuser['pay_num'] = 0;
					$playuser['count_time'] = strtotime(date('Y-m-d'));
					$userdataarr[$playuser['gid'].$playuser['pid']]=$playuser;
				}else{
					$userdataarr[$playuser['gid'].$playuser['pid']]['play_user']+=1;
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
				$count = new Touristcount();;//克隆
				$count->gid = (int)$v['gid'];
				$count->pid = (int)$v['pid'];
				$count->count_time = $date;
				$count->gamename = $v['gamename'];
				$count->play_user = $v['play_user'];
				$count->pay_user = (int)$v['pay_user'];
				$count->pay_sum = $v['pay_sum']; //充值总流水
				$count->pay_probability = ($v['play_user']==0)?0 :$v['pay_user']/$v['play_user'];
				$count->pay_num = $v['pay_num'];
				$count->ARPU = ($v['play_user']==0)?0.00:$v['pay_sum']/$v['play_user'];
				$count->ARPPU = ($v['pay_user']==0)?0.00:$v['pay_sum']/$v['pay_user'];
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
	 * 游客月汇总统计
	 */
	public function actionTouristmonthcount(){
		$date = strtotime(date('Y-m',strtotime('-1 day')));//昨天的时间
        $timestamp=strtotime(date("y-m-d"));
        $firstday=date('Y-m-01',strtotime(date('Y',$timestamp).'-'.(date('m',$timestamp)-1).'-01'));
        $lastday= date('Y-m-d',strtotime("$firstday +1 month -1 day"));
        $firsttime = strtotime($firstday);
        $lasttime = strtotime($lastday);
		//上月的付费用户数和充值流水 
		$gameorder = \Yii::$app->db->createCommand("SELECT T.date,T.gid,T.gamename, T.pid,T.createtime as count_time,COUNT(*) AS pay_user,SUM(S_price) as pay_sum, pay_num FROM (SELECT date_format(date_sub(curdate(), interval 1 month),'%Y-%m') AS date,SUM(price) S_price,O.createtime,O.uid,O.gid,O.pid, G.name AS gamename,COUNT(distinct O.uid) as pay_num FROM g_game G LEFT JOIN g_order O ON G.id = O.gid WHERE logintype=2 and O.state = 2 and FROM_UNIXTIME(O.createtime,'%Y-%m') = date_format(date_sub(curdate(), interval 1 month),'%Y-%m') GROUP BY O.uid,O.pid) T GROUP BY T.gid,T.pid")->queryAll();//游戏的月支付数和支付金额
		//上月游戏玩家记录
		$playuserarr = (new \yii\db\Query())
		->select('gg.id as gid,gg.name as gamename,gp.type,gp.pid')
		->from(' g_game as gg')
		->leftJoin('g_touristplaygameuser as gp','gg.id=gp.gid') 
		->where("gp.createtime between $firsttime and $lasttime")
		->all();
		$userdataarr = array();//日活跃记录和日新增用户记录
		if($playuserarr){
			foreach($playuserarr as $playuser){ //分别封装日活跃记录和日新增记录，并统计
				if(!isset($userdataarr[$playuser['gid'].$playuser['pid']])){
					$playuser['play_user'] = 1;
					$playuser['pay_user'] = 0;
					$playuser['pay_sum'] = 0.00;
					$playuser['pay_num'] = 0;
					$playuser['count_time'] = strtotime(date('Y-m-d'));
					$userdataarr[$playuser['gid'].$playuser['pid']]=$playuser;
				}else{
					$userdataarr[$playuser['gid'].$playuser['pid']]['play_user']+=1;
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
				$monthcount = new Touristmonthcount();
				$monthcount->gid = (int)$v['gid'];
				$monthcount->pid = (int)$v['pid'];
				$monthcount->count_time = $date;
				$monthcount->gamename = $v['gamename'];
				$monthcount->play_user = $v['play_user'];
				$monthcount->pay_user = (int)$v['pay_user'];
				$monthcount->pay_sum = $v['pay_sum']; //充值总流水
				$monthcount->pay_probability = ($v['play_user']==0)?0 :$v['pay_user']/$v['play_user'];
				$monthcount->pay_num = $v['pay_num'];
				$monthcount->ARPU = ($v['play_user']==0)?0.00:$v['pay_sum']/$v['play_user'];
				$monthcount->ARPPU = ($v['pay_user']==0)?0.00:$v['pay_sum']/$v['pay_user'];
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
	
}