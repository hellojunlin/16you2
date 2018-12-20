<?php 
namespace backend\controllers;

use yii;
use backend\controllers\BaseController;
use common\models\Exchange;
use common\common\Helper;
use yii\data\Pagination;

class ExchangeController extends BaseController{

    //兑换首页
    public function actionIndex() { 
        //分页
        $curPage = Helper::filtdata(Yii:: $app->request->get( 'page',1));
		$pageSize = 80;
        //搜索
        $value = Helper::filtdata(Yii:: $app->request->get('value',''));  
        $getcode = Helper::filtdata(Yii:: $app->request->get('getcode',''));
        $search = ($value)?['like','gu.username',$value]: '';
        $search1 = ($getcode)?['ge.getcode'=>$getcode]: '';
        $query = (new \yii\db\Query())
                ->select('ge.id,ge.product_name,ge.integral,gu.username,ge.createtime,ge.getcode,ge.isdispose')
                ->from('g_exchange AS ge')
                ->leftJoin('g_user AS gu','gu.id = ge.uid')
                ->orderBy('ge.createtime desc');
        $search1 && $query->andWhere($search1);
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        if($data['data']){
        	$data['data'] = $data['data']->all();
        } 
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/exchange/index.html';
        return $this->render('index', [
             'data' => $data,
             'pages' => $pages,
             'value' => $value,
        	'getcode'=>$getcode,
        ]);
    }  

    //详情
    public function actionToedit(){
        if(!isset($_GET['id'])){
            exit;
        }
        $id = Helper::filtdata($_GET['id'],'INT');
        $model = (new \yii\db\Query())
                ->select('ge.id,ge.product_name,ge.integral,gu.username,ge.createtime,gu.head_url,ge.phone,ge.area')
                ->from('g_exchange AS ge')
                ->leftJoin('g_user AS gu','gu.id = ge.uid')
                ->where(['ge.id'=>$id])
                ->one();
        if($model){
            return $this->render('edit',[
                'model'=>$model,
                ]);
        }
    }

        /**
     * 兑换统计页面
     * 折线图数据
     */
    public function actionTocount() {
        $qyear = substr(date("Y-m-d"),0,4); //当前年份
        $qmonth = substr(date("Y-m-d"),5,2);//当前月份
        if(isset($_GET['time'])){
            $qyear = htmlspecialchars(trim(($_GET['time'])));
            $qmonth = isset($_GET['time1'])?htmlspecialchars(trim(($_GET['time1']))):$qmonth;
            $time = $qyear.'-'.$qmonth;
        }else{
            $time = date('Y-m');      
            $qyear = substr($time,0,4);  //查询的年份
            $qmonth = substr($time,5,2); //查询的月份
        }
        $time1 = strtotime($time);
        $months1 = strtotime("+1months",$time1);
        $exchange = Exchange::find()->where("createtime between $time1 and $months1");
        $re_data = array();
        $re_count = $exchange->count();
        if($re_count){
            $ceil = 1;
            if($re_count >50000){//每次只查50000条数据
                $ceil = ceil($re_count/50000);
            }
            for ($i=1; $i <= $ceil; $i++) { 
                $arr = $exchange->select('createtime')
                    ->offset(($i-1)*50000)->limit(50000)
                    ->asArray()->all();
                $re_data = array_merge($arr,$re_data);//合并数组
            }
        }
        $reday = array();//记录当天的兑换 key=>天 value=>兑换数
        $darr = array();//显示天数1-31天
        for($index=1;$index<=31;$index++){
            $darr[]=$index;
            $reday[$index] = 0;//$index=>天  value=>兑换数
        } 
        $darr = implode(',',$darr); //数组转字符串
        $dayarr=array();//统计所有兑换数
        $valarr=array();//统计每天的兑换
        foreach ($re_data as $vc){
            $createtime = date('Y-m-d',$vc['createtime']);
            $cr_day = substr($createtime,8,2);
            if($cr_day<10){
                $cr_day = substr($cr_day,1,1);
            }
            if(substr($createtime,0,4) == $qyear){  //查询的年份
                if(substr($createtime,5,2) == $qmonth){//查询的月份
                    $valarr[] = $cr_day;
                    $arr = substr($createtime,8,2);//获取具体的日期
                    if($arr<10){
                        $arr = substr($arr,1,1);
                    }
                    $dayarr[] = $arr;
                }
            }
        }
        $valdat = array_replace($reday,array_count_values ($valarr));
        $val_s['valdata'] = implode(',',$valdat);
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/exchange/tocount.html';
        return $this->render('count',[
            'valdata'=>$val_s,
            'darr'=>$darr,
            'time'=>$qyear,
            'time1'=>$qmonth
        ]);
    }
    
