<?php 
namespace pc\controllers;

use yii;
use pc\controllers\BaseController;
use common\models\Discount;
use common\models\Product;
use common\common\Helper;
use common\models\Integral;
use common\models\Exchange;
use common\models\User;

/**
 * 商城页
 * @author junlin
 */
class IntegralshopController extends BaseController{
	//商城首页
	public function actionIndex(){
		$user = yii::$app->session['user'];
		if(!$user){
			return $this->redirect("/personal/index.html");
			exit;
		}
		//判断是否有当周的折扣
		$mondaytime =  strtotime(date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600))); //本周一时间戳
		$thisweekdiscountobj =   Discount::findOne(['uid'=>$user->id]);    //查找本周折扣  //'mondaytime'=>$mondaytime,
		$isshowdiscount = false;
		if($thisweekdiscountobj){  
			if($thisweekdiscountobj->mondaytime==$mondaytime){ //存在本周折扣则直接获取本周折扣
				$discount = $thisweekdiscountobj->discount;
			}else{//不存在则重新获取本周折扣
				$discount = $this->getdiscount($user, $mondaytime,$thisweekdiscountobj);
				$discount && $isshowdiscount = true;
			}
		}else{//不存在则获取本周折扣 并存入数据库
			$discount = $this->getdiscount($user, $mondaytime,$thisweekdiscountobj);
			$discount && $isshowdiscount = true;
		}
		$noticearr = $this->getnotice();
		yii::$app->session['typemenu'] = 3;     //菜单下标
		$payurl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];   //支付前端跳转页面
	    return $this->render('integralindex',[
	           'isshowdiscount'=>$isshowdiscount,
	    				 'user'=>$user,
	    		     'discount'=>$discount,
	    		    'noticearr'=>$noticearr,
	    			   'payurl'=>$payurl,
	    ]);
	}
	
	/**
	 * 本周折扣
	 */
	private function getdiscount($user,$mondaytime,$discountobj){
		$discountnum = 10;   //10  不打折扣
		$randomnum = rand(0,100);  //随机取0-100的随机数
		if($randomnum<=1){   //1%的概率
			$discountnum = 7;
		}elseif($randomnum>1 && $randomnum<=9){//9%的概率
			$discountnum = 8;
		}elseif($randomnum>9 && $randomnum<=100){ //90%的概率
			$discountnum = 9;
		}
		if(!$discountobj){ //不存在时则新增
			$discountobj = new Discount();
		}
		$discountobj->uid = $user->id;
		$discountobj->discount = $discountnum;
		$discountobj->mondaytime = $mondaytime;
		$discountobj->createtime = time(); 
		$res = $discountobj->save();
		if($res){
			return $discountnum;
		}else{
			return false;
		} 
		
	}
	
	//获取公告数据
	private function getnotice(){
		$noticearr = array();  //公告数组
		$noticearr = (new \yii\db\Query())
					->select('ge.id,ge.product_name,ge.integral,gu.username')
					->from(' g_exchange as ge')
					->leftJoin('g_user as gu','gu.id=ge.uid')
					->orderBy('ge.createtime desc')
					->limit(30) 
					->all();
		return $noticearr;
	}
	
	
	/**
	 * 异步获取积分商品
	 */
	public function actionIntegralgoods(){
		if (!Yii::$app->request->isAjax || !isset ( $_POST ['page'] )){
		      return json_encode(['errorcode'=>1001,'msg'=>'网络异常，请稍后再试！']);
		}
		$page = Helper::filtdata($_POST['page'],'INT');
		if(!$page){
			return json_encode ( ['errorcode' => '1002','list' => null,'msg'=>'网络异常，请稍后再试！']);
		}
		$pageSize = 10;//条数
		$start =  $pageSize * ($page-1);
		$end = $pageSize * $page;
		if($page==1){
			$end = $end+5;
			$productarr = Product::find()->where(['state'=>1])->andWhere(['>','number',0])->offset($start)->limit($end)->asArray()->orderBy('sort desc')->select('id,product_name,type,number,integral,image_url')->all();  //查询15条已启用的商品
			yii::$app->session['productarr'] = $productarr;
			$productarr && $productarr = array_splice ( $productarr, $start, $pageSize );
			$errorcode = ($productarr!=null)?'0':'1003';	//为空则返回1003码
			return json_encode(['errorcode'=>$errorcode,'msg'=>$productarr]);
		}else{
			$plist = yii::$app->session['productarr'];
			$list = null;
			$actSessionlen = count($plist);		//当前缓存中数组的长度
			if($end>$actSessionlen){//总数若是大于缓存中的总数,则继续从数据表中读取数据
				$productarr = Product::find()->where(['state'=>1])->andWhere(['>','number',0])->offset($start)->limit($end)->asArray()->orderBy('sort desc')->select('id,product_name,type,number,integral,image_url')->all();  //查询10条已启用的商品
				$plist =array_merge($plist,$productarr);
				Yii::$app->session->set('productarr',$plist);
			}
			if(!$plist){
				return json_encode(['errorcode'=>1003,'msg'=>'数据加载完成']);
			}
			$alist = array_splice ( $plist, $start, $pageSize);   //数据截取数据
			if($alist){
				foreach($alist as $k=>$v){
					$list[$k]['id'] = $v['id'];
					$list[$k]['product_name'] = $v['product_name'];
					$list[$k]['type'] = $v['type'];
					$list[$k]['number'] = $v['number'];
					$list[$k]['integral'] = $v['integral'];
					$list[$k]['image_url'] = $v['image_url'];
				}
			}
			$errorcode = ($list!=null)?'0':'1003';	//为空则返回1003码
			return json_encode(['errorcode'=>$errorcode,'msg'=>$list]);
		}
	}
	
	/**
	 * 积分来源
	 */
	public  function actionIntegralsource(){
	if (!Yii::$app->request->isAjax || !isset ( $_POST ['page'] )){
		      return json_encode(['errorcode'=>1001,'msg'=>'网络异常，请稍后再试！']);
		}
		$page = Helper::filtdata($_POST['page'],'INT');
		if(!$page){
			return json_encode ( ['errorcode' => '1002','list' => null,'msg'=>'网络异常，请稍后再试！']);
		}
		$pageSize = 10;//条数
		$start =  $pageSize * ($page-1);
		$end = $pageSize * $page;
		$user = yii::$app->session['user'];
		$integral_type = yii::$app->params['integral_type'];
		if($page==1){
			$end = $end+5;
			$integralarr = Integral::find()->where(['uid'=>$user->id])->offset($start)->limit($end)->asArray()->orderBy('createtime desc')->select('id,type,integral,uid,createtime')->all();  //查询15条已启用的商品
			yii::$app->session['integralarr'] = $integralarr;
			$integralarr && $integralarr = array_splice ( $integralarr, $start, $pageSize );
			$errorcode = ($integralarr!=null)?'0':'1003';	//为空则返回1003码
			return json_encode(['errorcode'=>$errorcode,'msg'=>$integralarr,'integral_type'=>$integral_type]);
		}else{
			$plist = yii::$app->session['integralarr'];
			$list = null;
			$actSessionlen = count($plist);		//当前缓存中数组的长度
			if($end>$actSessionlen){//总数若是大于缓存中的总数,则继续从数据表中读取数据
				$integralarr = Integral::find()->where(['uid'=>$user->id])->offset($start)->limit($end)->asArray()->orderBy('createtime desc')->select('id,type,integral,uid,createtime')->all();  //查询10条已启用的商品
				$plist =array_merge($plist,$integralarr);
				Yii::$app->session->set('integralarr',$plist);
			}
			if(!$plist){
				return json_encode(['errorcode'=>1003,'msg'=>'数据加载完成']);
			}
			$alist = array_splice ( $plist, $start, $pageSize);   //数据截取数据
			if($alist){
				foreach($alist as $k=>$v){
					$list[$k]['id'] = $v['id'];
					$list[$k]['type'] = 3;
					$list[$k]['integral'] = $v['integral'];
					$list[$k]['uid'] = $v['uid'];
					$list[$k]['createtime'] = $v['createtime'];
				}
			}	
			$errorcode = ($list!=null)?'0':'1003';	//为空则返回1003码
			return json_encode(['errorcode'=>$errorcode,'msg'=>$list,'integral_type'=>$integral_type]);
		}
	}
	
	
	/**
	 * 购买兑换
	 */
	public  function actionIntegralbug(){
		if (!Yii::$app->request->isAjax || !isset ( $_POST ['page'] )){
			return json_encode(['errorcode'=>1001,'msg'=>'网络异常，请稍后再试！']);
		}
		$page = Helper::filtdata($_POST['page'],'INT');
		if(!$page){
			return json_encode ( ['errorcode' => '1002','list' => null,'msg'=>'网络异常，请稍后再试！']);
		}
		$pageSize = 15;//条数
		$start =  $pageSize * ($page-1);
		$end = $pageSize * $page;
		$user = yii::$app->session['user'];
		$integral_type = yii::$app->params['integral_type'];
		if($page==1){
			$end = $end+5;
			$exchangearr = Exchange::find()->where(['uid'=>$user->id])->offset($start)->limit($end)->asArray()->orderBy('createtime desc')->select('id,,product_name,integral,createtime,getcode')->all();  //查询15条已启用的商品
			yii::$app->session['exchangearr'] = $exchangearr;
			$exchangearr && $exchangearr = array_splice ( $exchangearr, $start, $pageSize );
			$errorcode = ($exchangearr!=null)?'0':'1003';	//为空则返回1003码
			return json_encode(['errorcode'=>$errorcode,'msg'=>$exchangearr,'integral_type'=>$integral_type]);
		}else{
			$plist = yii::$app->session['exchangearr'];
			$list = null;
			$actSessionlen = count($plist);		//当前缓存中数组的长度
			if($end>$actSessionlen){//总数若是大于缓存中的总数,则继续从数据表中读取数据
				$exchangearr = Exchange::find()->where(['uid'=>$user->id])->offset($start)->limit($end)->asArray()->orderBy('createtime desc')->select('id,,product_name,integral,createtime,getcode')->all();  //查询10条已启用的商品
				$plist =array_merge($plist,$exchangearr);
				Yii::$app->session->set('exchangearr',$plist);
			}
			if(!$plist){
				return json_encode(['errorcode'=>1003,'msg'=>'数据加载完成']);
			}
			$alist = array_splice ( $plist, $start, $pageSize);   //数据截取数据
			if($alist){
				foreach($alist as $k=>$v){
					$list[$k]['product_name'] = $v['product_name'];
					$list[$k]['integral'] = $v['integral'];
					$list[$k]['getcode'] = $v['getcode'];
					$list[$k]['createtime'] = $v['createtime'];
				}
			}
			$errorcode = ($list!=null)?'0':'1003';	//为空则返回1003码
			return json_encode(['errorcode'=>$errorcode,'msg'=>$list,'integral_type'=>$integral_type]);
		}
	}
	
	
	/**
	 * 积分兑换
	 */
	public function actionExchange(){
		if (!Yii::$app->request->isAjax || !isset ( $_POST ['pid'] )){
			return json_encode(['errorcode'=>1001,'msg'=>'网络异常，请稍后再试！']);
		}
		$user = yii::$app->session['user'];
		$pid = Helper::filtdata($_POST['pid'],'INT'); //商品id
		if(!$pid){
			return json_encode(['errorcode'=>1002,'msg'=>'网络异常，请稍后再试！']);
		}
		$product = Product::find()->where(['id'=>$pid])->andWhere(['>','number',0])->one();//获取该商品
		if(!$product){//不存在
			return json_encode(['errorcode'=>1003,'msg'=>'抱歉，该商品库存不足']);
		}
	    $user = User::findOne(['id'=>$user->id]);   //查找该用户
    	if(!$user){
    		return json_encode(['errorcode'=>1001,'msg'=>'网络异常，稍后在试']);
    	}  
    	if($user->integral<$product->integral){   //积分不足时
    		return json_encode(['errorcode'=>1004,'msg'=>'积分不足']);
    	}
    	//检测是否有锁
    	$checkres = $this->checklock(1);
    	if(!$checkres){
    		return json_encode([
    				'errorcode'=>'1007',
    				'msg'=>'您好，当前兑换人数较多，请重新兑换',
    				]);
    	}
    	$lockarr = ['time'=>time(),'lock'=>1];
    	yii::$app->cache->set('lock',$lockarr); //加锁
    	$connection = Yii::$app->db->beginTransaction();//开启事务
    	$exchange = new Exchange();
    	$exchange->pid = $pid;
    	$exchange->product_name = $product->product_name;
    	$exchange->integral = $product->integral;
    	$exchange->uid = $user->id;
    	$exchange->createtime = time();
    	$exchange->getcode = chr(rand(65,90)).chr(rand(97,122)).uniqid();  //领取码
    	$user->integral = ($user->integral - $product->integral)>0?$user->integral - $product->integral:0;
    	$product->number = ($product->number-1>0)?$product->number-1:0;
    	if($exchange->save()&&$user->save()&& $product->save()){
    		yii::$app->session['user'] = $user;
    		$connection->commit();//事物提交
    		$lockarr = ['time'=>time(),'lock'=>0];
    		yii::$app->cache->set('lock',$lockarr); //释放锁
    		return json_encode(['errorcode'=>0,'msg'=>'兑换成功','getcode'=>$exchange->getcode]);
    	}else{
    		$connection->rollBack();//事物回滚
    		$lockarr = ['time'=>time(),'lock'=>0];
    		yii::$app->cache->set('lock',$lockarr); //释放锁
    		return json_encode(['errorcode'=>1005,'msg'=>'兑换失败，请重新再试']);
    	}
	}
	
	/**
	 * 检测锁是否解开
	 */
	private function checklock($num){
		if($num<4){//循环3次结束
			$lockarr = yii::$app->cache->get('lock');
			$time = isset($lockarr['time'])?time()-$lockarr['time'] : 100;
			if($time>30){//锁超过30秒  解锁
				$lockarr = ['time'=>time(),'lock'=>0];
				yii::$app->cache->set('lock',$lockarr); //释放锁
				return true;
			}
			if(isset($lockarr['lock']) && $lockarr['lock']==1){//已加锁
				sleep(3);
				$num += 1;
				$this->checklock($num);
			}else{
				return true;
			}
		}else{
			return false;
		}
	}
	
	
}