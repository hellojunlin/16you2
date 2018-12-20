<?php
namespace backend\controllers;

use yii;
use backend\controllers\BaseController;
use common\common\Helper;
use yii\data\Pagination;
use common\models\Active;
use common\models\Gamecurrency;
use common\models\User;
use common\models\Rebaterecord;
use common\common\Youwxpayutil;
use common\common\Wxinutil;

class RebateController extends BaseController
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
        ($starttime) && $where = "gr.createtime between $starttime and $endtime";
        
        $query = (new \yii\db\Query())
                ->select('gr.id,gr.rebatenum,gr.state,gr.createtime,gr.passtime,gu.username,gu.head_url,gu.id as uid,gu.Unique_ID,gr.remark')
                ->from('g_rebaterecord AS gr')
                ->leftJoin('g_user AS gu','gu.id = gr.uid')
                ->where($where)
                ->orderBy('gr.createtime desc');
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        $data['data'] =  ($data['data'])?$data['data']->all():'';
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        
        //查询已返利的金额
        $allrebatearr = (new \yii\db\Query())
				        ->select('sum(gr.rebatenum) as num')
				        ->from('g_rebaterecord AS gr')
				        ->leftJoin('g_user AS gu','gu.id = gr.uid')
				        ->where($where)
				        ->andWhere('state=1')
				        ->andWhere($search)
				        ->all();
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/rebate/index.html';
        $ispass = $this->ispassrebate();
        return $this->render('index', [
                'data' => $data, 
                'pages' => $pages,
        		'starttime'=>$start_time,
        		'endtime'=>$end_time,
        		'ispass'=>$ispass,
        		'allrebatearr'=>$allrebatearr,
        		'username'=>$username,
            ]);
	}
	
	/**
	 * 审核返利
	 */
	public function actionPassrebate(){
		if(yii::$app->request->isAjax && isset($_POST['id']) && isset($_POST['uid']) && isset($_POST['type'])){
			$ispass = $this->ispassrebate();  //查看登录是否超时
			if($ispass){ //登录超时，请重新登录
				return json_encode([
						'errorcode'=>'1002',
						'msg'=>'登录超时，请重新登录'
						]);
			}
			$id = Helper::filtdata($_POST['id'],'INT');
			$uid = Helper::filtdata($_POST['uid'],'INT');
			$type= Helper::filtdata($_POST['type']);
			if(!$id || !$uid){
				return json_encode([
						'errorcode'=>1001,
						'msg'=>'请求参数错误，请刷新重试！'
						]);
			}
			$connection = Yii::$app->db->beginTransaction();//开启事务
			$rebaterecord = Rebaterecord::findOne(['id'=>$id,'state'=>0]);	 
			$user = User::findone(['id'=>$uid]);
			if(!$rebaterecord || !$user){
				return json_encode([
						'errorcode'=>1001,
						'msg'=>'该记录不存在，请刷新重试！'
						]);
			}
			if($rebaterecord->state==1){
				return json_encode([
						'errorcode'=>1001,
						'msg'=>'已审核通过，请刷新重试',
						]);
			}
			if($type==1){//小程序审核申请
				$url = 'http://xgame.nj6080.com/user/changestate.html';
				$data['uid'] = $rebaterecord->uid;
				$data['ordernum'] = $rebaterecord->ordernum;
				$wxinutilres = json_decode(Wxinutil::https_request($url,$data),true);
				if($wxinutilres['errorcode']!=0){
					return json_encode(['errorcode'=>1001,'msg'=>'接口异常，请联系后台管理员']);
				}
			}
			$rebaterecord->state = 1;  //state 是否审核通过  0：未审核   1：已审核
			$rebaterecord->passtime = time();
			if($rebaterecord->save() ){
				if($rebaterecord->rebatenum<1){//金额小于1 不符合微信企业支付规则
					return json_encode([
							'errorcode'=>1001,
							'msg'=>'金额不能小于1元',
							]);
				}
				$partner_trade_no = ('fl'.date('YmdHis',time()).rand(0,9999));
				$wx = new Youwxpayutil();
				$res = $wx->sendredpacket($user->openid,$partner_trade_no,$rebaterecord->rebatenum,'16游'); //发红包
				$res = true;
				if($res){//红包发送成功
					$connection->commit();//事物提交
					$checkcreatetime = date('Y-m-d H:i:s',$rebaterecord->passtime);
					return json_encode([
							'errorcode'=>0,
							'msg'=>'审核成功',
							'checkcreatetime'=>$checkcreatetime,
							]);
				}else{
					$connection->rollBack();//事物回滚
					return json_encode([
							'errorcode'=>1001,
							'msg'=>'红包发送失败，请查看微信账户资金是否充足',
							]);
				}
			}else{
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
	 * 登录返利审核
	 */
	public function actionLoginrebate(){
		
		if(yii::$app->request->isAjax && isset($_POST['passwordvalue'])){
			$passwordvalue = Helper::filtdata($_POST['passwordvalue']);   //获取的密码
			if($passwordvalue===false){
				return json_encode([
						'errorcode'=>'1001',
						'msg'=>'密码错误，请重新输入'
				]);
			}
		if(md5($passwordvalue)=='5fcfac1fa81ba1d435b9a952b8279bf9'){
				yii::$app->session->set('ispassrebate',time()); //更新session
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
	private function ispassrebate(){
		if(yii::$app->session->get('ispassrebate')){
			$showtime = yii::$app->session->get('ispassrebate');
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
	 * 删除未审核的返利申请
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
			$rebaterecord = Rebaterecord::deleteAll(['id'=>$id,'state'=>0]);
			if($rebaterecord){
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