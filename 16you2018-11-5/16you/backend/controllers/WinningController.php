<?php
namespace backend\controllers;

use yii;
use backend\controllers\BaseController;
use common\common\Helper;
use yii\data\Pagination;
use common\models\Winning;
use common\models\Dogexchange;
use yii\base\Object;
use common\models\Redpackrecord;
use common\common\Wxpayutil;
use common\models\Rebatecurrencytemp;
use common\models\Order;

class WinningController extends BaseController
{
	public function actionIndex(){
		$model = new Winning();
        //分页
        $curPage = Yii:: $app->request->get( 'page',1);
        $pageSize = yii::$app->params['pagenum'];
        $value = Helper::filtdata(Yii:: $app->request->get('keyword','')); 
        $selecte = Helper::filtdata(Yii:: $app->request->get('selectval','')); 
        $startdate = Helper::filtdata(Yii:: $app->request->get('starttime',''));
        $enddate = Helper::filtdata(Yii:: $app->request->get('endtime',''));
        $starttime = $startdate?strtotime($startdate):strtotime(date('Y-m-d',time()));
        $endtime = $enddate?strtotime($enddate)+3600*24-1:time();
        $search = ($value)?['like',$selecte,$value]: '';
        //搜索
        $query = (new \yii\db\Query())
        ->select('w.id,uid,username,w.content,w.type,w.createtime')
        ->from(' g_winning as w')
        ->leftJoin('g_user as u','w.uid=u.id')
        ->orderBy('w.createtime desc')
        ->where("w.createtime between $starttime and $endtime");
        $query1 = clone $query;
        
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        if($data['data']){
            $data['data'] = $data['data']->all();
        }
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        $price = 0;
        $res = $query1->andWhere($search)->andWhere(['type'=>2])->all();
        if($res){
            foreach ($res as $v) {
                 $price1 = (strlen($v['content'])==23)?mb_substr($v['content'],4):mb_substr($v['content'],2);
                 $price += mb_substr($price1,0,mb_strlen($price1)-1);
                // $price += mb_substr($v['content'], 2,1,'utf-8');
            }
        }
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/winning/index.html';
        return $this->render('index', [
                'data' => $data, 
                'pages' => $pages,
                'value' => $value,
                'select' => $selecte,
                'price' => $price,
                'starttime'=>$startdate,
                'endtime'=>$enddate,  
            ]);
	}

	/**
     * 删除活动
     * @param integer $uniqid
     * @return mixed
     */
    public function actionDel()
    {
        $id = Helper::filtdata(Yii::$app->request->post('id'),'INT');
        $res = Winning::deleteAll(['id'=>$id]);
        if($res){
            return json_encode([
                'errorcode'=>0,
                'info'=>'删除成功',
            ]);
        }else{
            return json_encode([
                'errorcode'=>1011,
                'info'=>'删除失败',
            ]);
        }
    }
    
    /**
     * 整点抢红包管理
     */
    public function actionRobredpacket(){
    	//分页
    	$curPage = Yii:: $app->request->get( 'page',1);
    	$pageSize = yii::$app->params['pagenum'];
    	$type = Helper::filtdata(Yii:: $app->request->get('type',''));
    	$startdate = Helper::filtdata(Yii:: $app->request->get('starttime',''));
    	$enddate = Helper::filtdata(Yii:: $app->request->get('endtime',''));
    	$starttime = $startdate?strtotime($startdate):strtotime(date('Y-m-d',time()));
    	$endtime = $enddate?strtotime($enddate):time();
    	$where  =($type)?['type'=>$type]:'';
    	//搜索 1505923200  1506009600
    	$query = (new \yii\db\Query())
		    	 ->select('gr.id,gr.uid,gr.type,gr.createtime,gr.money,gr.title,gu.username')
		    	 ->from('g_redpackrecord AS gr')
		    	 ->leftJoin('g_user AS gu','gr.uid = gu.id')
		    	 ->orderBy('gr.createtime DESC')
    	         ->where("gr.createtime between $starttime and $endtime")
    			 ->andWhere($where);
    	$data = Helper::getPages($query,$curPage,$pageSize,'');
    	$data['data'] =  ($data['data'])?$data['data']->all():''; 
    	$pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
    	$money = Redpackrecord::find()->where($where)->andWhere("createtime between $starttime and $endtime")->select('sum(money) as money')->one();
    	$money = ($money->money)?$money->money:0;
    	//菜单定位
    	unset(yii::$app->session['localfirsturl']);
    	yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/winning/robredpacket.html';
    	return $this->render('robredpacket', [
    			'data' => $data,
    			'pages' => $pages,
    			'starttime'=>$startdate,
    			'endtime'=>$enddate,  
    			'money'=>$money,
    			'type'=>$type,
    			]); 
    }
    