    /**
     * 代金券记录
     */
    public function actionVoucher(){
    	//分页
    	$curPage = Helper::filtdata(Yii:: $app->request->get( 'page',1));
    	$pageSize = 80;
    	//搜索
    	$value = Helper::filtdata(Yii:: $app->request->get('value',''));
    	$transaction_id = Helper::filtdata(Yii:: $app->request->get('transaction_id',''));
    	$state = Helper::filtdata(Yii:: $app->request->get('state',''));
    	$start_time = Yii:: $app->request->get('start_time');
    	$end_time = Yii:: $app->request->get('end_time');
    	$starttime = $start_time?strtotime($start_time):strtotime(date('Y-m-d'));
    	$endtime = $end_time?strtotime($end_time)+3600*24:strtotime(date('Y-m-d'))+3600*24;
    	$search = ($value)?['gu.Unique_ID'=>$value]: '';
    	$search1 = ($transaction_id)?['gv.transaction_id'=>$transaction_id]: '';
    	$search2 = ($state)?['gv.state'=>$state]: '';
    	$query = (new \yii\db\Query())
    	->select('gv.id,gv.price,gv.discount,gv.currencynum,gv.state,gv.pid,gv.transaction_id,gv.ptype,gv.payclient,gv.vtype,gv.createtime,gu.username,gu.head_url,gu.Unique_ID')
    	->from('g_voucher AS gv')
    	->leftJoin('g_user AS gu','gu.id = gv.uid')
    	->where("gv.createtime between $starttime and $endtime")
    	->orderBy('gv.createtime desc');
    	$search1 && $query->andWhere($search1);
    	$search2 && $query->andWhere($search2);
    	$data = Helper::getPages($query,$curPage,$pageSize,$search);
    	if($data['data']){
    		$data['data'] = $data['data']->all();
    	}
    	$pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
    	//统计
    	$order = \Yii::$app->db->createCommand("SELECT sum(price) as count_p,count(distinct uid) as count_u FROM g_voucher as gv left join g_user as gu on gv.uid=gu.id  WHERE gv.state=2 $search1  and  gv.createtime BETWEEN $starttime and $endtime")->queryOne();
    	//菜单定位
    	unset(yii::$app->session['localfirsturl']);
    	yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/exchange/voucher.html';
    	return $this->render('voucher', [
    			'data' => $data,
    			'pages' => $pages,
    			'value' => $value,
    			'transaction_id'=>$transaction_id,
    			'state'=>$state,
    			'start_time'=>$start_time,
    			'end_time'=>$end_time,
    			'order'=>$order,
    			]);
    }
    
    
    
    /**
     * 改变是否处理状态
     */
    public function actionChangeisdispose(){
    	if(yii::$app->request->isAjax || isset($_POST['id']) ||isset($_POST['state'])){
    		$id = Helper::filtdata($_POST['id'],'INT');
    		$state = Helper::filtdata($_POST['state'],'INTEGER');
    		if(!$id || $state===false){
    			return json_encode([
    					'errorcode'=>1001,
    					'info'=>'网络异常，稍后在试',
    					]);
    		}
    		$exchange = Exchange::findOne(['id'=>$id]);
    		if(!$exchange){
    			return json_encode([
    					'errorcode'=>1001,
    					'info'=>'改游戏不存在,请刷新在试',
    					]);
    		}
    		$exchange->isdispose = $state;
    		if($exchange->save()){
    			$info = ($state==0)?'状态已修改为已处理':'状态已修改为未处理';
    			return json_encode([
    					'errorcode'=>0,
    					'info'=>$info,
    					]);
    		}else{
    			return json_encode([
    					'errorcode'=>1001,
    					'info'=>'该小游戏不存在,请刷新在试',
    					]);
    		}
    	}
    }
}