<?php
namespace pc\controllers;

use yii;
use common\common\Helper;
use common\common\Wxinutil;
use common\models\User;
use common\models\Game;
use common\models\Plateform;
use common\common\Getuserinfoutil;
use pc\controllers\BaseController;

/** 
 * @author He
 */

class IndexController extends BaseController
{	
	public function actionTest(){
		$key = yii::$app->session['verifykey'];
		$val = yii::$app->cache->get($key);
		echo $val;
	}
	
	public function actionIndex(){
		if(Helper::isMobile()){
			return $this->redirect("/game/list.html");
			exit;
		}
		if(isset(yii::$app->session['user'])&&!empty(yii::$app->session['user'])){
			$user = yii::$app->session['user'];
			if(yii::$app->session['playgame']){ 
				$playgame = array_slice(yii::$app->session['playgame'],0,3);
			}else{
				$this->newplay(); //获取最近在玩游戏
				$playgame = array_slice(yii::$app->session['playgame'],0,3);
			}
			$game = Game::find()->where(['type'=>1])->orderBy('sort desc')->limit(4)->all();
			return $this->renderPartial('index',[
				'user'=>$user,
				'game'=>$game,
				'playgame'=>$playgame,
			]);
		}
		//获取微信二维码
		$wxutil = new Wxinutil();
		$appid = yii::$app->params['wxinfo']['appid'];
		$verify = '2017'.rand(100,999).rand(100,999);
		$filearr = $wxutil->gettempcode($appid,$verify,dirname(dirname(__FILE__)).'/web/media/images/code/');
		$ticket = '';
		$filename = '';
		if($filearr){ 
			$filename = isset($filearr['filename'])?$filearr['filename']:'';
			$verify = isset($filearr['ticket'])?md5($filearr['ticket']):'' ;
			yii::$app->session['verifykey'] = $verify;
		} 
		return $this->renderPartial('index',[
			'verify'=>$verify,
			'filename'=>$filename, 
			//'ticket'=>$ticket,
		]);
	}
	

	//验证用户
	public function actionVerifyuser(){
		if(!yii::$app->request->isAjax||!isset($_POST['verify'])){
			return json_encode([
				'info'=>'数据错误',
				'errorcode'=>0,
			]);
		}
		$verify = Helper::filtdata($_POST['verify']);
		$res = yii::$app->cache->get($verify);
		if(yii::$app->cache->get($verify)){
			$openid = yii::$app->cache->get($verify);
			$userinfo = User::findOne(['openid'=>$openid]);
			$sres = true;
			if(!$userinfo){
				$appid = yii::$app->params['wxinfo']['appid'];
				$userdata = Wxinutil::getUserinfo($openid,$appid);
				if($userdata){
					$puid = yii::$app->session['puid'];
					$plate = Plateform::find()->where(['punid'=>$puid,'state'=>1])->one();
					if(!$plate){
						return json_encode([
							'info'=>'该平台未授权使用',
							'errorcode'=>5678,
						]);
					} 
					
					$userinfo = new Getuserinfoutil();
					$access_token = $userinfo->getaccess_token();
					$userinfo = new User();
					$maxid = $userinfo->find()->select('max(Unique_ID) AS MAXID')->asArray()->one();
					$userinfo->Unique_ID = $maxid?($maxid['MAXID']+1):'10000000';
					$userinfo->openid = $openid;
					$userinfo->pid = isset($plate->id)?$plate->id:14;//14;//16游平台id
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
				}
			}
			if($sres){
				$helper = new Helper();
				@$helper->deletedir(dirname(dirname(__FILE__)).'/web/media/images/code');
				yii::$app->cache->delete($verify);
				\Yii::$app->session->set('user',$userinfo);
				$server = yii::$app->session['server'];
				return json_encode([
					'info'=>'成功',
					'errorcode'=>0,
					'server'=>$server
				]);
			}else{
				return json_encode([
					'info'=>'网络异常，稍后在试',
					'errorcode'=>5678,
				]);
			}
		}else{
			return json_encode([
				'info'=>'没有扫码',
				'errorcode'=>-1,
			]);
		}
	}

	 /**
     * 最近在玩
     */
    private function newplay(){
    	$user =  yii::$app->session['user'];
    	$openid = $user->openid;
    	//获取最近玩的游戏
    	$temparr = array();
    	if($user){
    		$gid_arr = ( $user->gid)?json_decode($user->gid,true):array();
    		if(!empty($gid_arr)){
    			arsort($gid_arr);//以降序排序
    			$gidarr = array_keys($gid_arr);
                //获取已启用的最近在玩游戏    	
    			$playgame = Game::find()->where(['id'=>$gidarr,'state'=>1])->limit(10)->asArray()->limit(200)->all();
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
    
    //退出登录
    public function actionLogout(){
    	unset(yii::$app->session['user']);
    	unset(yii::$app->session['playgame']);
    	return $this->redirect("index.html");
    }
}