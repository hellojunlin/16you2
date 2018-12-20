<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\common\Wxinutil;
use common\models\Autoreply;
use common\models\Wxkeyword;
use common\models\User;
/**
 * 
 * @author He
 *
 */

class ResponseController extends Controller{
	/**
	 * descript:微信入口文件
	 */
	public function actionIndex(){
		if(isset($_GET['appid'])){
			$appid = htmlspecialchars(trim($_GET['appid']));
			$model = '';
			if(!isset($_SESSION['rev'])){
	            yii::$app->session['rev'] = yii::$app->params['replyVideo'];
	        }
			$res = yii::$app->session['rev'];
	        foreach($res as $v){
	        	if($v['appid']==$appid){
	        		$model = $v;
	        	}
	        }
			if($model){
				if(isset($_GET['echostr'])){
					$echostr = $_GET['echostr'];
					if($this->checkSignature($model['token'])){//校验第一次
						echo $echostr;
					}
				}else{ //开始接收消息
					 yii::$app->session->set('wxinfo',$model);
	                 $this->responseMsg($model['token'],$appid);	
				}
			}else{
				echo '';
			}
		}
	}
	
	/**
	 * 微信校验
	 * @param unknown $token
	 * @return boolean
	 */
	private function checkSignature($token)
	{
		$signature = isset($_GET["signature"])?$_GET["signature"]:null;
		$timestamp = isset($_GET["timestamp"])?$_GET["timestamp"]:null;
		$nonce = isset($_GET["nonce"])?$_GET["nonce"]:null;
		$tmpArr = array($token, $timestamp, $nonce);
		// use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 消息处理
	 */
	public function responseMsg($token,$appid)
	{
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		if ($postStr){
			libxml_disable_entity_loader(true);
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$data=array();
			$data['FromUserName'] = $postObj->FromUserName;
			$data['ToUserName'] = $postObj->ToUserName;
			$data['MsgType'] = $postObj->MsgType;
			$data['Time'] = time()+(3600*8);
			$data['EventKey'] = $postObj->EventKey;
			$model = yii::$app->session->get('wxinfo');
			if($data['MsgType']=='event'){//事件类型
				$data['Event'] = $postObj->Event;
				$eventkey = substr($data['EventKey'],0,4);
				if($eventkey =='2017' || $eventkey == 'qrsc'){
					$wxinutil = new Wxinutil();
					$aa = isset($postObj->Ticket)?md5($postObj->Ticket):'';
					$bb = $data['FromUserName'];
					yii::$app->cache->set("$aa","$bb");
					$value = yii::$app->cache->get("$aa");
					$data['content'] = '您已在电脑登录成功';
					$resultStr = $wxinutil->respText($data);
					echo $resultStr;
				}
				if($data['Event']=='subscribe'){
					$this->toAutoreply($data,$appid);
					$user = User::findOne(['openid'=>$postObj->FromUserName]);
					if($user){
						$user->is_subecribe = 1;
						$user->save();
					} 
					/* if(substr($data['EventKey'],8,4)=='2017'){
						$str = substr($data['EventKey'],8,4);
						yii::trace('-------------------------------'.$str);
						$wxinutil = new Wxinutil();
						$aa = substr($data['EventKey'],8);
						yii::trace('---------------aaaaa----------------'.$aa);
						$bb = $data['FromUserName'];
						yii::$app->cache->set("$aa","$bb");
						$data['content'] = '您已在电脑登录成功';
						$resultStr = $wxinutil->respText($data);
						echo $resultStr;
					} */
				}elseif($data['Event']=='unsubscribe'){
					$user = User::findOne(['openid'=>$postObj->FromUserName]);
					if($user){
						$user->is_subecribe = 0;
						$user->save();
					}
				}elseif($data['Event']=='CLICK'){
					$this->toKeyword($data,$appid);
				}
				
			}elseif($data['MsgType']=='text'){//文本类型
				$data['EventKey'] = trim($postObj->Content);
				$this->toKeyword($data,$appid);
			}
		}else {
			echo "";
			exit;
		}
	}

		/**
	 * 关键字判断
	 * @return [type] [description]
	 */
	public function toKeyword($data,$appid){
		$keyword = Wxkeyword::find()->where(['wxappid'=>$appid])->andWhere(['like','keyword',$data['EventKey']])->orderBy('sort')->asArray()->all();
		if($keyword){
			$this->toMsglaunch($data,$keyword);
		}
	}


	/**
	 * 自动回复判断
	 * @return [type] [description]
	 */
	public function toAutoreply($data,$appid){
		$autoreply = Autoreply::find()->where(['wxappid'=>$appid,'state'=>1])->orderBy('sort')->asArray()->all();
		if($autoreply){
			$this->toMsglaunch($data,$autoreply);//查询自动回复内容
		}
	}

	public function toMsglaunch($data,$autoreply){
		$wxinutil = new Wxinutil();
		$count = count($autoreply);
		$count_1 = $count-1;
		if($count>1){//使用客服消息
			$res = array_slice($autoreply,0,$count_1);
			$wxinfo = yii::$app->session['wxinfo'];
			foreach ($res as $v) {
				switch ($v['type']) {
					case '1':		//文本
						$data['content'] = json_decode($v['content']);
						$wxinutil->serviceMsgtext($data,$wxinfo);
						break;
					case '2':		//图文
						$content = json_decode($v['content']);
						$wxinutil->serviceMsgnews($data,$wxinfo,$content);
						break;
					case '3':		//图片
						$data['content'] = $v['content'];
						$wxinutil->serviceMsgimages($data,$wxinfo);
						break;
					case '4':		//视频
						$content = json_decode($v['content']);
						$wxinutil->serviceMsgvideo($data,$wxinfo,$content);
						break;
				}
			}
		}
		$auto_1 = $autoreply["$count_1"];
		switch ($auto_1['type']) {
			case '1':		//文本
				$data['content'] = json_decode($auto_1['content']);
				$resultStr = $wxinutil->respText($data);
				echo $resultStr;
				break;
			case '2':		//图文
				$content = json_decode($auto_1['content']);
				$resultStr = $wxinutil->respNews($data,$content);
				echo $resultStr;
				break;
			case '3':		//图片
				$resultStr = $wxinutil->respImages($data,$auto_1['content']);
				echo $resultStr;
				break;
			case '4':		//视频
				$content = json_decode($auto_1['content']);
				$resultStr = $wxinutil->respVideo($data,$content);
				echo $resultStr;
				break;
		}
	}
}