<?php
namespace pc\controllers;

use yii;
use pc\controllers\BaseController;
use common\models\Plateform;
use common\models\Product;
use common\models\Carousel;
use common\common\Helper;
use common\models\Integral;
use common\models\Exchange;
use common\models\User;
use common\models\Rule;
use yii\base\Object;
use common\redismodel\ExchangeRedis;
use common\redismodel\UserRedis;
use common\redismodel\CarouselRedis;
/**
 * 商城页
 * @author junlin
 */
class IntegralController extends BaseController{
	//商城首页
	public function actionIndex(){
		yii::$app->session['typemenu'] = 3;
	    return $this->render('integindex');
	}
	
	/**
	 * 获取商城首页数据
	 * @return string
	 */
	public function actionGetdata(){
		$dataarr = array();
		$page = isset($_GET['page'])?$_GET ['page']:0;
		if($page==0){ //第一次获取数据
			$carousel = CarouselRedis::find()->where(['state'=>2])->limit(5)->asArray()->all();
			if(!$carousel){ //redis 不存在时则去数据库查询
				$carousel = Carousel::find()->limit(5)->andWhere(['state'=>2])->orderBy('sort asc')->asArray()->all(); //轮播图
				if($carousel){//数据库存在
						foreach ($carousel as $key => $val) {
							$redis = new CarouselRedis();
							$redis->id = $val['id'];
							$redis->url = $val['url'];
							$redis->image = $val['image'];
							$redis->createtime = $val['createtime'];
							$redis->state = $val['state'];
							$redis->sort = $val['sort'];
							$redis->save();
						}
					}
			}else{//存在时则按sort排序
				$carousel = Helper::quick_sort($carousel,'sort','SORT_ASC');//按某个字段排序
			}
			$dataarr['carousel'] = $carousel;
			$user = yii::$app->session['user'];
			$dataarr['integral'] = $user->integral; //用户积分
			//查询最新50条公告
			$notice = (new \yii\db\Query())
			->select('ge.product_name,gu.username')
			->from('g_exchange as ge')
			->leftJoin('g_user as gu','ge.uid=gu.id')
			->orderBy('ge.createtime desc')
			->limit(50)
			->all();
			$dataarr['notice'] = $notice;
		}
    	$pagenum = 50;    //当前页面的显示数量
    	$start =  $pagenum * ($page); //当前页面开始显示的行数
		//查询50条商品记录
		$product = Product::find()->where(['state'=>1])->andWhere('number!=0')->asArray()->offset($start)->limit($pagenum)->orderBy('createtime desc')->select('id,product_name,image_url,integral,number')->all();
		$dataarr['datas'] = $product;
		$dataarr['code'] = 0; 
		if(!$product){
			$dataarr['code'] = 1001;
		}
		return json_encode($dataarr);
	}
	
	
	//积分记录页
	public function actionTorecord(){
		return  $this->render('integralrecord');
	}
	
	/**
	 * 获取积分页面数据
	 */
	public function actionGetintegraldata(){
		$dataarr =  array();
		$page = isset($_GET['page'])?$_GET ['page']:0;
		$user = yii::$app->session['user'];
		$pagenum = 50;
		$start =  $pagenum * ($page); //当前页面的显示数量
		$integral = Integral::find()->asArray()->where(['uid'=>$user->id])->offset($start)->limit($pagenum)->asArray()->orderBy('createtime desc')->all();
		$dataarr['head_url'] = $user->head_url;
		$dataarr['integral'] = $user->integral; //用户积分
		$dataarr['recordlists'] = $integral;
		$dataarr['code'] = 0;
		if(!$integral){
			$dataarr['code'] = 1001;
		}
		return json_encode($dataarr);
	}
	
    //商品详情页
	public function actionTodetail(){
		if(!isset($_GET['id'])){
			return $this->redirect('/common/error.html');
			exit;
		}
		yii::$app->session['pr_id'] = Helper::filtdata($_GET['id'],'INT');
		return  $this->renderPartial('changedetail');
	}

	/*
	 * 获取商品详情页数据
	 */
	public function actionDetaildata(){
		$id = yii::$app->session['pr_id'];
		if(!$id){
			$this->redirect('/integral/index.html');
		}
		$model = Product::find()->where(['id'=>$id])->asArray()->one();
		$user = yii::$app->session['user'];
		$dataarr['product'] = $model;
		$dataarr['user'] = $user->integral;
		return json_encode($dataarr);
	}	
	
	/**
	 * 跳转兑换记录页
	 */
	public function actionTochangetail(){
		return $this->render('changerecord');
	}
	
