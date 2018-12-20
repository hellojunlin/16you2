<?php
namespace frontend\controllers;

use yii;
use yii\web\Controller;
use common\common\Wxinutil;
use common\models\User;
use common\models\Plateform;
use common\common\Getuserinfoutil;
use common\common\Helper;
use common\redismodel\UserRedis;
use yii\base\Object;
class WxauthController extends Controller{
	
	public function actionAuth(){
		$appid = yii::$app->params['wxinfo']['appid'];
		$state = yii::$app->params['state'];
		$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';  //判断访问类型是http还是https
		if(isset($_GET['scope'])){
			$scope = 'snsapi_userinfo';
			$redirect_uri=urlencode($http_type.$_SERVER['HTTP_HOST'].'/wxauth/userinfo.html');
		}else{
			$scope = 'snsapi_base';
			$redirect_uri=urlencode($http_type.$_SERVER['HTTP_HOST'].'/wxauth/getinfo.html');
		}
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=$scope&state=$state#wechat_redirect";
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
		$appid = yii::$app->params['wxinfo']['appid'];
		$secret =yii::$app->params['wxinfo']['secret'];
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
		$output = Wxinutil::http_get($url);
		$data = json_decode($output);
		if(!isset($data->access_token)){
			return $this->redirect('/wxauth/auth.html');//获取不到权限，非法访问
		}
		$openid = $data->openid;
		$userinfo = User::findOne(['openid'=>$openid]);
		// if(!$userinfo){//redis不存在时往数据库查询
		// 	$userinfo = User::findOne(['openid'=>$openid]);
		// 	$userinfo && $this->saveuserredis($userinfo);
		// }else{
		// 	if($userinfo->vip==null){
		// 		$userinfo->vip = 0;
		// 		$userinfo->consult_id = '';
		// 		$userinfo->save();
		// 	}
		// }
		if($userinfo){
			\Yii::$app->session->set('user',$userinfo);
			$headurl = (yii::$app->session['server'])?yii::$app->session['server']:yii::$app->params['frontend'].'/index/index!16you.html';//原来的地址
			/* if($userinfo->Unique_ID=='10494918'){
				$headurl = 'https://wx.16you.com/index/index!16you.html';
			} */
			$this->redirect($headurl);
		}else{
			return $this->redirect('/wxauth/auth.html?scope=userinfo');
		}
	}

	//获取用户信息
	public function actionUserinfo(){
		$state = yii::$app->params['state'];
		if(!isset($_GET['code'])||!isset($_GET['state'])||($_GET['state']!=yii::$app->params['state'])){	//用户禁止授权，则重定向后不会带上code参数。
			echo '非法访问';
			exit();
		}
		$code = $_GET['code'];
		$appid = yii::$app->params['wxinfo']['appid'];
		$secret =yii::$app->params['wxinfo']['secret'];
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
		$output = Wxinutil::http_get($url);
		$data = json_decode($output);
		if(!isset($data->access_token)){
			return $this->redirect('/wxauth/auth.html');//获取不到权限，非法访问
		}
		$access_token = $data->access_token;    //网页授权接口调用凭证
		$openid = $data->openid;
		// $userinfo = UserRedis::find()->where(['openid'=>$openid])->one();//再次判断该用户是否存在
		// $etime=microtime(true);
  //       $total=$etime-$stime1; //计算差值
  //       yii::trace("--userres---------------$total------------------");
		// if(!$userinfo){//redis不存在时往数据库查询
			$userinfo = User::findOne(['openid'=>$openid]);
		// 	$userinfo && $this->saveuserredis($userinfo);
		// }else{
		// 	if($userinfo->vip==null){
		// 		$userinfo->vip = 0;
		// 		$userinfo->consult_id = '';
		// 		$userinfo->save();
		// 	}
		// }
		if($userinfo){
			\Yii::$app->session->set('user',$userinfo);
			$headurl = (yii::$app->session['server'])?yii::$app->session['server']:'/index/index!16you.html';//原来的地址
			$this->redirect($headurl);
		}else{
			$userinfourl = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
			$output1 = Wxinutil::http_get($userinfourl);
			$userdata = json_decode($output1);
			if(!isset($userdata->openid)){
				return $this->redirect('/wxauth/auth.html');//获取不到权限，非法访问
			}else{
				$id = yii::$app->session['puid'];
				$plate = Plateform::find()->where(['punid'=>$id,'state'=>1])->one();
				if(!$plate){
					$plate = Plateform::find()->where(['punid'=>'16you','state'=>1])->one();
					if(!$plate){
						echo '该平台没有权限';
						exit();
					}
				}
				$userinfo = User::findOne(['unionid'=>$userdata->unionid]);
				if($userinfo){//存在即在app登录过，或则是老用户
					if(!$userinfo->openid){  //openid 为空时
						$userinfo->openid = $openid;
						$sres = $userinfo->save();
						if(!$sres){
							return $this->redirect('/common/error.html');
						}
					}
				}else{
					$userinfo = new Getuserinfoutil();
					$access_token = $userinfo->getaccess_token();
					$userinfo = new User();
					$maxid = $userinfo->find()->select('max(Unique_ID) AS MAXID')->asArray()->one();
					$userinfo->Unique_ID = $maxid?($maxid['MAXID']+1):'10000000';
					$userinfo->openid = $openid;
					$userinfo->pid = $plate->id;
					$userinfo->username = isset($userdata->nickname)?Helper::filterEmoji($userdata->nickname):"";
					$userinfo->unionid = isset($userdata->unionid)?$userdata->unionid:'';
					$userinfo->sex = isset($userdata->sex)?$userdata->sex:0;
					$userinfo->head_url = isset($userdata->headimgurl)?$userdata->headimgurl:'';
					$userinfo->province = isset($userdata->province)?$userdata->province:'';
					$userinfo->city = isset($userdata->city)?$userdata->city:'';
					$userinfo->integral = 0;
					$userinfo->gid = '';
					$userinfo->phone = '';
					$userinfo->access_token = $access_token;
					$userinfo->createtime = time();
					$userinfo->password = rand(100,999).rand(100,999);
					$userinfo->vip = 0;
					$userinfo->consult_id = '';
					$userinfo->is_subecribe = 0;
					$userinfo->logintype = 1;
					$sres = $userinfo->save();
					if(!$sres){
						return $this->redirect('/common/error.html');
					}
				}
				\Yii::$app->session->set('user',$userinfo);
				$headurl = (yii::$app->session['server'])?yii::$app->session['server']:'/index/index!16you.html';//原来的地址
				$this->redirect($headurl);
				
			}	
		}
	}
	
	/**
	 * 保存用户redis
	 */
	private function saveuserredis($userinfo){
		$redis = NEW UserRedis();
		$redis->id = $userinfo->id;
		$redis->openid = $userinfo->openid;
		$redis->pid = $userinfo->pid;
		$redis->username = $userinfo->username;
		$redis->sex = $userinfo->sex;
		$redis->head_url = $userinfo->head_url;
		$redis->province = $userinfo->province;
		$redis->city = $userinfo->city;
		$redis->integral = $userinfo->integral;
		$redis->gid = $userinfo->gid;
		$redis->phone = $userinfo->phone;
		$redis->access_token = $userinfo->access_token;
		$redis->createtime = $userinfo->createtime;
		$redis->Unique_ID = $userinfo->Unique_ID;
		$redis->password = $userinfo->password;
		$redis->unionid = isset($userinfo->unionid)?$userdata->unionid:'';
		$redis->vip = 0;
		$redis->consult_id = '';
		$redis->is_subecribe = 0;
		$redis->save();
	}
}