<?php 
namespace backend\controllers;

use yii;
use backend\controllers\BaseController;
use common\models\Order;
use common\models\User;
use common\models\Game;
use common\models\Refund;
use common\models\Plateform;
use common\models\Manage;
use common\models\OrderHide;
use common\models\Playgameuser;
use common\common\Helper;
use common\common\Wxpayutil;
use yii\data\Pagination;
use common\common\Phpexcelr;
use common\models\Configuration;
use common\redismodel\UserRedis;
use common\common\Sftpayutil;
use common\models\OrderTest;

class OrderController extends BaseController{

    //订单首页
    public function actionIndex() { 
        $managemodel = yii::$app->session['tomodel'];
        $manage_pid = yii::$app->session->get('pid'); //权限管理
        if($manage_pid){//平台管理则或者平台商
            $p_where = ['id'=>$manage_pid];
            $gp_where = ['pid'=>$manage_pid];
        }else{//超级管理员
            $p_where = '';
            $gp_where ='';
        }
        $plate = Plateform::find()->where($p_where)->andWhere(['state'=>1])->select(['id','pname'])->orderBy('sort desc')->all();  //查找所有平台
        $pid = Helper::filtdata(yii::$app->request->get('pid')); // 查询平台
        //分页
        $curPage = Helper::filtdata(Yii:: $app->request->get( 'page',1));
        $gid = Helper::filtdata(yii::$app->request->get('gid'));
        $state = Helper::filtdata(yii::$app->request->get('state'));
        $ptype = Helper::filtdata(yii::$app->request->get('ptype'));
        $pageSize = 100;
        //搜索
        $value = Helper::filtdata(Yii:: $app->request->get('value','')); 
        $orderID = Helper::filtdata(Yii:: $app->request->get('orderID','')); 
        $Unique_ID = Helper::filtdata(Yii:: $app->request->get('Unique_ID','')); 
        $districtID = Helper::filtdata(Yii:: $app->request->get('districtID','')); 
        $transaction_id = Helper::filtdata(Yii:: $app->request->get('transaction_id','')); 
        $search = ($value)?['like','gu.username',$value]:'';
        $search11 = ($orderID)?['like','go.orderID',$orderID]:'';
        $search12 = ($Unique_ID)?['like','gu.Unique_ID',$Unique_ID]:'';
        $search13 = ($districtID)?['like','go.districtID',$districtID]:'';
        $search14 = ($transaction_id)?['like','go.transaction_id',$transaction_id]:'';
        $start_time = Yii:: $app->request->get('start_time');  
        $end_time = Yii:: $app->request->get('end_time');
        //判断是否有别的查询条件
        // if($search||$search11||$search12||$search13||$search14||$pid||$gid||$state){
        //     $starttime = strtotime('1970-01-01');
        // }else{
            $starttime = $start_time?strtotime($start_time):strtotime(date('Y-m-d'));
        // }
        $endtime = $end_time?strtotime($end_time)+3600*24:strtotime(date('Y-m-d'))+3600*24;
        $query = (new \yii\db\Query())
                ->select('go.id as id,propname,username,name,price,go.num,go.state,orderID,go.createtime,pname,transaction_id,gu.Unique_ID,go.districtID,go.type,go.ptype,go.uid as tourid')
                ->from('g_order AS go')
                ->leftJoin('g_game AS gg','gg.id = go.gid')
                ->leftJoin('g_user AS gu','gu.id = go.uid')
                ->leftJoin('g_plateform AS gp','gp.id = go.pid')
                ->where("go.createtime between $starttime and $endtime")
                ->orderBy('go.createtime desc');
        $manage_pid  && ($query = $query->andWhere(["gp.id"=>$manage_pid]));
        $search11  && ($query = $query->andWhere($search11));
        $search12  && ($query = $query->andWhere($search12));
        $search13  && ($query = $query->andWhere($search13));
        $search14  && ($query = $query->andWhere($search14));
        trim($pid) && ($query = $query->andWhere(["gp.id"=>$pid]));
        trim($gid) && ($query = $query->andWhere(["gg.id"=>$gid]));
        trim($state) && ($query = $query->andWhere(["go.state"=>$state]));
        $ptype  && ($query = $query->andWhere(['go.ptype'=>$ptype]));
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        $data['data'] =  ($data['data'])?$data['data']->all():'';
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        $search0 = $gid?"o.gid like '%$gid%' and":'';
        $search1 = $value?"u.username like '%$value%' and":'';
        $search2 = $Unique_ID?"u.Unique_ID like '%$Unique_ID%' and":'';
        $search3 = $orderID?"o.orderID like '%$orderID%' and":'';
        $search4 = $districtID?"o.districtID like '%$districtID%' and":'';
        $search5 = $transaction_id?"o.transaction_id like '%$transaction_id%' and":'';
        $search6 = $ptype?"o.ptype = '$ptype' and":'';
        $pid1 = $pid?"o.pid=$pid and":'';    
        $game = Game::find()->limit(5000)->asArray()->ALL();
        $o_where = ($manage_pid)?"o.pid in(".implode(',',$manage_pid).") AND" :'';
        //统计
        $order = \Yii::$app->db->createCommand("SELECT sum(price) as count_p,count(distinct uid) as count_u FROM g_order as o left join g_user as u on o.uid=u.id  WHERE o.state=2 and $search0 $search1 $search2 $search3 $search4 $search5 $search6 $o_where $pid1 o.createtime BETWEEN $starttime and $endtime")->queryOne();
        $order['count_p'] = ($order['count_p']=='')?0:$order['count_p'];
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/order/index.html';
        $gname = Helper::filtdata(Yii:: $app->request->get( 'gname'));
        return $this->render('index', [
             'data' => $data,
             'pages' => $pages,
             'value' => $value,
             'start_time' => $start_time,
             'end_time' => $end_time,
             'order' => $order,
             'game' => $game,
             'gid' => $gid,
             'orderID' => $orderID,
             'Unique_ID' => $Unique_ID,
             'districtID' => $districtID,
             'transaction_id' => $transaction_id,
             'plate' => $plate,
             'pid' => $pid,
             'state' => $state,
             'managemodel'=>$managemodel,
        	 'gname'=>$gname,
        	 'ptype'=>$ptype,
        ]);
    }  
    
