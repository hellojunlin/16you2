<?php 
namespace backend\controllers;

use yii;
use backend\controllers\BaseController;
use common\models\Integral;
use common\common\Helper;
use yii\data\Pagination;

class IntegralController extends BaseController{

 //积分首页
    public function actionIndex() { 
        //分页
        $curPage = Helper::filtdata(Yii:: $app->request->get( 'page',1));
		$pageSize = 80;
        //搜索
        $value = Helper::filtdata(Yii:: $app->request->get('value','')); 
        $integraltype = Helper::filtdata(Yii:: $app->request->get('integraltype',''));
        $start_time = Yii:: $app->request->get('start_time','');
        $end_time = Yii:: $app->request->get('end_time','');
        $starttime = $start_time?strtotime($start_time):strtotime(date('Y-m-d'));
        $endtime = $end_time?strtotime($end_time)+3600*24:strtotime(date('Y-m-d'))+3600*24;
        $search = ($value)?['like','gu.Unique_ID',$value]: '';
        $search1 = ($integraltype)?['gi.type'=>$integraltype]:'';
        $query = (new \yii\db\Query())
               ->select('gi.type,gi.id,gu.username,gi.integral,gi.createtime,gu.Unique_ID,gu.head_url')
                ->from('g_integral AS gi')
                ->leftJoin('g_user AS gu','gu.id = gi.uid')
                ->where("gi.createtime between $starttime and $endtime")
                ->orderBy('gi.createtime desc');
        $search1 && $query->andWhere($search1);
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        if($data['data']){
        	$data['data'] = $data['data']->all();
        	$type = yii::$app->params['integral_type'];
        	foreach ($data['data'] as $k=>$v){
        		isset($type[$v['type']]) && $data['data'][$k]['type'] = $type[$v['type']];
        	}
        } 
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        $totalintegral = (new \yii\db\Query())
		               	->select('sum(gi.integral) as totalintegral')
		                ->from('g_integral AS gi')
		                ->leftJoin('g_user AS gu','gu.id = gi.uid')
		                ->where("gi.createtime between $starttime and $endtime")
		                ->orderBy('gi.createtime desc');
        $search && $totalintegral->andWhere($search);
		$search1 && $totalintegral->andWhere($search1);
		$totalintegral = $totalintegral->one();
        //菜单定位
        unset(yii::$app->session['localsecondurl']);
        yii::$app->session['localfirsturl'] = yii::$app->params['backend'].'/integral/index.html';
        return $this->render('index', [
             'data' => $data,
             'pages' => $pages,
             'value' => $value,
        	'integraltype'=>$integraltype,
        	'totalintegral'=>$totalintegral,
        	'start_time'=>$start_time,
        	'end_time'=>$end_time,
        ]);
    }  
	
    //详情
    public function actionToedit(){
        if(!isset($_GET['id'])){
            exit;
        }
        $id = Helper::filtdata($_GET['id'],'INT');
        $model = (new \yii\db\Query())
                ->select('gi.type,username,gi.integral,gi.createtime,head_url')
                ->from('g_integral AS gi')
                ->leftJoin('g_user AS gu','gu.id = gi.uid')
                ->where(['gi.id'=>$id])
                ->one();
        if($model){
            $type = yii::$app->params['integral_type'];
            $model['type'] = $type[$model['type']];
            return $this->render('edit',[
                'model'=>$model,
                ]);
        }

    }
    
    /**
     * 排行榜
     * @return [type] [description]
     */
    public function actionRanking(){
    	$pid1 = Helper::filtdata(yii::$app->request->get('pid',''));  //选择的pid
    	//分页
    	$curPage = Yii:: $app->request->get( 'page',1);
    	$pageSize = yii::$app->params['pagenum'];
    	//搜索
    	$value = Helper::filtdata(Yii:: $app->request->get('value',''));
    	$Unique_ID = Helper::filtdata(Yii:: $app->request->get('Unique_ID',''));
    	$integraltype = Helper::filtdata(Yii:: $app->request->get('integraltype',''));
    	$search = ($value)?['like','username',$value]: '';
    	$search1 = ($Unique_ID)?['like','Unique_ID',$Unique_ID]: '';
    	$search2 = ($integraltype)?['gi.type'=>$integraltype]:'';
    	$start_time = Yii:: $app->request->get('start_time','');
    	$end_time = Yii:: $app->request->get('end_time','');
    	$starttime = $start_time?strtotime($start_time):strtotime('1970-01-01');;
    	$endtime = $end_time?strtotime($end_time)+3600*24:strtotime(date('Y-m-d'))+3600*24;
    	$time = "gi.createtime between {$starttime} and {$endtime}";
    	$query = (new \yii\db\Query())
    	->select('sum(gi.integral) AS totalintegral,gu.username,gu.Unique_ID,gu.head_url,gu.integral')
    	->from('g_integral AS gi')
    	->leftJoin('g_user AS gu','gu.id = gi.uid')
    	->where("gi.createtime between $starttime and $endtime")
    	->groupBy('gi.uid')
    	->orderBy('totalintegral desc');
    	$search1 && $query = $query->andWhere($search1);
    	$search2 && $query = $query->andWhere($search2);
    	$data = Helper::getPages($query,$curPage,$pageSize,$search);
    	$data['data'] =  ($data['data'])?$data['data']->all():'';
    	$pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
    	//菜单定位
    	unset(yii::$app->session['localfirsturl']);
    	yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/integral/ranking.html';
    	$managemodel = yii::$app->session['tomodel'];
    	return $this->render('ranking',[
    			'data' => $data,
    			'pages' => $pages,
    			'value' => $value,
    			'starttime'=>$start_time,
    			'endtime'=>$end_time,
    			'pid' => $pid1,
    			'managemodel'=>$managemodel,
    			'Unique_ID'=>$Unique_ID,
    			'integraltype'=>$integraltype,
    			]);
    }
}