    public function  actionDelredpacket(){
    	$id = Helper::filtdata(Yii::$app->request->post('id'),'INT');
    	$res = Redpackrecord::deleteAll(['id'=>$id]);
    	if($res){
    		return json_encode([
    				'errorcode'=>0,
    				'info'=>'删除成功',
    				]);
    	}else{
    		return json_encode([
    				'errorcode'=>1001,
    				'info'=>'删除失败',
    				]);
    	}
    }

    /**
     * 数据统计
     * @return [type] [description]
     */
    public function actionTotle(){
        //查出大转盘每天转的数次
        $data = \Yii::$app->db->createCommand("SELECT FROM_UNIXTIME(createtime,'%Y-%m-%d') as date,count(*) as ucount FROM g_winning GROUP BY Date order By Date desc")->queryAll();
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/winning/totle.html';
        return $this->render('totle', [
            'data' => $data,
        ]); 
    }

        //狗粮兑换
    public function actionDogfood(){
        //搜索
        $startdate = Helper::filtdata(Yii:: $app->request->get('starttime',''));
        $enddate = Helper::filtdata(Yii:: $app->request->get('endtime',''));
        $starttime = $startdate?strtotime($startdate):strtotime(date('Y-m'));
        $endtime = $enddate?strtotime($enddate)+86400:time();
        $value = Helper::filtdata(Yii:: $app->request->get('keyword',''));
        $search = ($value)?['like','Unique_ID',$value]: '';
        $data = (new \yii\db\Query())
                ->select('gu.id,Unique_ID,username,ga.type,sum(ga.num) unum')
                ->from('g_acquire AS ga')//狗粮记录
                ->leftJoin('g_user as gu','gu.id = ga.uid')
                ->orderBy('unum desc')
                ->groupBy('gu.id,type')//按每个用户每个不同的获取方式分组
                ->where($search)
                ->andWhere(['between','ga.createtime',$starttime,$endtime])
                ->all();
        $res = [];
        $price = 0;
        if($data){
            $arruid = [];
            foreach ($data as $k => $v) {
                if(array_key_exists($v['id'],$res)){
                    $res[$v['id']]['type'][$v['type']] = $v['unum']; 
                    $res[$v['id']]['sumnum'] += $v['unum'];
                }else{
                    $res[$v['id']] = [//以每个用户的id作为新数组的key
                            'id'=>$v['id'],
                            'Unique_ID'=>$v['Unique_ID'],
                            'username'=>$v['username'],
                            'type'=>[
                                $v['type']=>$v['unum'],
                            ],
                            'sumnum'=>$v['unum'],
                        ];
                }
                if(!in_array($v['id'],$arruid)){
                    $arruid[] = $v['id'];
                }
            }
            $uid = implode(',',$arruid);
            $dog = Dogexchange::find()->where("uid in({$uid})")->andWhere(['between','createtime',$starttime,$endtime])->select('sum(price) price,uid')->groupBy('uid')->asArray()->all();
            if($dog){
                foreach ($dog as $vddd) {
                    $price = $price + $vddd['price'];
                }
            }
            foreach ($res as $d => $vd) {
                $res[$d]['price'] = 0;
                foreach ($dog as $kg => $vg) {

                    if($vd['id']==$vg['uid']){
                        $res[$d]['price'] = $vg['price'];
                    }
                }
            }
        }

        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/active/dogfood.html';
        return $this->render('dogfood', [
                'res' => $res, 
                'value' => $value,
                'price'=>$price,
                'starttime'=>$startdate,
                'endtime'=>$enddate,  
            ]);
    }