    /**
     * 订单添加页面
     */
    public function actionToadd() {
        $user = User::find()->all();
        $game = Game::find()->all();
        return $this->render("add",[
            'user'=>$user,
            'game'=>$game,
        ]);
    }
    
    /**
     * 订单编辑页面
     */
    public function actionToedit($id) {
        $model = (new \yii\db\Query())
                ->select('go.id as id,propname,username,name,price,go.num,go.state,orderID,go.createtime,gu.head_url,transaction_id')
                ->from('g_order AS go')
                ->leftJoin('g_game AS gg','gg.id = go.gid')
                ->leftJoin('g_user AS gu','gu.id = go.uid')
                ->where(['go.id'=>$id])
                ->one();
        if($model){
            switch ($model['state']) {
                case '1':
                    $model['state'] = '待付款';
                    break;
                case '2':
                    $model['state'] = '支付成功';
                    break;
                case '3':
                    $model['state'] = '退款中';
                    break;
                case '4':
                    $model['state'] = '已退款';
                    break;
                case '5':
                    $model['state'] = '支付失败';
                    break;
            }
        }
        return $this->render("edit",[
            'model'=>$model
        ]);
    }
    
    public function actionCreate(){
        if(!isset($_POST['propname'])||!isset($_POST['uid'])||!isset($_POST['gid'])||!isset($_POST['state'])){
            return $this->render('add');
        }
        $model = new Order();
        if(isset($_POST['id'])){
            $model = $model->findOne($_POST['id']);
        }else{
            $model->createtime = time();
        }
        $app = Yii::$app->request;
        $model->propname = Helper::filtdata($app->post('propname',''));
        $model->uid = Helper::filtdata($app->post('uid',''));
        $model->gid = Helper::filtdata($app->post('gid',''));
        $model->price = Helper::filtdata($app->post('price',''));
        $model->num = Helper::filtdata($app->post('num',''));
        $model->orderID = Helper::filtdata($app->post('orderID',''));
        $model->transaction_id = Helper::filtdata($app->post('transaction_id',''));
        $model->state = Helper::filtdata($app->post('state',''));
        $pid = User::find()->where(['id'=>$model->uid])->select(['pid'])->one();
        if(!$pid){
            echo '<script>该游戏平台已不存在</script>';
            exit;
        }else{
            $model->pid = $pid->pid;
        }
        if($model->save()){
            return $this->redirect('index.html');
        }
    }
    
     
    /**
     * 订单统计页面
     * 折线图数据
     */
    public function actionTocount() {
        $managemodel = yii::$app->session['tomodel'];
        $pid = yii::$app->session->get('pid'); //权限管理   
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
        $order = Order::find()->where("createtime between $time1 and $months1");
        
        ($pid)? $o_where['pid'] = $pid :$o_where = '' ;
        $order = $order->where($o_where);   
        $re_data = array();
        $re_count = $order->count();
        if($re_count){
            $ceil = 1;
            if($re_count >50000){//每次只查50000条数据
                $ceil = ceil($re_count/50000);
            }
            for ($i=1; $i <= 2; $i++) { 
                $arr = $order->select(['state','createtime'])
                    ->offset(($i-1)*50000)->limit(50000)
                    ->asArray()->all();
                $re_data = array_merge($arr,$re_data);//合并数组
            }
        }
        $reday = array();//记录当天的订单 key=>天 value=>订单数
        $darr = array();//显示天数1-31天
        for($index=1;$index<=31;$index++){
            $darr[]=$index;
            $reday[$index] = 0;//$index=>天  value=>订单数
        } 
        $darr = implode(',',$darr); //数组转字符串
        $dayarr=array();//统计所有订单数
        $valarr=array();//统计待付款的订单
        $valarr1=array();//统计付款成功的订单
        $valarr2=array();//统计退款中的订单
        $valarr3=array();//统计已退款的订单
        $valarr4=array();//统计付款失败的订单
        foreach ($re_data as $vc){
            $createtime = date('Y-m-d',$vc['createtime']);
            $cr_day = substr($createtime,8,2);
            if($cr_day<10){
                $cr_day = substr($cr_day,1,1);
            }
            if(substr($createtime,0,4) == $qyear){  //查询的年份
                if(substr($createtime,5,2) == $qmonth){//查询的月份
                    switch ($vc['state']) {
                        case '1':
                            $valarr[] = $cr_day;
                            break;
                        case '2':
                            $valarr1[] = $cr_day;
                            break;
                        case '3':
                            $valarr2[] = $cr_day;
                            break;
                        case '4':
                            $valarr3[] = $cr_day;
                            break;
                        case '5':
                            $valarr4[] = $cr_day;
                            break;
                    }
                    $arr = substr($createtime,8,2);//获取具体的日期
                    if($arr<10){
                        $arr = substr($arr,1,1);
                    }
                    $dayarr[] = $arr;
                }
            }
        }
        $valdat = array_replace($reday,array_count_values ($valarr));
        $valdat1 = array_replace($reday,array_count_values ($valarr1));
        $valdat2 = array_replace($reday,array_count_values ($valarr2));
        $valdat3 = array_replace($reday,array_count_values ($valarr3));
        $valdat4 = array_replace($reday,array_count_values ($valarr4));
        $val_s['valdata'] = implode(',',$valdat);
        $val_s['valdata1'] = implode(',',$valdat1);
        $val_s['valdata2'] = implode(',',$valdat2);
        $val_s['valdata3'] = implode(',',$valdat3);
        $val_s['valdata4'] = implode(',',$valdat4);
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/order/tocount.html';
        return $this->render('count',[
            'valdata'=>$val_s,
            'darr'=>$darr,
            'time'=>$qyear,
            'time1'=>$qmonth
        ]);
    }
    
