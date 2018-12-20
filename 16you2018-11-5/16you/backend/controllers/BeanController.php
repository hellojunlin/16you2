<?php 
namespace backend\controllers;

use yii;
use backend\controllers\BaseController;
use common\common\Helper;
use yii\data\Pagination;
use common\models\User;

class BeanController extends BaseController{
    //统计页
    public function actionTocount() {
    	$managertype = yii::$app->session->get('managetype'); //管理角色
        $pid = yii::$app->session->get('pid'); //权限管理
        $gcreatime = '';
        if($pid){//平台管理则或者平台商
        	if(yii::$app->session->get('platepid')==6){
        		$gcreatime = ['between','gu.createtime','1501516800','1525104000'];//显示2017年8月份以后的数据
        		$gcreatime1 = 'createtime between 1501516800 and 1525104000';
        	}
        }
        //分页
        $curPage = Yii:: $app->request->get( 'page',1);
        $pageSize = yii::$app->params['pagenum'];
        //搜索
        $value =Helper::filtdata(Yii:: $app->request->get('keyword',''));
        $search = ($value)?['like','gp.pname',$value]: '';
        $start_time = Yii:: $app->request->get('start_time');  
        $end_time = Yii:: $app->request->get('end_time');
        $starttime = $start_time?strtotime($start_time):strtotime(date('Y-m'));
        $endtime = $end_time?strtotime($end_time)+3600*24:time();
        
        // 查询平台下的粉丝
        $query = (new \yii\db\Query())
         ->select('gp.id as pid,gp.pname, COUNT(gu.id) as num')
        ->from('g_plateform as gp')
        ->leftJoin('g_user as gu','gp.id=gu.pid')
        ->where("gu.createtime between $starttime and $endtime")
        ->groupBy('gp.id')
        ->orderBy('gp.createtime desc');
        $pid && $query = $query->andWhere(['gp.id'=>$pid]);
        $gcreatime  && $query = $query->andWhere($gcreatime);
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        if($data['data']){
            $data['data'] = $data['data']->all();
            //查询当前时间的数据汇总
            $cuser = (new \yii\db\Query())
                    ->from('g_user as gu')
                    ->leftJoin('g_plateform as gp','gp.id=gu.pid')
                    ->where("gu.createtime between $starttime and $endtime")
                    ->andWhere($search)
                    ->andWhere($gcreatime)
                    ->count();
        }else{
            $data['data'] = '';
            $cuser = '';
        }
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);

        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/bean/tocount.html';
        $managemodel = yii::$app->session['tomodel'];
        return $this->render('count', [
                'data' => $data,
                'pages' => $pages,
                'search' => $value,
                'managemodel'=>$managemodel,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'cuser' => $cuser,
        		'managertype'=>$managertype
                ]);
    } 
    
    /**
     * 查看详情页
     * @return Ambigous <string, string>
     */
    public function actionDetacount() { 
        if($_GET['id']){
            $managemodel = yii::$app->session['tomodel'];
            $manage_pid = yii::$app->session->get('pid'); //权限管理
           
            $pid = '';
            if($managemodel->role!=-1 && $managemodel->type==0){//不是超级管理员，且是平台商，则只能看自己的平台信息
                $pid = $managemodel->g_p_id;
            }else{
                 $pid = Helper::filtdata($_GET['id'],'INT');
            }
            if(!$pid){
                exit;
            }
            $qyear = (isset($_GET['starttime'])) ? substr(trim($_GET['starttime']),0,4): substr(date("Y-m-d"),0,4); //查询的年份
            $qmonth = (isset($_GET['starttime'])) ? trim($_GET['month']):substr(date("Y-m-d"),5,2); //查询的月份
            $date = ($qmonth<10)?$qyear.'0'.($qmonth+1).'00':$qyear.($qmonth+1).'00';
            $dateres = $this->getthemonth($date);
            $firsttime = '';
            $endtime = '';
            if(!empty($dateres)){
                $firsttime = strtotime($dateres[0]);  //当前月份第一天的时间戳
                $endtime = strtotime($dateres[1]);//当前月份最后一天的时间戳
            }
            if($manage_pid){
            	if(yii::$app->session->get('platepid')==6){
            		$endtime = '1525104000';
            	}
            }
            $userarr = User::find()->where(['pid'=>$pid])->andWhere(['between', 'createtime', $firsttime, $endtime])->asArray()->all();//查询当前月份的粉丝
            $uarr = array();//存储粉丝数数
            $darr = array();//显示天数1-31天
            for($index=1;$index<=31;$index++){
                $uarr[$index] = 0;//$index=>天  value=>订单数
                $darr[]=$index;
            }
            $darr = implode(',',$darr); //数组转字符串
            if($userarr){//存在订单
                foreach ($userarr as $u){
                    $createtime = date('Y-m-d',$u['createtime']);
                    if(substr($createtime,0,4) == $qyear){  //查询的年份
                        $vmonth = substr($createtime,5,2); //月份
                        if($vmonth<10){
                            $vmonth = substr($vmonth,1,1);
                        }
                        $uarr[$vmonth] = $uarr[$vmonth]+1;
                    }
                }
            }
            //统计当月订单数
            $uadata =implode(',',$uarr);
            return $this->render('detacount',[
                    'uarr'=>$uadata,
                    'uid'=>$pid,
                    'year'=>$qyear,
                    'darr'=>$darr,
                    'month'=>$qmonth,
                    ]);
        }
    }            
    
    /**
     * 获取当月的第一天和最后一天
     * @param unknown $date
     * @return multitype:string
     */
    private function getthemonth($date){
        echo $date.'=';
        $firstday = date('Y-m-01', strtotime($date));
        $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
        return array($firstday,$lastday);
    }
    
   
}