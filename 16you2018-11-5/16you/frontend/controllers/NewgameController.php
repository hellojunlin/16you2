<?php 
namespace frontend\controllers;

use yii;
use frontend\controllers\BaseController;
use common\common\Helper;
use common\models\Game;
/**
 * 新游
 * @author junlin
 */
class NewgameController extends BaseController{
    //异步加载新的游戏
    public function actionGetnewgame(){
        if(!yii::$app->request->isAjax||!isset($_POST['page'])){
            return json_encode([
                    'errorcode'=>1000,
                    'info'=>'数据错误，请稍后再试',
            ]);
        }
        $cur = Helper::filtdata(yii::$app->request->post('page',1));
        $pageSize = 50;
        $game = Game::find()->where(['state'=>1,'is_newgame'=>1])->offset(($cur-1)*$pageSize)->limit($pageSize)->orderBy('sort DESC')->asArray()->all();
   		if($game){
	   			foreach ($game as $k=>$g){
	   				$game[$k]['label'] = json_decode($g['label']);
	   			}
	        	return json_encode([
	        			'errorcode'=>0,
	        			'info'=>$game
	        			]);
	    }else{
	        	$info =($cur>1)? '已加载所有新游戏':'暂时没有新游戏';
	        	return json_encode([
	        			'errorcode'=>1002,
	        			'info'=>$info,
	        			]);
	    }    
    }
    
    
    //异步加载新开服的游戏
    public function actionGetnewopen(){
    	if(!yii::$app->request->isAjax || !isset($_POST['page']) || !isset($_POST['type']) ){
    		return json_encode([
    				'errorcode'=>1000,
    				'info'=>'数据错误，请稍后再试',
    				]);
    	}
    	$cur = Helper::filtdata(yii::$app->request->post('page',1));
    	$type = Helper::filtdata(yii::$app->request->post('type',1));
    	$pageSize = 50;
    	$time = time();//现在时间戳
    	$ltime = strtotime(date('Y-m-d', strtotime('-7 days')));
    	$type==1 && $where = "gn.open_time between $ltime and $time";  //已开新服
    	$type==2 && $where = "gn.open_time>$time";  //新服预告
    	$type==1 && $orderby = "gn.open_time desc";  //已开新服
    	$type==2 && $orderby = "gn.open_time";  //新服预告
    	$nweservice =  (new yii\db\Query())
		    			->select('gg.name,gg.head_img,gn.gid,gn.service_code,gn.open_time')
		    	        ->from('g_newservice as gn')
		    	        ->leftJoin('g_game as gg','gn.gid = gg.id')
		    	        ->where(['gn.state'=>1])
		    	        ->andWhere($where)
		    			->offset(($cur-1)*$pageSize)
		    			->limit($pageSize)
		    			->orderBy($orderby)
		    			->all(); 
    	if($nweservice){
    			foreach ($nweservice as $k=>$n){
    				if($type==1){
	    				$str = '(已开服';
	    				$res = $this->timediff($time, $n['open_time']);
	    				($res['day']!=0) && $str .= $res['day'].'天'.$res['hour'].'小时';
	    				($res['day']==0 && $res['hour']!=0 ) && $str .= ($res['hour']==0)?'1小时':$res['hour'].'小时';
	    				($res['day']==0 && $res['hour']==0 && $res['min']!=0 ) && $str .= ($res['min']==0)?'1分':$res['min'].'分';
	    				$str .= ")";
	    				$nweservice[$k]['open_time'] = $str;
    				}else{
    					$str = '';
    					$datetime = strtotime(date('Y-m-d'));
    					if($datetime<$n['open_time'] && $n['open_time']<$datetime+86400){//今天的日期
    						$str .='(今日'.date('H:i',$n['open_time']).'开服)';
    					}else if($datetime+86400 <$n['open_time'] && $n['open_time']<$datetime+86400*2){//明天的日期
    						$str .='(明日'.date('H:i',$n['open_time']).'开服)';
    					}else{
    						$str .='('.date('m月d日',$n['open_time']).'开服)';
    					}
    					$nweservice[$k]['open_time'] = $str;
    				}
    			}
    		return json_encode([
    				'errorcode'=>0,
    				'info'=>$nweservice
    				]);
    	}else{
    		$info =($cur>1)? '已加载所有新游戏':'暂时没有新游戏';
    		return json_encode([
    				'errorcode'=>1002,
    				'info'=>$info,
    				]);
    	}
    }
    
    
    /**
     * 
     * @param unknown $begin_time
     * @param unknown $end_time
     * @return multitype:number
     */
    function timediff( $begin_time, $end_time )
    {
    	if ( $begin_time < $end_time ) {
    		$starttime = $begin_time;
    		$endtime = $end_time;
    	} else {
    		$starttime = $end_time;
    		$endtime = $begin_time;
    	}
    	$timediff = $endtime - $starttime;
    	$days = intval( $timediff / 86400 );
    	$remain = $timediff % 86400;
    	$hours = intval( $remain / 3600 );
    	$remain = $remain % 3600;
    	$mins = intval( $remain / 60 );
    	$secs = $remain % 60;
    	$res = array( "day" => $days, "hour" => $hours, "min" => $mins, "sec" => $secs );
    	return $res;
    }
}