    /**
     * 判断添加时的订单号是否存在
     */
    public function actionOrderid(){
        $transaction_id = Helper::filtdata(Yii::$app->request->post('transaction_id'),'STRING');
        $res = Order::findOne(['transaction_id'=>$transaction_id]);
        if($res)
            return 1;
        else
            return 0;
    }  

    /**
     * 删除订单
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        $id = Helper::filtdata(Yii::$app->request->post('id'),'INT');
        if($id){
            $res = Order::deleteALL(['id'=>$id]);
            if($res){
                return 1;//'删除成功！';
            }else{
                return 0;//'删除失败！';
            }
        }
    }
    
    /**
     * 退款订单
     */
    public function actionRefund(){
        //$pid = yii::$app->session->get('pid',''); //权限管理
        $model = new Refund();
        //分页
        $curPage = Yii:: $app->request->get( 'page',1);
        $pageSize = 80;
        //搜索
        $keyword = Yii:: $app->request->get('keyword','');    
        $value = Yii:: $app->request->get('value',''); 
        $uniqueid = Yii:: $app->request->get('uniqueid',''); 
        $search = ($value)?['like',$keyword,$value]: '';
        $start_time = Yii:: $app->request->get('start_time','');
        $end_time = Yii:: $app->request->get('end_time','');
        $starttime = $start_time?strtotime($start_time):0;
        $endtime = $end_time?strtotime($end_time):time();
        $where = "refund_time between $starttime and $endtime";
        //$o_where = ($pid)?['pid'=>$pid]:'';
        $query = $model->find()->where($where)->orderBy('refund_time desc');
        $uniqueid && $query = $query->andWhere(['like','Unique_ID',$uniqueid]);
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        $data['data'] =  ($data['data'])?$data['data']->asArray()->all():'';
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/order/refund.html';
        return $this->render('refund', [
             'data' => $data,
             'pages' => $pages,
             'value' => $value,
             'keyword' => $keyword,
             'start_time' => $start_time,
             'end_time' => $end_time,
             'uniqueid' => $uniqueid,
        ]);
    }

    /**
     * 确认退款
     * @return [type] [description]
     */
    public function actionState(){
        if(!isset($_POST['transaction_id'])&&isset($_POST['ptype'])){
            return '数据异常，请稍后重试！';
            exit ();
        }
        $transaction_id = Helper::filtdata(yii::$app->request->post('transaction_id'));//报名记录表的id
        $ptype = Helper::filtdata(yii::$app->request->post('ptype'));//报名记录表的id
        $Order = Order::findOne(['transaction_id'=>$transaction_id,'state'=>2]);
        if($Order){
            $Order->state = 3; //3退款中
            $wxpay = new Wxpayutil();
            $refundorderno = 'ref'.date('YmdHis',time()).rand(1000,9999);//退款编号
            if($ptype==7){
            	 $res = json_decode($this->sftrefund($Order,$refundorderno),true);
            }else{
           		 $res = $wxpay->refund($Order->transaction_id,$Order->price,Yii::$app->session['tomodel']->username);
            }
            if($res['errorcode'] == 0){//退款成功
                $order = clone $Order;
                $order->state = 4;//4已退款
                if($order->save()){
                    $refund = new Refund();
                    $refund->uid = $Order->uid;
                    $user = User::find()->where(['id'=>$Order->uid])->select(['username','Unique_ID','gid'])->one();
                    $game = Game::find()->where(['id'=>$order->gid])->select(['name'])->one();
                    if(!$user||!$game){
                        return '数据异常，请稍后重试！';
                        exit ();    
                    }
                    $refund->gamename = $game->name;
                    $refund->gid = $order->gid;
                    $refund->createtime = $Order->createtime;
                    $refund->price = $Order->price;
                    $refund->num = $Order->num;
                    $refund->username = $user->username;
                    $refund->Unique_ID = $user->Unique_ID;
                    $refund->transaction_id = $Order->transaction_id;
                    $refund->refund_time = time();
                    $refund->refundorderno = $refundorderno;
                    $refund->save();
                    return 1;
                }else{
                    return '数据保存失败';
                }
            }else{
                return '退款失败';
            }
        }else{
            return '没有查到数据';
        }
    }

