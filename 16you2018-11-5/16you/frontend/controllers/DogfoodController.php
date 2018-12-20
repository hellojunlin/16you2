<?php 
namespace frontend\controllers;

use yii;
use frontend\controllers\BaseController;
use common\models\Dogexchange;
use common\models\Acquire;
use common\common\Helper;
use common\common\Wxpayutil;

/**
 * 狗粮活动 
 * @author HE
 */
class DogfoodController extends BaseController{ 

	/**
	 *换狗粮首页 
	 */
	public function actionIndex(){
		//判断是否有openid,用于兑换红包
		if(!isset(yii::$app->session['rpopenid'])||!yii::$app->session['rpopenid']){
			yii::$app->session['activeserver'] = $_SERVER['REQUEST_URI'];
			$this->toAuth();
		}

		$uid = yii::$app->session->get('user')->id;
		//查出总的狗粮数
		$day = strtotime(date('Y-m-d'));
		$acquire = Acquire::find()->where(['uid'=>$uid])->andWhere(['>=',"createtime",$day])->select('sum(num) as num')->asArray()->one();
		$num = 0;//所剩狗粮
		$price = 0;//兑换的总金额
		if($acquire['num']!=NULL){
			$num = $acquire['num'];
			//查出兑换的记录 总金额 和 兑换的狗粮总数
			$dog = Dogexchange::find()->where(['uid'=>$uid,'state'=>1])->select('sum(num) as num,sum(price) as price')->asArray()->one();
			if($dog['num']!=NULL){
				$num = $num-$dog['num'];
				$price = $dog['price'];
			}
		}
		yii::$app->session['acquire_num'] = $num;
		yii::$app->session['acquire_price'] = $price;
		yii::$app->session['typemenu'] = 8;
		$openid = yii::$app->session->get('user')->openid;
		return $this->renderPartial('index',[
			'num'=>$num,
			'price'=>$price,
			'uid'=>$uid,
			'openid'=>$openid,
		]);
	}

	/**
	 *充值页
	 */
	public function actionRecharge(){
		$uid = yii::$app->session->get('user')->id;
		$openid = yii::$app->session->get('user')->openid;

		$res = Acquire::find()->where(['uid'=>$uid,'type'=>3])->select('sum(num) as num')->one();
		$num = 0;
		if($res['num'])
			$num = $res['num'];

		return $this->renderPartial('recharge',[
				'num'=>$num,
				'uid'=>$uid,
				'openid'=>$openid,
		]);
	}

	/**
	 * 兑换红包
	 * @return [type] [description]
	 */
	public function actionExchange(){
		if(!yii::$app->request->isAjax){
			return json_encode([
				'errorcode'=>1001,
				'info'=>''
			]);
		}
		$starttime = 1510243200;//2017.11.10
		$endtime = 1511280000;//2017.11.22 00:00:00
		$day = strtotime(date('Y-m-d'));
		if($starttime>$day || ($endtime<$day)){
			return json_encode([
				'errorcode'=>1991,
				'info'=>'活动时间为2017-11-10至2017-11-21',
			]);
		}
		$user = yii::$app->session['user'];
		$uid = $user->id;
		//查出总的狗粮数
		if(!yii::$app->session['acquire_num']||!yii::$app->session['acquire_price']){
			$acquire = Acquire::find()->where(['uid'=>$uid])->andWhere(['>=',"createtime",$day])->select('sum(num) as num')->asArray()->one();
			$num = 0;//所剩狗粮
			$rprice = 0;//兑换的总金额
			if($acquire['num']!=NULL){
				$num = $acquire['num'];
				//查出兑换的记录 总金额 和 兑换的狗粮总数
				$dog = Dogexchange::find()->where(['uid'=>$uid,'state'=>1])->select('sum(num) as num,sum(price) as price')->asArray()->one();
				if($dog['num']!=NULL){
					$num = $num-$dog['num'];
					$rprice = $dog['price'];
				}
			}
		}else{
			$num = yii::$app->session['acquire_num'];
			$rprice = yii::$app->session['acquire_price'];
		}
		if($num>=1111){
			$connection = Yii::$app->db->beginTransaction();//开启事务
			$dog = new Dogexchange();
			$dog->uid = $uid;
			$dog->num = 1111;
			$dog->price = 11;
			$dog->createtime = time();
			$rdog = $dog->save();

			//获取用户openid
			if(!isset(yii::$app->session['rpopenid'])||!yii::$app->session['rpopenid']){
				yii::$app->session['activeserver'] = $_SERVER['REQUEST_URI'];
				$this->toAuth();
				exit;
			}
			$openid = yii::$app->session['rpopenid'];
			$partner_trade_no = 'wxp'.date('YmdHis').rand(1000,9999);
			$price = 11;
			$desc = '16游狗粮兑换';
			//发红包
			$wx = new Wxpayutil();
			$res = $wx->sendredpacket($openid,$partner_trade_no,$price,$desc);
			if($rdog && $res){
				$connection->commit();//事物提交
				$dog->state = 1;
				$dog->save();
				$arr['num'] = $num-1111;
				$arr['price'] = $price+$rprice;
				yii::$app->session['acquire_num'] = $arr['num'];
				yii::$app->session['acquire_price'] = $arr['price'];
				return json_encode([
					'errorcode'=>0,
					'info'=>$arr,
				]); 
			}else{
				$connection->rollBack();//事物回滚
				return json_encode([
					'errorcode'=>1010,
					'info'=>'网络异常，红包发送失败',
				]);
			} 
		}else{
			return json_encode([
				'errorcode'=>2011,
				'info'=>'ummm....你获得的狗粮还太少',
			]);
		}
	}


	/**
	 * 静默授权，获取openid
	 */
	public function toAuth(){
		$appid = yii::$app->params['redpackwinfo']['appid'];
		$state = yii::$app->params['state'];
		$redirect_uri=urlencode('http://'.$_SERVER['HTTP_HOST'].'/luck/getinfo.html');
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=$state#wechat_redirect";
		header("Location:$url");
	}
	
	//获取openid
	public function actionGetinfo(){
		$state = yii::$app->params['state'];
		if(!isset($_GET['code'])&&!isset($_GET['state'])&&($_GET['state']!=yii::$app->params['state'])){	//链接不正确，分发访问
			echo '非法访问';
			exit();
		}
		$code = $_GET['code'];
		$appid = yii::$app->params['redpackwinfo']['appid']; //'wx1874a10fb8e2bf85';
		$secret = yii::$app->params['redpackwinfo']['secret']; //'ece73aac0ca1908b7d68642f961d0960';
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
		$output = Wxinutil::http_get($url);
		$data = json_decode($output);
		if(!isset($data->access_token)){
			return '获取不到权限，非法访问';
		}
		$openid = $data->openid; 
		yii::$app->session['rpopenid'] =  $data->openid;
		/* $url = yii::$app->params['frontend']."/luck/robredpacket.html";
		header("Location:$url"); */
		$headurl = (yii::$app->session['activeserver'])?yii::$app->session['activeserver']:yii::$app->params['frontend'].'/luck/index.html';//原来的地址
		$this->redirect($headurl);
	}
	
}