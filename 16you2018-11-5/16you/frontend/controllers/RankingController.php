<?php
namespace frontend\controllers;

use yii;
use yii\web\Controller;
use common\models\Order;
use common\models\Rule;
use common\models\User;
use common\models\Game;
use common\common\Helper;
use frontend\controllers\BaseController;
use common\redismodel\GameRedis;

/**
 * 游戏金榜
 * @author He
 */
class RankingController extends BaseController{
	
	/**
	 * 游戏金榜首页
	 */
	public function actionIndex(){
		$state = Helper::filtdata(yii::$app->request->get('state',1));
		$rule = yii::$app->cache->get('rule');//从缓存中读取游戏金榜规则
		if(!$rule){// state 1启用 0禁用 type 1游戏金榜 0积分规则
			$rule = Rule::find()->where(['state'=>1,'type'=>1])->orderBy('endtime asc')->asArray()->ALL();
			if(!$rule){//暂时没有数据
				return $this->render('rangingerror');
				exit;
			}
			yii::$app->cache->set('rule',$rule);
		}
		$count = count($rule);
		$endrule = $rule[$count-1];
		$data['rule'] = $count;//第几期
		if($count>1 && !$state){//上期起始时间
			$endrule = $rule[$count-2];
			$data['rule'] = $count-1;//第几期
		}
		$data['kr'] = $endrule['content'];//查出本期的活动详情
		$now = $endrule['starttime'];
		$last = $endrule['endtime'];
		$where = "go.createtime between {$now} and {$last}";
		$order = (new \yii\db\Query())
				->select('username,sum(go.price) as sum_price,head_url')
				->from('g_user AS gu')
				->leftJoin('g_order AS go','gu.id = go.uid')
				->groupBy('gu.id')
				->where($where)
				->andWhere(['go.state'=>2])
				->orderBy('sum_price desc')
				->limit(10)
				->all();
		$res7 = $res3 = array();
		$data['orderby'] = '';
		$user = yii::$app->session['user'];
		if($order){
			foreach ($order as $key => $value) {
				if($value['username']==$user->username){
					$data['orderby'] = $key+1;
				}
			}
			$res3 = array_slice($order,0,3); 
			$res7 = array_slice($order,3,7);
		}
		$sprice = Order::find()->where("createtime between {$now} and {$last}")->andWhere(['uid'=>$user->id,'state'=>2])->select('sum(price) as s_price')->asArray()->one();
		if($sprice){
			$data['sprice'] = (int)($sprice['s_price']);
		}else{
			$data['sprice'] = 0;
		}
		if(yii::$app->session['playgame']){ 
			$playgame = array_slice(yii::$app->session['playgame'],0,3);
		}else{
			$this->newplay(); //获取最近在玩游戏
			$playgame = array_slice(yii::$app->session['playgame'],0,3);
		}
		$data['now'] = date('m/d',$now);
		$data['last'] = date('m/d',$last);
		$data['integral'] = yii::$app->params['integral'];
		$data['state'] = $state?0:1;
		yii::$app->session['typemenu'] = 2;
		return $this->render('index',[
			'res3'=>$res3,
			'res7'=>$res7,
			'user'=>$user,
			'playname'=>$playgame,  
			'data'=>$data, 
		]);
	}

	//往期排名
	public function actionToorderby(){
		$rule = yii::$app->cache->get('rule');//从缓存中读取游戏金榜规则
		if(!$rule){
			$rule = Rule::find()->where(['state'=>1,'type'=>1])->orderBy('endtime asc')->asArray()->ALL();
			yii::$app->cache->set('rule',$rule);
		}
		if(!$rule){
			return $this->redirect('/index/index.html');
		}
		$starttime = $rule['0']['starttime'];
		$endtime = end($rule)['endtime'];
		$uid = yii::$app->session['user']->id;
		$order =Order::find()->where("createtime between {$starttime} and {$endtime}")->andWhere(['uid'=>$uid])->asArray()->all();
		$data = array();
		if($order){
			$inte = 0;
			foreach ($rule as $k=>$v){
			 	foreach ($order as $ko => $vo){
		 			if($vo['createtime'] >= $v['starttime'] && $vo['createtime'] <= $v['endtime']){
			 			$inte  = $inte+$vo['price'];
			 		}
			 	}
			 	$data[$k]['period'] = $k+1;//第几期
			 	$data[$k]['inte'] = (int)($inte);//增长
			 	$data[$k]['starttime'] = date('Y年m月d日',$v['starttime']);
			 	$data[$k]['endtime'] = date('Y年m月d日',$v['endtime']);
			}
			krsort($data);
		}
		return $this->renderPartial('orderby',[
				'data'=>$data,
		]);
	}
	
	/**
	 * 最近在玩
	 */
	private function newplay(){
		$user = yii::$app->session['user'];
		$openid = $user->openid;
		//获取最近玩的游戏
		$userarr = User::find()->where(['openid'=>$openid])->asArray()->select('gid')->one();
		$temparr = array();
		if($userarr){
			$gid_arr = ($userarr['gid'])?json_decode($userarr['gid'],true):array();
			if(!empty($gid_arr)){
				arsort($gid_arr);//以降序排序
				$gidarr = array_keys($gid_arr);
				//获取已启用的最近在玩游戏
			//获取已启用的最近在玩游戏
				$playgame = GameRedis::find()->where(['id'=>$gidarr,'state'=>1])->limit(50)->asArray()->all();
				if(!$playgame){
					$playgame = Game::find()->where(['id'=>$gidarr,'state'=>1])->limit(50)->asArray()->select('id,name,descript,label,head_img,game_url')->all();
				}
				if($playgame){
					foreach ($gidarr as $g){//获取对应游戏内容
						foreach ($playgame as $k=>$play){
							if($g==$play['id']){
								$play['playtime'] = $gid_arr[$play['id']];
								$temparr[] = $play;
							}
						}
					}
				}
			}
		}
		yii::$app->session['playgame'] = $temparr;
	}
}