    /**
     * 盛付通退款
     */
    private  function sftrefund($order,$refundorderno){
    	$Sftpayutil = new Sftpayutil();
    	$sftparamarr = array();
    	$sftparamarr['merchantNo'] = yii::$app->params['sftpay']['MsgSender'];        //商户号
    	$sftparamarr['charset'] = 'UTF-8';          //字符集
    	$sftparamarr['requestTime'] = date('YmdHis');  //请求时间
    	$sftparamarr['refundOrderNo'] = $refundorderno;          //退款请求流水号(商户系统唯一)
    	$sftparamarr['merchantOrderNo'] = $order->transaction_id;   //原支付订单号
    	$sftparamarr['refundAmount'] = $order->price; 	     //订单创建时间
    	$sftparamarr['notifyURL'] = yii::$app->params['backend'].'/notify/sftrefundnotify.html'; //盛付通退款通知回调地址
    	$sftparamarr['exts'] = '';   //扩展属性,JSON串
    	$data = json_encode($sftparamarr);
    	$reqPar = $data.yii::$app->params['sftpay']['key'];
    	$signature  = strtoupper(MD5($reqPar));
    	$headers = array(
    			"Content-Type:application/json;charset='utf-8'",
    			'signType: MD5',
    			'signMsg:'.$signature,
    	);
    	$requesturl ='http://mgw.shengpay.com/web-acquire-channel/pay/refund.htm';
    	$Sftpayutil = new Sftpayutil();
    	$curlresult = $this->curlPost($requesturl,$data,$headers);
    	return $curlresult;
    }
    
    
    public function curlPost($requesturl,$data,$headers){
    	$curl = curl_init();
    	curl_setopt($curl,CURLOPT_URL,$requesturl);//用PHP取回的URL地址
    	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);//禁用后cURL将终止从服务端进行验证
    	if (defined('CURLOPT_SAFE_UPLOAD')) {
    		curl_setopt($curl, CURLOPT_SAFE_UPLOAD, false);
    	}
    	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);//设定是否显示头信息
    	if(!empty($data)){
    		curl_setopt($curl,CURLOPT_POST,1);//如果你想PHP去做一个正规的HTTP POST，设置这个选项为一个非零值。这个POST是普通的 application/x-www-from-urlencoded 类型，多数被HTML表单使用
    		curl_setopt($curl,CURLOPT_POSTFIELDS,$data);//传递一个作为HTTP “POST”操作的所有数据的字符串
    	}
    	curl_setopt($curl, CURLOPT_HEADER, 0);
    	curl_setopt($curl, CURLOPT_HTTPHEADER,$headers);
    	$output = curl_exec($curl);
    	curl_close($curl);
    	if($output){
    		$res = json_decode($output,true);
    		if(isset($res['returnCode'])&& $res['returnCode']=='01'){  //01是退款成功
    			return  json_encode([
    					'errorcode'=>'0',
    					]);
    		}else{
    			return  json_encode([
    					'errorcode'=>'1001'
    					]);
    		}
    	}else{
    		return  json_encode([
    				'errorcode'=>'1001'
    				]);
    	}
    }
    
    
    
    
    

    /**
     * 退款详情页
     */
    public function actionRefunddetail($id) {
        $model = (new \yii\db\Query())
                ->select('propname,name,R.username,O.price,refund_time,head_url,O.transaction_id,O.createtime,U.Unique_ID')
                ->from('g_refund AS R')
                ->leftJoin('g_order AS O','O.transaction_id = R.transaction_id')
                ->leftJoin('g_user AS U','U.id = R.uid')
                ->leftJoin('g_game AS G','G.id = O.gid')
                ->where(['R.id'=>$id])
                ->one();
        return $this->render("refunddetail",[
            'model'=>$model
        ]);
    }

    //导出订单数据页面
    public function actionDownload(){
        $game = Game::find()->select('id,name')->where(['state'=>'1'])->limit(100)->asArray()->ALL();
        return $this->renderPartial("download",[
            'game'=>$game,
        ]);
    }

    //导出订单数据
    public function actionOutput(){
        $state = Helper::filtdata(yii::$app->request->get('state',''));
        $orderID = Helper::filtdata(yii::$app->request->get('orderID',''));
        $transaction_id = Helper::filtdata(yii::$app->request->get('transaction_id',''));
        $districtID = Helper::filtdata(yii::$app->request->get('districtID',''));
        $Unique_ID = Helper::filtdata(yii::$app->request->get('Unique_ID',''));
        $username = Helper::filtdata(yii::$app->request->get('username',''));
        $search = ($username)?['like','gu.username',$value]:'';
        $search1 = ($orderID)?['like','go.orderID',$orderID]:'';
        $search2 = ($Unique_ID)?['like','gu.Unique_ID',$Unique_ID]:'';
        $search3 = ($districtID)?['like','go.districtID',$districtID]:'';
        $search4 = ($transaction_id)?['like','go.transaction_id',$transaction_id]:'';
        $gid = Helper::filtdata(yii::$app->request->get('gid',''),'INT');
        $pid = yii::$app->session->get('pid');
        $start_time = Helper::filtdata(Yii:: $app->request->get('start_time',''));
        $end_time = Helper::filtdata(Yii:: $app->request->get('end_time'));
        $starttime = $start_time?strtotime($start_time):strtotime(date('Y-m'));
        $endtime = $end_time?strtotime($end_time)+3600*24:time();
        $query = (new \yii\db\Query())
                ->select('propname,username,name,price,orderID,go.createtime,pname,transaction_id,Unique_ID,go.type,districtID,go.state')   
                ->from('g_order AS go') 
                ->leftJoin('g_game AS gg','gg.id = go.gid')
                ->leftJoin('g_user AS gu','gu.id = go.uid')
                ->leftJoin('g_plateform AS gp','gp.id = go.pid')
                ->where("go.createtime between $starttime and $endtime")
                ->orderBy('go.createtime desc');
        $state && ($query = $query->andWhere(["go.state"=>$state]));
        $pid && ($query = $query->andWhere(["gp.id"=>$pid]));
        $gid && ($query = $query->andWhere(["go.gid"=>$gid]));
        $search && ($query = $query->andWhere($search));
        $search1 && ($query = $query->andWhere($search1));
        $search2 && ($query = $query->andWhere($search2));
        $search3 && ($query = $query->andWhere($search3));
        $search4 && ($query = $query->andWhere($search4));
        $data = ($query)?$query->all():'';
        if(!$data){
            return '没有数据需要导出';  
        } 

        $header = ['编号','游戏名称','用户名称','厂商订单遍号','订单状态','订单金额','下单时间','交易编号'];
        foreach ($data as $k => $v) {
            $arr[$k]['v'] = $k+1;
            $arr[$k]['name'] = $v['name'];
            $arr[$k]['username'] = $v['username'];
           // $arr[$k]['orderID'] = $v['districtID'].' ';
            $arr[$k]['orderID'] = $v['orderID'].' ';
            switch ($v['state']) {
                case '1':
                     $arr[$k]['state'] = '待付款';
                    break;
                case '2':
                     $arr[$k]['state'] = '付款成功';
                    break;
                case '4':
                     $arr[$k]['state'] = '已退款';
                    break;
                case '5':
                     $arr[$k]['state'] = '付款失败';
                    break;
            } 
            $arr[$k]['price'] = '￥'.$v['price'];
            $arr[$k]['createtime'] = date('Y-m-d H:i:s',$v['createtime']);
            $arr[$k]['transaction_id'] = $v['transaction_id'].' ';
        }
        $time = date('Y-m-d',$starttime).' - '.date('Y-m-d',$endtime);
        Phpexcelr::exportData($arr,$header,$time."_订单导出",$time."_订单导出");
        exit;//阻止跳转，一定要写，不写会跳转
    }
    
    //支付回调
    public function actionCallback(){ 
        if(isset($_GET['out_trade_no'])){ 
            $time = TIME();
            $out_trade_no = Helper::filtdata($_GET['out_trade_no']);
            //1.查到该订单，验证订单状态是否处理过
            $order = Order::findOne(['transaction_id'=>$out_trade_no]);
            if($order){
                $id = $order->uid;
                $_yew = Order::find()->where(['uid'=>$id,'state'=>2])->orderBy('createtime')->asArray()->all();
                $num = $order->price;
                if($_yew){
                    $ogid = $order->gid;
                    $order->first_time = strtotime(date('Y-m-d',$_yew['0']['createtime']));
                    $order->utype = 1;
                    foreach ($_yew as $vy) {
                        if(!$order->gfirst_time && $vy['gid']==$ogid){//非游戏新付款用户
                            $order->gtype = 1;
                            $order->gfirst_time = strtotime(date('Y-m-d',$vy['createtime']));
                        }
                        $num = intval($num)+intval($vy['price']);//计算vip等级
                    }
                    if($order->gtype!=1){
                        $order->gtype = 2;
                        $order->gfirst_time = $time;
                    }
                }else{
                    $order->gtype = 2;//新付款用户
                    $order->utype = 2;//新付款用户
                    $order->gfirst_time = $time;
                    $order->first_time = $time;
                }
                $order->state = 2;
                $res = $order->save();
                $vip = Helper::vipSort($num);
                if($order->logintype==1){
                	$user = User::findOne($id);
                	if($user){
                		$user->vip = $vip['num'];
                		$user->save();
                	}
                }
                //2.将成功结果发送给游戏方，处理过后，则直接返回true给微信
                $config = Configuration::findOne(['gid'=>$order->gid]);
                    if($config){
                        $guniqe = Game::find()->where(['id'=>$order->gid])->select('unique')->asArray()->one();
                        $data['trade_status'] = 'SUCCESS';
                        $data['game'] = $guniqe?$guniqe['unique']:'';
                        $data['partnerid'] = $config->partnerid;
                        $data['userid'] = $order->uid;
                        $data['total_fee'] = $order->price*100;
                        $data['transaction_id'] = $order->transaction_id;
                        $data['out_trade_no'] = $order->orderID;
                        $data['product_id'] = $order->product_id;
                        $data['attach'] = $order->attach;
                        $data['pay_time'] = date('Y-m-d H:i:s',$order->createtime);
                        $data['timestamp'] = time();
                        $data['sign'] = Helper::getSign($data,$config->key); //获取签名
                        $url = $config->type_url;
                        $curl = curl_init();
                        //$url = 'http://pt0.xd-game.com:20600';
                        curl_setopt($curl,CURLOPT_URL,$url);//用PHP取回的URL地址
                        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);//禁用后cURL将终止从服务端进行验证
                        if (defined('CURLOPT_SAFE_UPLOAD')) {
                            curl_setopt($curl, CURLOPT_SAFE_UPLOAD, false);
                        }
                        if(!empty($data)){
                            curl_setopt($curl,CURLOPT_POST,1);//如果你想PHP去做一个正规的HTTP POST，设置这个选项为一个非零值。这个POST是普通的 application/x-www-from-urlencoded 类型，多数被HTML表单使用
                            curl_setopt($curl,CURLOPT_POSTFIELDS,$data);//传递一个作为HTTP “POST”操作的所有数据的字符串
                        }
                        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);//设定是否显示头信息
                        $output = curl_exec($curl);
                        curl_close($curl);
                        if(!$output){
                        	echo '返回false';
                            exit;
                        }
                        $_order = clone $order;
                        echo 'output=='.$output.'---';
                        if($output=='SUCCESS'){
                            $file = dirname(dirname(__FILE__))."/runtime/callback_log.txt";
                            $myfile = fopen($file, "a+");
                            $txt = date('Y-m-d H:i:s').'---回调成功------回调参数:'.json_encode($data)."\r\n回调地址：".$url."\r\n\r\n";
                            fwrite($myfile, $txt);
                            fclose($myfile);
                            $_order->type = 1;//成功
                            $_order->save();
                            echo '回调成功';exit;
                        }else{
                            $file = dirname(dirname(__FILE__))."/runtime/callback_log.txt";
                            $myfile = fopen($file, "a+");
                            $txt = date('Y-m-d H:i:s').'---回调失败------回调参数:'.json_encode($data)."\r\n回调地址：".$url."\r\n\r\n";
                            fwrite($myfile, $txt);
                            fclose($myfile);
                            echo '回调失败';
                        }
                    }
                    echo '回调结束';
                    exit;
            }else{
                echo '订单不存在';
            }
        }else{
            echo 'out_trade_no不存在';
        }
    }
    
   
    
    
    //成功订单首页
    public function actionSindex() {
    	$managemodel = yii::$app->session['tomodel'];
        $managertype = yii::$app->session->get('managetype'); //管理角色
    	$manage_pid = yii::$app->session->get('pid'); //权限管理
        $gcreatime = '';
        $gcreatime1 = '';
    	if($manage_pid){//平台管理则或者平台商
    		$p_where = ['id'=>$manage_pid];
    		$gp_where = ['pid'=>$manage_pid];
            if(yii::$app->session->get('platepid')==6){
                $gcreatime = ['between','go.createtime','1501516800','1525104000'];//显示2017年8月份以后的数据
                $gcreatime1 = 'and createtime between 1501516800 and 1525104000';
            }
            $hide_m = 'and is_hide=1';
            $gp_where1 = 'and pid in ('.implode(',',$manage_pid).')';
    	}else{//超级管理员
    		$p_where = '';
            $gp_where ='';
            $gp_where1 ='';
    		$hide_m ='';
    	}
    	$ptype = Helper::filtdata(yii::$app->request->get('ptype','')); //支付的方式
    	$payclient = Helper::filtdata(yii::$app->request->get('payclient','')); //支付的方式
        $pid = Helper::filtdata(yii::$app->request->get('pid','')); // 查询平台
        $plate = Plateform::find()->where($p_where)->andWhere(['state'=>1])->select(['id','pname'])->orderBy('sort desc')->asArray()->all();  //查找所有平台
    	//分页
    	$curPage = Helper::filtdata(Yii:: $app->request->get( 'page',1));
    	$pageSize = 100;
    	//搜索
    	$start_time = Yii:: $app->request->get('start_time');
    	$end_time = Yii:: $app->request->get('end_time');
    	//判断是否有别的查询条件
    	$starttime = $start_time?strtotime($start_time):strtotime(date('Y-m-d'));
    	$endtime = $end_time?strtotime($end_time)+3600*24:strtotime(date('Y-m-d'))+3600*24;
    	$mpid = ($manage_pid)?['is_hide'=>1]:'';
    	$query = (new \yii\db\Query())
        ->select('go.id as id,gu.username,name,price,go.num,go.state,go.createtime,pname,go.type,role,is_hide,go.ptype,go.uid as tourid')
        ->from('g_order AS go')
        ->leftJoin('g_game AS gg','gg.id = go.gid')
        ->leftJoin('g_user AS gu','gu.id = go.uid')
        ->leftJoin('g_plateform AS gp','gp.id = go.pid')
        ->leftJoin('g_manage AS gm','gm.g_p_id = go.pid')
        ->where("go.createtime between $starttime and $endtime")
        ->andWhere($mpid)
        ->orderBy('go.createtime desc')
        ->groupBy('go.id')
        ->andWhere('go.state=2');
        $pid && $query = $query->andWhere(['go.pid'=>$pid]);
        $gcreatime&&$query = $query->andWhere($gcreatime);
    	$manage_pid  && ($query = $query->andWhere(["gp.id"=>$manage_pid]));
    	$ptype  && ($query = $query->andWhere(['go.ptype'=>$ptype]));
    	$payclient &&($query = $query->andWhere(['go.payclient'=>$payclient]));
    	$data = Helper::getPages($query,$curPage,$pageSize);
    	$data['data'] =  ($data['data'])?$data['data']->all():'';
    	$pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        $ppid = $pid?"and pid={$pid}":'';
        $ptypewhere = $ptype?"and ptype=$ptype":'';
        $payclientwhere = $payclient?"and payclient =$payclient" :'';
        //游戏总览
        $zcount = \Yii::$app->db->createCommand("SELECT sum(price) zprice,count(distinct uid) zuid from g_order where state=2 $ptypewhere $hide_m $gcreatime1 $gp_where1 $payclientwhere and createtime between $starttime and $endtime $ppid")->queryOne();//总付费用户数和总付费金额(元)

        $cplay = User::find()->where("createtime between $starttime and $endtime");
        $manage_pid && $cplay = $cplay->andWhere(['pid'=>$manage_pid]);
        $pid && $cplay = $cplay->andWhere(['pid'=>$pid]);
        $gcreatime && $cplay = $cplay->andWhere(['between','createtime','1501516800','1525104000']); //['between','go.createtime','1501516800','1525104000']
        $cplay = $cplay->count();
    	//菜单定位
    	unset(yii::$app->session['localfirsturl']);
    	yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/order/sindex.html';
    	return $this->render('successindex', [
    			'data' => $data,
    			'pages' => $pages,
    			'start_time' => $start_time,
    			'end_time' => $end_time,
    			'managemodel'=>$managemodel,
                'zcount'=>$zcount,
                'cplay'=>$cplay,
                'plate'=>$plate,
                'pid'=>$pid,
                'managertype'=>$managertype,
    			'ptype'=>$ptype,
    			'payclient'=>$payclient,
    			]);
    }

        
    public function actionChangehide(){
        if(yii::$app->request->isAjax || isset($_POST['id']) ||isset($_POST['state']) ||isset($_POST['time'])){
            $id = Helper::filtdata($_POST['id'],'INT');
            $state = Helper::filtdata($_POST['state'],'INTEGER');
            $time = Helper::filtdata($_POST['time']);
            if(!$id || $state===false){
                return json_encode(['errorcode'=>1001,'info'=>'网络异常，稍后在试']);
            }
          
            $ordertest = OrderTest::findOne(['oid'=>$id]);
            if($ordertest && $state==2){
            	return json_encode(['errorcode'=>1001,'info'=>'该订单已处理过，请刷新在试']);
            }
            
            if(!$ordertest && $state==1){
            	return json_encode(['errorcode'=>1001,'info'=>'不存在该订单，请刷新在试']);
            }
           
            $connection = Yii::$app->db->beginTransaction();//开启事务
            $order = Order::findOne(['id'=>$id,'state'=>2]);
            if(!$order){ 
            	return json_encode(['errorcode'=>1001,'info'=>'该订单不存在,请刷新在试']);
            }
            $order->is_hide = $state;
            if($order->save()){
            	$info = ($state==2)?'隐藏成功':'显示成功';
                //将隐藏的订单保存起来
                $time = strtotime(date('Y-m-d',strtotime($time)));
                $hide = OrderHide::find()->where(['pid'=>$order->pid,'createtime'=>$time,'gid'=>$order->gid])->one();
                if($hide){
                    $hide->price = ($state==2)?$hide->price+$order->price : $hide->price- $order->price;
                    if($hide->price<0){
                        $hide->price = 0;
                    }
                }else{
                	$hide = new OrderHide();
                    $hide->price = $order->price;
                    $hide->pid = $order->pid;
                    $hide->gid = $order->gid;
                    $hide->createtime = $time;
                }
                
                $this->ordertest($order,$state);
                if($hide->save()){
                	$connection->commit();//事物提交
                	return json_encode([
                			'errorcode'=>0,
                			'info'=>$info,
                			]);
                }else{
                	$connection->rollBack();//事物回滚
                	return json_encode([
                			'errorcode'=>1001,
                			'info'=>'保存失败，请刷新再试',
                			]);
                }
            }else{
                return json_encode([
                        'errorcode'=>1001,
                        'info'=>'该订单不存在,请刷新在试',
                        ]);
            }
        }
    }
    
    private function ordertest($order,$state){
    	if($state==1){
    		$ordertest = OrderTest::deleteAll(['oid'=>$order->id]);
    	}elseif($state==2){//隐藏
    		$ordertest = new OrderTest();
    		$ordertest->gid = $order->gid;
    		$ordertest->uid = $order->uid;
    		$ordertest->pid = $order->pid;
    		$ordertest->oid = $order->id;
    		$ordertest->price = $order->price;
    		$ordertest->transaction_id = $order->transaction_id;
    		$ordertest->hidetime = $order->createtime;
    		$ordertest->createtime = time();
    		$ordertest->save();
    	}
    }
    
    
    
    /***
     * 获取退款数据
    */
    public function actionGetrefunddata(){
    	if(!isset($_POST['transaction_id'])){
    		return '数据异常，请稍后重试！';
    		exit ();
    	}
    	$transaction_id = Helper::filtdata(yii::$app->request->post('transaction_id'));//报名记录表的id
    	$order = Order::findOne(['transaction_id'=>$transaction_id,'state'=>2]);
    	if($order){
    		$refund = Refund::findOne(['transaction_id'=>$transaction_id,'state'=>1]);
    		if(!$refund){
    			$refund = new refund();
    		}
    		$refund->uid = $order->uid;
    		$user = User::find()->where(['id'=>$order->uid])->select(['username','Unique_ID','gid'])->one();
    		$game = Game::find()->where(['id'=>$order->gid])->select(['name'])->one();
    		if(!$user||!$game){
    			return '数据异常，请稍后重试！';
    			exit ();
    		}
    		$refund->gamename = $game->name;
    		$refund->gid = $order->gid;
    		$refund->createtime = $order->createtime;
    		$refund->price = $order->price;
    		$refund->num = $order->num;
    		$refund->username = $user->username;
    		$refund->Unique_ID = $user->Unique_ID;
    		$refund->transaction_id = $order->transaction_id;
    		$refund->refund_time = time();
    		$refund->state = 1;        //退款中，并未回调
    		$refund->refundorderno = 'sftr'.date('YmdHis',time()).rand(1000,9999);
    		if($refund->save()){
		    	$sftpayutil = new Sftpayutil();
		    	$sftparamarr = array();
		    	$sftparamarr['serviceCode'] = "REFUND_REQ";
		    	$sftparamarr['version'] = "V4.4.1.1.1";
		    	$sftparamarr['charset'] = "UTF-8";
		    	$sftparamarr['traceNo'] = $sftpayutil->getTraceno(); ;
		    	$sftparamarr['senderId'] = yii::$app->params['sftpay']['MsgSender'];
		    	$sftparamarr['sendTime'] = date('YmdHis');
		    	$sftparamarr['merchantNo'] = yii::$app->params['sftpay']['MsgSender'];
		    	$sftparamarr['refundOrderNo'] =  $refund->refundorderno;//退款订单号
		    	$sftparamarr['originalOrderNo'] = $transaction_id;  //商户原始订单号
		    	$sftparamarr['refundAmount'] = $order->price;     //退款金额
		    	$sftparamarr['refundRoute']= 0;//0原路返回
		    	$sftparamarr['notifyURL'] = yii::$app->params['backend'].yii::$app->params['sftpay']['refundnotifyUrl'];
		    	$sftparamarr['signType'] = "MD5";
		    	$sftparamarr['signMsg'] = $sftpayutil->getSignMsg($sftparamarr,yii::$app->params['sftpay']['key']);
		    	try {
		    		//不缓存wsdl文件, soap版本为1.1
		    		$options = array('trace'=>true,'cache_wsdl'=>'WSDL_CACHE_NONE','soap_version'=> 'SOAP_1_1');
		    		$client = new \SoapClient("https://cardpay.shengpay.com/api-acquire-channel/services/refundService?wsdl", $options);
		    		
		    		$header = array(
		    				'serviceCode'=>$sftparamarr['serviceCode'],
		    				'version'=>$sftparamarr['version'],
		    				'charset'=>$sftparamarr['charset'],
		    				'traceNo'=>$sftparamarr['traceNo'],
		    				'senderId'=>$sftparamarr['senderId'],
		    				'sendTime'=>$sftparamarr['sendTime']
		    		);
		    		
		    		$body = array(
		    				'merchantNo'=>$sftparamarr['merchantNo'],
		    				'refundOrderNo'=>$sftparamarr['refundOrderNo'],
		    				'originalOrderNo'=>$sftparamarr['originalOrderNo'],
		    				'refundAmount'=>$sftparamarr['refundAmount'],
		    				'refundRoute'=>$sftparamarr['refundRoute'],
		    				'notifyURL'=>$sftparamarr['notifyURL']
		    		);
		    		
		    		$signature=array(
		    				'signType'=>$sftparamarr['signType'],
		    				'signMsg'=>$sftparamarr['signMsg']
		    		);
		    		
		    		$refundRequest= array(
				        'Header'=>$header,
				        'Body'=>$body,
				        'signature'=>$signature
				    );
		    		
		    		$transResponse = $client->__soapCall('processRefund', array(array('arg0'=>$refundRequest)),array('location' => 'https://cardpay.shengpay.com/api-acquire-channel/services/refundService'));
		    		$respTrans= $transResponse->return;
		    		var_dump($respTrans);exit;
		    	} catch (Exception $e) {
		    	
		    	
		    	} 
    			return json_encode([
    					'errorcode'=>'0',
    					'msg'=>$sftparamarr,
    					]);
    		}else{
    			return  json_encode([
    					'errorcode'=>'1001',
    					'msg'=>'网络异常，稍后再试',
    					]);
    		}
    	}else{
    		return  json_encode([
    				'errorcode'=>'1001',
    				'msg'=>'该订单不存在',
    				]);
    	}
    }
    
    
    /**
     * 退款通知回调
     */
    public function actionSfthnotify(){
    	$sftparamarr = array();
    	$sftparamarr['serviceCode'] = $_POST['serviceCode'];
    	$sftparamarr['version'] = $_POST['version'];
    	$sftparamarr['charset'] = $_POST['charset'];
    	$sftparamarr['traceNo'] = $_POST['traceNo'];
    	$sftparamarr['sendTime'] = $_POST['sendTime'];
    	$sftparamarr['senderId'] = $_POST['senderId'];
    	$sftparamarr['signType'] = $_POST['signType'];
    	$sftparamarr['signMsg']= $_POST['signMsg'];
    	$sftparamarr['refundOrderNo'] = $_POST['refundOrderNo'];
    	$sftparamarr['originalOrderNo'] = $_POST['originalOrderNo'];
    	$sftparamarr['refundTransNo'] = $_POST['refundTransNo'];
    	$sftparamarr['transNo'] = $_POST['transNo'];
    	$sftparamarr['orderAmount'] = $_POST['orderAmount'];
    	$sftparamarr['refundAmount'] = $_POST['refundAmount'];
    	$sftparamarr['refundTime'] = $_POST['refundTime'];
    	$sftparamarr['desc'] = $_POST['desc'];
    	$sftparamarr['status'] = $_POST['status'];
    	$sftparamarr['errorCode'] = $_POST['errorCode'];
    	$sftparamarr['$status'] = $_POST['errorMsg'];
    	$ret = '';
    	$sftpayutil = new Sftpayutil();
    	switch ($sftparamarr['signType']){
    		case "MD5":
    			$mysignMsg = $sftpayutil->getSignMsg($sftparamarr,yii::$app->params['sftpay']['key']);
    			if($mysignMsg==$sftparamarr['signMsg'] and $sftparamarr['status']=="01"){
    				$ret=1;
    			}
    			break;
    		default:
    			break;
    	}
    	if($ret=='01'){
    		$order = Order::findOne(['transaction_id'=>$sftparamarr['originalOrderNo'],'state'=>2]);
    		$refund = Refund::findOne(['transaction_id'=>$sftparamarr['originalOrderNo']]);
    		if($order && $refund){
    			$order->state = 4;//4已退款
    			$refund->state =2; //退款成功并回调
    			if($order->save() && $refund->save()){
    				echo "OK";
    			}
    		}
    	}
    }
}