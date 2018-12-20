<?php
namespace app\controllers;

use yii;
use yii\web\Controller;
use common\models\User;
use common\common\Helper;
use common\common\Getuserinfoutil;


/**
 * @author He
 */
class ApploginController extends Controller{
	public function actionIndex() {
		if(isset($_POST['userinfo'])){
			$userinfo = $_POST['userinfo'];
			if(!$userinfo){
				return false;
			}
			$unionid = $userinfo['unionid'];
			$user = User::findOne(['unionid'=>$unionid]);
			$isboolean = true;
			if(!$user){//不存在
				$getuser = new Getuserinfoutil();
				$access_token = $getuser->getaccess_token();
				$user = new User();
				$maxid = $user->find()->select('max(Unique_ID) AS MAXID')->asArray()->one();
				$user->Unique_ID = $maxid?($maxid['MAXID']+1):'10000000';
				$user->pid = 14;
				$user->username = isset($userinfo['nickname'])?Helper::filterEmoji($userinfo['nickname']):"";
				$user->unionid = isset($userinfo['unionid'])?$userinfo['unionid']:'';
				$user->sex = isset($userinfo['sex'])?$userinfo['sex']:0;
				$user->head_url = isset($userinfo['headimgurl'])?$userinfo['headimgurl']:'';
				$user->province = isset($userinfo['province'])?$userinfo['province']:'';
				$user->city = isset($userinfo['city'])?$userinfo['city']:'';
				$user->integral = 0;
				$user->gid = '';
				$user->phone = '';
				$user->access_token = $access_token;
				$user->createtime = time();
				$user->password = rand(100,999).rand(100,999);
				$user->vip = 0;
				$user->consult_id = '';
				$user->is_subecribe = 0;
				$user->appopenid = $userinfo['openid'];
				$user->logintype = 2;  //app登录
				$sres = $user->save();
				if(!$sres){
					$isboolean = false;
				}
			}
			yii::$app->session->set('user',$user);
			return $isboolean;
		}else{
			return false;
		}
	}
	
	/**
	 * app下载
	 */
	public function actionDownload(){
		$filePath = "media/app/";//此处给出你下载的文件在服务器的什么地方
		$fileName = "16you.apk";
		//此处给出你下载的文件名
		$file = fopen($filePath . $fileName, "r"); //   打开文件
		//输入文件标签
		Header("Content-type:application/octet-stream ");
		Header("Accept-Ranges:bytes ");
		Header("Accept-Length:   " . filesize($filePath . $fileName));
		Header("Content-Disposition:   attachment;   filename= " . $fileName);
		//   输出文件内容
		echo fread($file, filesize($filePath . $fileName));
		fclose($file);
		exit();
	}
}