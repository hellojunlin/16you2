<?php 
namespace pc\controllers;

use yii;
use yii\web\Controller;
use yii\base\Exception;
use common\models\Rebatecurrencytemp;
use common\common\Helper;
use common\models\Gamecurrency;
use common\models\User;
					
/**
 * 2018-5-1 五一活动
 * @author junlin
 * date:2018-4-21
 */		
 class MayactiveController extends Controller{
 	/**
 	 * 获取领取或者未领取的游币记录
 	 * $type 0:未领取  1：已领取
 	 */
 	public function actionGetrecord(){
 		$user = yii::$app->session['user'];
 	    if(!isset(yii::$app->session['user'])||empty(yii::$app->session['user'])){
    		return json_encode([
	     			'errorcode'=>1008,
	     			'info'=>'该用户未登陆',
	     			]);
    	} 
	 	if(!yii::$app->request->isAjax || !isset($_POST['type']) || !isset($_POST['page'])){
	            return json_encode([
	                    'errorcode'=>1001,
	                    'info'=>'数据错误，请稍后再试',
	            ]);
	     }
	     $type = Helper::filtdata($_POST['type'],'INTEGER');    //查询的类型  0:未领取记录   1：已领取记录
	     $page = Helper::filtdata($_POST['page'],'INT'); //页码
	     $pagesize = 10;  //条数
	     if($type===false){
	     	return json_encode([
	     			'errorcode'=>1001,
	     			'info'=>'数据错误，请稍后再试',
	     			]); 
	     } 
	     $rebatecurrencytemp = Rebatecurrencytemp::find()->where(['uid'=>$user->id,'isdraw'=>$type])->offset(0)->limit($pagesize)->asArray()->select('id,rebatecurrency,drawtime,type')->all();  //查询该用户的领取记录
 		 $errorcode = empty($rebatecurrencytemp) ?1002 : 0; 
 		 \Yii::$app->session->set('rebatecurrencytemparr',$rebatecurrencytemp);//讲查询结果存入session,作为异步读取时使用
 		 return json_encode([
 		 				'errorcode'=>$errorcode,
						'info'=>$rebatecurrencytemp,
 				 ]);
 	}
 	
 	
 	/**
 	 * 上拉加载 异步获取数据
 	 */
 	public function actionGetpro(){
	     if(Yii::$app->request->isAjax && isset($_POST['page'])|| !isset($_POST['type'])){
	     	$user = yii::$app->session['user'];
	     	if(!isset(yii::$app->session['user'])||empty(yii::$app->session['user'])){
	     		return json_encode([
	     				'errorcode'=>1008,
	     				'info'=>'该用户未登陆',
	     				]);
	     	}
	     	$page = Helper::filtdata($_POST['page'],'INT');
	     	$type = Helper::filtdata($_POST['type'],'INTEGER');    //查询的类型  0:未领取记录   1：已领取记录
	     	if($type===false||!$page){
	     		return json_encode([
	     				'errorcode'=>1001,
	     				'info'=>'数据错误，请稍后再试',
	     				]);
	     	}
	     	$plist = Yii::$app->session->get('rebatecurrencytemparr');	//从缓存中获取列表，防止数据更新排名后，一些用户重复出现或未出现
	     	$list=null;
	     	$pagenum = 10;           //显示的数量
	     	$start =  $pagenum * ($page-1); //当前页面的显示数量
	     	$end =  $pagenum * ($page);          //加载此页时应显示的数量
	     	$actSessionlen = count($plist);		//当前缓存中数组的长度
	     	if($end>$actSessionlen){//总数若是大于缓存中的总数,则继续从数据表中读取数据
	     		$pagesize = yii::$app->params['limitnum'];
	     		$rebatecurrencytemp = Rebatecurrencytemp::find()->where(['uid'=>$user->id,'isdraw'=>$type])->offset($actSessionlen)->limit($pagesize)->asArray()->select('id,rebatecurrency,drawtime,type')->all();  //查询该用户的领取记录
	     		$plist =array_merge($plist,$rebatecurrencytemp);
	     		Yii::$app->session->set('rebatecurrencytemparr',$plist);
	     	}
	     	if($plist!=null){
	     		$alist = array_splice ( $plist, $start, $pagenum );
	     		foreach($alist as $k=>$v){
	     			$list[$k]['id'] = $v['id'];
	     			$list[$k]['rebatecurrency'] = $v['rebatecurrency'];
	     			$list[$k]['drawtime'] = $v['drawtime'];
	     			$list[$k]['type'] = $v['type'];
	     		}
	     	}
	     	$errorcode = ($list!=null)?'0':'1002';	//为空则返回1002码
	     	return json_encode(['errorcode'=>$errorcode,'info'=>$list]);
	     }
	}
 	
 	
 	/**
 	 * 获取未领取记录
 	 */
 	public function actionGetnotdraw(){
 		if(!yii::$app->request->isAjax){
 			return json_encode([
 					'errorcode'=>1001,
 					'info'=>'数据错误，请稍后再试',
 					]);
 		}
 		$user = yii::$app->session['user'];
	    if(!isset(yii::$app->session['user'])||empty(yii::$app->session['user'])){
	     		return json_encode([
	     				'errorcode'=>1008,
	     				'info'=>'该用户未登陆',
	     				]);
	    }
 		$rebatecurrencytemp = Rebatecurrencytemp::find()->where(['uid'=>$user->id,'isdraw'=>0])->select('id,rebatecurrency,drawtime,type')->asArray()->one();  //查询该用户的领取记录
 		$isboolean = empty($rebatecurrencytemp) ?false : true;
 		return json_encode([ 
 				'errorcode'=>0,
 				'info'=>$rebatecurrencytemp,
 				'isboolean'=>$isboolean,
 				]); 
 	} 
 	
 	
 	
 	
 	/**
 	 * 领取金币
 	 * 对三张表操作
 	 * rebatecurrencytemp : 五一充值返利表
 	 * gamecurrency : 游币记录表
 	 * user : 用户表
 	 * 
 	 */
 	public function actionDrawcurrency(){
 		if(!yii::$app->request->isAjax || !isset($_POST['rid'])){
 			return json_encode([
 					'errorcode'=>1001,
 					'info'=>'数据错误，请稍后再试',
 					]);
 		}
 		$user = yii::$app->session['user'];
	    if(!isset(yii::$app->session['user'])||empty(yii::$app->session['user'])){
	     		return json_encode([
	     				'errorcode'=>1008,
	     				'info'=>'该用户未登陆',
	     				]);
	    }
 		$rid = Helper::filtdata($_POST['rid'],'INT'); //充值返利表ID
 		$connection = Yii::$app->db->beginTransaction();//开启事务
 		$rebatecurrencytemp = Rebatecurrencytemp::findOne(['id'=>$rid,'uid'=>$user->id,'isdraw'=>0]);   //查询该返利记录
 		if(!$rebatecurrencytemp){
 			return json_encode([
 					'errorcode'=>1001,
 					'info'=>'网络异常，请稍后再试', 
 					]);
 		}
 		$rebatecurrencytemp->isdraw = 1;    //是否领取  0:未领取  1 已领取
 		$rebatecurrencytemp->drawtime = time();
 		if($rebatecurrencytemp->save()){
 			//游币记录表添加游币记录
 			$gamecurrency = new Gamecurrency();
 			$gamecurrency->uid = $rebatecurrencytemp->uid;
 			$gamecurrency->state = 1;   //状态 （0：未审核   1：审核通过）
 			$gamecurrency->currencynum = $rebatecurrencytemp->rebatecurrency;          //游币值
 			$gamecurrency->createtime = time();
 			$gamecurrency->checkcreatetime = time();
 			$gamecurrency->source = 1;   //来源  1：五一活动
 			if($gamecurrency->save()){
 				//修改用户币值
 				$userrecord = User::findOne(['id'=>$user->id]);
 				if($userrecord){
 					$userrecord->currencynum = $userrecord->currencynum+$rebatecurrencytemp->rebatecurrency;   //用户表的游币值
 					if($userrecord->save()){
 						$connection->commit();//事物提交
 						return json_encode([
 								'errorcode'=>0,
 								'info'=>'领取成功',
 								]);
 					}else{
 						$connection->rollBack();//事物回滚
 						return json_encode([
 								'errorcode'=>1001,
 								'info'=>'领取失败',
 								]);
 					}
 				}else{
 					$connection->rollBack();//事物回滚
 					return json_encode([
 							'errorcode'=>1001,
 							'info'=>'领取失败',
 							]);
 				}
 			}else{
 				$connection->rollBack();//事物回滚
 				return json_encode([
 						'errorcode'=>1001,
 						'info'=>'领取失败',
 						]);
 			}
 		}
 	}
}