    /**
     * 红包补发
     */
    public function actionReissuered(){
        if(!yii::$app->request->isAjax || !isset($_POST['id'])){
            return json_encode([
                'info'=>'数据错误，请稍后再试',
                'errorcode'=>1001,
            ]);
        }
        $win = Winning::findOne($_POST['id']);
        if(!$win){
             return json_encode([
                'info'=>'数据错误，请稍后再试',
                'errorcode'=>1002221,
            ]);
        }
        $wx = new Wxpayutil();
        $openid = $win->openid;
        $content = $win->content;
        $price = (strlen($v['content'])==23)?mb_substr($content,4):mb_substr($content,2);
        $price = mb_substr($price,0,mb_strlen($price)-1);
        if($win->type==3){
            $partner_trade_no = 'wxp'.date('YmdHis').rand(1000,9999);
            $p_res = $wx->sendredpacket($openid,$partner_trade_no,$price,'16游大转盘红包'); //发红包
            if($p_res){
                $win->type = 2;//红包发送成功
                $win->save();
                return json_encode([
                    'info'=>'红包发送成功',
                    'errorcode'=>0,
                ]);
            }else{
                $win->type = 5;//红包发送失败
                $win->save();
                return json_encode([
                    'info'=>'红包发送失败',
                    'errorcode'=>9909,
                ]);
            }
        }else{
            return json_encode([
                'info'=>'数据错误，请稍后再试',
                'errorcode'=>3422,
            ]);
        }
    }
    
/**
     * 游币返利
     */
    public function actionRebatecurrency(){
    	//分页
    	$curPage = Yii:: $app->request->get( 'page',1);
    	$pageSize = yii::$app->params['pagenum'];
    	$aid = Helper::filtdata(Yii:: $app->request->get('aid',''));
    	$uniqueid = Helper::filtdata(Yii:: $app->request->get('uniqueid',''));
    	$startdate = Helper::filtdata(Yii:: $app->request->get('starttime',''));
    	$enddate = Helper::filtdata(Yii:: $app->request->get('endtime',''));
    	$starttime = $startdate?strtotime($startdate):strtotime(date('Y-m-d',time()));
    	$endtime = $enddate?strtotime($enddate)+86400:time();
    	$where  =($aid)?['aid'=>$aid]:'';
    	//搜索
    	$query = (new \yii\db\Query())
    	->select('gr.id,gr.aid,gr.uid,gr.type,gr.createtime,gr.drawtime,gr.rebatecurrency,gr.price,gr.isdraw,gu.username,gu.Unique_ID,gu.head_url,gp.pname')
    	->from('g_rebatecurrencytemp AS gr')
    	->leftJoin('g_user as gu','gu.id = gr.uid')
    	->leftJoin('g_plateform as gp','gp.id=gu.pid')
    	->orderBy('gr.createtime DESC')
    	->where("gr.createtime between $starttime and $endtime")
    	->andWhere($where);
    	$uniqueid && $query->andWhere(['gu.Unique_ID'=>$uniqueid]);
    	$data = Helper::getPages($query,$curPage,$pageSize,'');
    	$data['data'] =  ($data['data'])?$data['data']->all():'';
    	$pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
    	$allrebatecurrency = 0; //总返利游币
    	$rebatecurrency = 0; //已领取的游币
    	$price = 0;      //已充值的金额
    	$rebatecurrencytemp = Rebatecurrencytemp::find()->where("createtime between $starttime and $endtime")->andWhere($where)->asArray()->all();
    	if($rebatecurrencytemp){
    		foreach ($rebatecurrencytemp as $v){
    			$allrebatecurrency += $v['rebatecurrency'];
    			if($v['isdraw']==1){
    				$rebatecurrency += $v['rebatecurrency'];
    			}
    		}
    	}
    	$order = Order::find()->where("createtime between $starttime and $endtime and state=2 and price>=100")->select('sum(price) as price')->asArray()->one();
    	if($order){
    		$price = $order['price'];
    	} 
    	//菜单定位
    	unset(yii::$app->session['localfirsturl']);
    	yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/winning/rebatecurrency.html';
    	return $this->render('rebatecurrency', [
    			'data' => $data,
    			'pages' => $pages,
    			'starttime'=>$startdate,
    			'endtime'=>$enddate,
    			'allrebatecurrency'=>$allrebatecurrency,
    			'rebatecurrency'=>$rebatecurrency,
    			'aid'=>$aid,
    			'price'=>$price,
    			'uniqueid'=>$uniqueid,
    			]);
    
    }
    
}