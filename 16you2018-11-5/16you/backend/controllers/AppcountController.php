<?php
namespace backend\controllers;
use yii;
use backend\controllers\BaseController;
use common\models\Playgameuser;
use common\models\Plateform;
use common\models\Downloadrecord;
use common\common\Helper;
use yii\data\Pagination;
use common\models\Daydownload;

class AppcountController extends BaseController{
	//成功订单首页
	public function actionIndex() {
		//搜索
		$start_time = Yii:: $app->request->get('start_time');  //查询 开始时间
		$end_time = Yii:: $app->request->get('end_time');      //查询   结束时间
		$starttime = $start_time?strtotime($start_time):'';               //有查询时间则按该时间查询   反之则默认当天开始时间
		$endtime = $end_time?strtotime($end_time)+3600*24-1:strtotime(date('Y-m-d'))+3600*24-1;      //有查询时间则按该时间查询 反之则默认当天结束时间
		$istoday = true; //该字段用于判断是否查询今日数据
		if($endtime<strtotime(date('Y-m-d'))){//查询时间不包括今日
			$istoday = false;
		}
		$search ='';
		$starttime && $search = "createtime between $starttime and $endtime";
		//分页
		$curPage = Yii:: $app->request->get( 'page',1);
		$pageSize = yii::$app->params['pagenum'];
		//下载详情数据
		$query = Daydownload::find()->orderBy('createtime desc');
		$data = Helper::getPages($query,$curPage,$pageSize,$search);
		$data['data'] =  ($data['data'])?$data['data']->all():'';
		$pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
		//游戏总览
		$downloadnum = Downloadrecord::find()->where($search)->count(); //今天的下载次数
		!$downloadnum && $downloadnum=0;
		$todaynum =  $downloadnum;  //app今日下载次数
		$daydownloadnum = Daydownload::find()->select('sum(num) as num')->where($search)->asArray()->one(); //之前日期的下载数
		$daydownloadnum && $downloadnum = $downloadnum+$daydownloadnum['num'];    //计算总数
		$search && $search = "and createtime between $starttime and $endtime";
		$zcount = \Yii::$app->db->createCommand("SELECT sum(price) zprice,count(distinct uid) zuid,FROM_UNIXTIME(createtime, '%Y-%m-%d') createtime from g_order where payclient=3 and state=2 $search GROUP BY FROM_UNIXTIME(createtime, '%Y-%m-%d'); ")->queryAll();//总付费用户数和总付费金额(元) 按照每天分组并去重
		$payarr = array(); //记录付费人数及付费金额
		$payarr['downloadnum'] = $downloadnum;
		$todaypayarr = array(); //记录今日下载数、今日下载时间、今日付费人数及付费金额
		$todaypayarr['downloadnum'] = $todaynum; //今日下载数
		$todaypayarr['downloaddate'] =  strtotime(date('Y-m-d')); //今日下载时间
		if($zcount){
			foreach ($zcount as $z){
				  if(strtotime($z['createtime'])==strtotime(date('Y-m-d'))){//今日数据
				  	   $todaypayarr['paynum'] = isset( $todaypayarr['paynum'])? $todaypayarr['paynum']+$z['zuid']:$z['zuid']; //今日下载用户数
				  	   $todaypayarr['payprice'] = isset( $todaypayarr['payprice'])? $todaypayarr['payprice']+$z['zprice']:$z['zprice'];//今日下载金额
				  }
				  $payarr['paynum'] = isset($payarr['paynum'])?$payarr['paynum']+$z['zuid']:$z['zuid'];//下载用户数
				  $payarr['payprice'] = isset($payarr['payprice'])?$payarr['payprice']+$z['zprice']:$z['zprice'];//下载金额
			}
		}
		//菜单定位
		unset(yii::$app->session['localfirsturl']);
		yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/appcount/index.html';
		return $this->render('appcount', [
				'data'=>$data,
				'pages' => $pages,
				'start_time'=>$start_time,
				'end_time'=>$end_time,
				'payarr'=>$payarr,
				'todaypayarr'=>$todaypayarr,
				'istoday'=>$istoday,
				]);
	}
}