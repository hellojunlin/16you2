<?php
namespace backend\controllers;

use yii;
use backend\controllers\BaseController;
use common\common\Helper;
use yii\data\Pagination;
use common\models\Active;
use common\models\Gamecurrency;
use common\models\User;

class CurrencyController extends BaseController
{
	public function actionIndex(){
        //分页
        $curPage = Yii:: $app->request->get( 'page',1);
        $pageSize = yii::$app->params['pagenum'];
        //搜索
        $start_time = Yii:: $app->request->get('starttime');
        $end_time = Yii:: $app->request->get('endtime');
        $starttime = $start_time?strtotime($start_time):'';
        $endtime = $end_time?strtotime($end_time)+3600*24:time();
        $username = Yii:: $app->request->get('username','');
        $search = ($username)?['like','username',$username]: '';
        $where ='';
        ($starttime) && $where = "gg.createtime between $starttime and $endtime";
        
        $query = (new \yii\db\Query())
                ->select('gg.id,gg.currencynum,gg.state,gg.createtime,gg.checkcreatetime,gu.username,gu.head_url,gu.Unique_ID,gg.remark')
                ->from('g_gamecurrency AS gg')
                ->leftJoin('g_user AS gu','gu.id = gg.uid')
                ->where('gg.source=0')
                ->andWhere($where)
                ->orderBy('gg.createtime desc');
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        $data['data'] =  ($data['data'])?$data['data']->all():'';
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        
        //查询总游币
        $allgamecurrency = (new \yii\db\Query())
       					   ->select('sum(gg.currencynum) as num')
       					   ->from('g_gamecurrency AS gg')
       					   ->leftJoin('g_user AS gu','gu.id = gg.uid')
       					   ->where($where)
       					   ->andWhere('gg.state=1')
       					   ->andWhere($search)
       					   ->all();
        //查询已使用的游币
        $usegamecurrency = (new \yii\db\Query())
       					   ->select('sum(gg.price) as num')
       					   ->from('g_order AS gg')
       					   ->leftJoin('g_user AS gu','gu.id = gg.uid')
       					   ->where($where)
       					   ->andWhere('ptype=8 and state=2')
       					   ->andWhere($search)
       					   ->all();
        
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/currency/index.html';
        $ispass = $this->ispasscurrency();
        return $this->render('index', [
                'data' => $data, 
                'pages' => $pages,
        		'starttime'=>$start_time,
        		'endtime'=>$end_time,
        		'ispass'=>$ispass,
        		'allgamecurrency'=>$allgamecurrency,
        		'usegamecurrency'=>$usegamecurrency,
        		'username'=>$username,
            ]);
	}
	
	/**
	 * 审核发送游币
	 */
	public function actionPasscurrency(){
		if(yii::$app->request->isAjax && isset($_POST['id'])){
			$ispass = $this->ispasscurrency();  //查看登录是否超时
			if($ispass){ //登录超时，请重新登录
				return json_encode([
						'errorcode'=>'1002',
						'msg'=>'登录超时，请重新登录'
						]);
			}
			$id = Helper::filtdata($_POST['id'],'INT');
			if(!$id){
				return json_encode([
						'errorcode'=>1001,
						'msg'=>'请求参数错误，请刷新重试！'
						]);
			}
			$connection = Yii::$app->db->beginTransaction();//开启事务
			$gamecurrency = Gamecurrency::findOne(['id'=>$id]);	 
			if(!$gamecurrency){
				return json_encode([
						'errorcode'=>1001,
						'msg'=>'该记录不存在，请刷新重试！'
						]);
			}
			if($gamecurrency->state==1){
				return json_encode([
						'errorcode'=>1001,
						'msg'=>'已审核通过，请刷新重试',
						]);
			}
			$gamecurrency->state = 1;  //state 是否审核通过  0：未审核   1：已审核
			$gamecurrency->checkcreatetime = time();
			$user = User::findOne(['id'=>$gamecurrency->uid]);  //修改用户表的币值
			if(!$user){ 
				return json_encode([
						'errorcode'=>1001,
						'msg'=>'用户不存在，请刷新重试',
						]);
			}
			$user->currencynum = $user->currencynum + $gamecurrency->currencynum;
			if($gamecurrency->save() && $user->save()){
				$connection->commit();//事物提交
				$checkcreatetime = date('Y-m-d H:i:s',$gamecurrency->checkcreatetime);
				return json_encode([
						'errorcode'=>0,
						'msg'=>'审核成功',
						'checkcreatetime'=>$checkcreatetime,
						]);
			}else{
				$connection->rollBack();//事物回滚
				return json_encode([
						'errorcode'=>1001,
						'msg'=>'审核失败，请刷新重试！'
						]);
			}
		}else{
			return json_encode([
					'errorcode'=>1001,
					'msg'=>'请求错误，请刷新重试！'
			]);
		}
	}
	
	/**
	 * 登录游币审核
	 */
	public function actionLogincurrency(){
		
		if(yii::$app->request->isAjax && isset($_POST['passwordvalue'])){
			$passwordvalue = Helper::filtdata($_POST['passwordvalue']);   //获取的密码
			if($passwordvalue===false){
				return json_encode([
						'errorcode'=>'1001',
						'msg'=>'密码错误，请重新输入'
				]);
			}
			if(md5($passwordvalue)=='5fcfac1fa81ba1d435b9a952b8279bf9'){
				yii::$app->session->set('ispasscurrency',time()); //更新session
				return json_encode([
						'errorcode'=>'0',
						'msg'=>'登录成功'
						]);
			}else{
				return json_encode([
						'errorcode'=>'1001',
						'msg'=>'密码错误，请重新输入'
						]);
			}
		}else{
			return json_encode([
					'errorcode'=>'1001',
					'msg'=>'请求错误,请重新再试！'
					]);
		}
	}
	
	/**
	 * 登录是否过期  过期返回true  否则返回false
	 */
	private function ispasscurrency(){
		if(yii::$app->session->get('ispasscurrency')){
			$showtime = yii::$app->session->get('ispasscurrency');
			if(time()-$showtime>600){//时间超过十分钟则重新登录
				return true;
			}else{
				return false;
			}
		}else{
			return true;
		}
	}
	
	/**
	 * 删除未审核的游币申请
	 */
	public function actionDel(){
		if(yii::$app->request->isAjax && isset($_POST['id'])){
			$id = Helper::filtdata($_POST['id'],'INT');
			if(!$id){
				return json_encode([
						'errorcode'=>1001,
						'msg'=>'请求参数错误，请刷新重试！'
						]);
			}
			$currency = Gamecurrency::deleteAll(['id'=>$id,'state'=>0]);
			if($currency){
				return json_encode([
						'errorcode'=>0,
						'msg'=>'删除成功',
						]);
			}else{
				return json_encode([
						'errorcode'=>1001,
						'msg'=>'删除失败',
						]);
			}
		}else{
			return json_encode([
					'errorcode'=>'1001',
					'msg'=>'请求错误,请重新再试！'
					]);
		}
	}
}