	/**
	 * 获取兑换记录数据
	 */
	public function actionGetchangedata(){
		$user = yii::$app->session['user'];
		$page = isset($_GET['page'])?$_GET ['page']:0;
		$pagenum = 20;
		$start =  $pagenum * ($page); //当前页面的显示数量
		$exchange = Exchange::find()->where(['uid'=>$user->id])->offset($start)->limit($pagenum)->asArray()->orderBy('createtime desc')->all();
		$dataarr['code'] = 0;
		if($exchange){
			$dataarr['datas'] = $exchange;
		}else{
			$dataarr['code'] = 1001;
			$dataarr['datas'] = '';
		}
		return  json_encode($dataarr); 
	}
	
	/**
	 * 立即兑换
	 */
	public function actionToarea(){
		if(yii::$app->request->isAjax||isset($_POST['phone'])||isset($_POST['area'])||isset($_POST['product_name'])){
			$connection = Yii::$app->db->beginTransaction();//开启事务
			$model = new Exchange();
			$model->phone = Helper::filtdata(yii::$app->request->post('phone'),'INT');
			$model->area = trim(yii::$app->request->post('area'));
			$model->product_name = Helper::filtdata(yii::$app->request->post('product_name'));
			$model->integral = Helper::filtdata(yii::$app->request->post('integral'));
			$model->uid = yii::$app->session['user']->id;
			$model->createtime = time();
			$model_res = $model->save(); //保存兑换记录
			$now = strtotime(date('Y-m-d'));
	        $after = time();
	        $where = "createtime BETWEEN $now AND $after";
	        $_model = clone $model;
	        $exchange = $_model->find()->where($where)->andWhere(['uid'=>$model->uid])->count();//统计今天是兑换条数
			$user = User::findOne($model->uid);
			$user->integral = $user->integral - $model->integral;
			if($exchange==1){//判断是否为每日首换
				$rank = yii::$app->params['getintegral']['rank'];//每日首换所获得的积分
				$user->integral = $user->integral+$rank;
				$integral = new Integral();
				$integral->type = 0;
				$integral->integral = $rank;
				$integral->uid = $model->uid;
				$integral->createtime = time();
				$integral->save();
			}
			$user_res = $user->save();//保存用户信息
			$product = Product::findOne(['product_name'=>$model->product_name]);
			$product->number = $product->number - 1;//被兑换的商品数减1
			$product_res = $product->save(); 
			if($model_res && $user_res && $product_res){
				$connection->commit();//事物提交
				yii::$app->session['user'] = $user;//更新用户session
				$userredis = UserRedis::findOne($user->id);
				$userredis && $this->updateUserRedis($user, $userredis);
				$redis = new ExchangeRedis();//兑换记录存进redis
				$redis->product_name = $model->product_name;
				$redis->integral = $model->integral;
				$redis->area = $model->area;
				$redis->uid = $model->uid;
				$redis->phone = $model->phone;
				$redis->createtime = $model->createtime;
				$redis->username = yii::$app->session['user']->username;
				$redis->save();
				return json_encode([
						'errorcode'=>0,
						'info'=>'兑换成功',
				]); 
			}else{
				$connection->rollBack();//事物回滚
				return json_encode([
						'errorcode'=>1000,
						'info'=>$model->integral,
				]);
			}
		}
	}
	
	/**
	 * 积分规则
	 * state 1启用 0禁用
	 * type 0积分规则 1游戏金榜
	 */
	public function actionRules(){
		$content = Rule::find()->where(['state'=>1,'type'=>0])->orderBy('createtime desc')->select('content')->one();
		if($content) $content = $content['content'];
		return $this->renderPartial('rules',[
				'content'=>$content,
		]);
	}
	
	/**
	 * 更新用户redis缓存
	 * @param unknown $user
	 * @param unknown $userredis
	 */
	private function updateUserRedis($user,$userredis){
		if($userredis){//存在更新
			$userredis->integral = $user->integral;
			$userredis->save();
		}else{//重新保存
			$uredis = NEW UserRedis();
			$uredis->id = $user->id;
			$uredis->openid = $user->openid;
			$uredis->pid = $user->pid;
			$uredis->username = $user->username;
			$uredis->sex = $user->sex;
			$uredis->head_url = $user->head_url;
			$uredis->province = $user->province;
			$uredis->city = $user->city;
			$uredis->integral = $user->integral;
			$uredis->gid = $user->gid;
			$uredis->phone = $user->phone;
			$uredis->access_token = $user->access_token;
			$uredis->createtime = $user->createtime;
			$uredis->save();
		}
	}
}