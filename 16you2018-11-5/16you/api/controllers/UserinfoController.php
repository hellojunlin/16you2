<?php
namespace api\controllers;

use yii;
use yii\web\Controller;
use common\common\Helper;
use common\common\Getuserinfoutil;
use common\models\User;
use common\models\Configuration;
use common\pay\Wxnotify_pub;
use common\common\Wxinutil;

/**
 * @author junlin
 */

class UserinfoController extends Controller
{	
	public function actionGetuserinfo(){
			$resarr = array();
			if(!isset($_POST['partnerid'])){ //厂商编号
				$resarr['code'] = "1001";
				$resarr['message'] = "partnerid required";
				echo json_encode($resarr);exit;
			}
			
			if(!isset($_POST['access_token'])){ //access_token
				$resarr['code'] = "1001";
				$resarr['message'] = "access_token required";
				echo json_encode($resarr);exit;
			}
			
			if(!isset($_POST['sign'])){  //签名
				$resarr['code'] = "1001";
				$resarr['message'] = "sign required";
				echo json_encode($resarr);exit;
			}
			$partnerid = Helper::filtdata($_POST['partnerid']);
			$access_token = Helper::filtdata($_POST['access_token']);
			$sign = Helper::filtdata($_POST['sign']);
			if(!$partnerid || !$access_token || !$sign){
				$resarr['code'] = "1001";
				$resarr['message'] = "parameter error";
				echo json_encode($resarr);exit;
			}
			$uwhere = $this->getuserid($access_token);
			if($uwhere['access_token']=='test2018116you666'){//游客信息
				$resarr['userid'] =$uwhere['userid'];
				$resarr['nickname'] = '游客';
				$resarr['avatar'] = 'http://wx.qlogo.cn/mmopen/07ug0oheAoqYtiaiafr05cNmdyJAqibPFicbo6e1HMGp4aIPmBghZg6eyho61pWsZQuG4zDym0NqBB85hdXQeIdu2xOENDB8gzRP/0';
				$resarr['sex'] = '0';
				$resarr['province'] = '广东';
				$resarr['city'] = '广州';
				$resarr['code'] = 0;
				$resarr['message'] = 'ok';
				echo json_encode($resarr);exit;
			}else{
				$notify = new Wxnotify_pub();
				$darr = ['partnerid'=>$partnerid,'access_token'=>$access_token,'sign'=>$sign];
				$configuration = Configuration::findOne(['partnerid'=>$partnerid]);
				if(!$configuration){
					
					$resarr['code'] = "1001"; 
					$resarr['message'] = "partnerid error";
					echo json_encode($resarr);exit;
				}
				$darr['key'] = $configuration['key'];
				$notify->data = $darr;
				//验证签名，并回应
				if($notify->checkSign2()==TRUE){
						$user = User::findOne(['id'=>$uwhere['userid'],'access_token'=>$uwhere['access_token']]);
						$resarr = array();
						if($user){//用户存在
							$resarr['userid'] = $user->id;
							$resarr['nickname'] = $user->username;
							$resarr['avatar'] = $user->head_url;
							$resarr['sex'] = $user->sex;
							$resarr['province'] = $user->province;
							$resarr['city'] = $user->city;
							$resarr['code'] = 0;
							$resarr['message'] = 'ok';
							//2.将成功结果发送给游戏方，处理过后，则直接返回true给微信
						    echo json_encode($resarr);exit; 
						}else{
							$resarr['code'] = "1001";
							$resarr['message'] = "user not exist";
							echo json_encode($resarr);exit;
						}
				}else{
					$resarr['code'] = "1001";
					$resarr['message'] = "sign error";
					echo json_encode($resarr);exit;
				}
			}
		
		}
		
		/**
		 * 检测用户是否关注
		 */
		public function actionChecksubscribe(){
			$resarr = array();
			if(!isset($_GET['partnerid'])){ //厂商编号
				$resarr['code'] = "1001";
				$resarr['message'] = "partnerid required";
				return $_GET['jsoncallback'] . "(".json_encode($resarr).")";
			}
				
			if(!isset($_GET['access_token'])){ //厂商编号
				$resarr['code'] = "1001";
				$resarr['message'] = "access_token required";
				return $_GET['jsoncallback'] . "(".json_encode($resarr).")";
			}
				
			if(!isset($_GET['sign'])){  //签名
				$resarr['code'] = "1001";
				$resarr['message'] = "sign required";
				return $_GET['jsoncallback'] . "(".json_encode($resarr).")";
			}
				
			$partnerid = Helper::filtdata($_GET['partnerid']);
			$access_token = Helper::filtdata($_GET['access_token']);
			$sign = Helper::filtdata($_GET['sign']);
			if(!$partnerid || !$access_token || !$sign){
				$resarr['code'] = "1001";
				$resarr['message'] = "parameter error";
				return $_GET['jsoncallback'] . "(".json_encode($resarr).")";
			}
			$configuration = Configuration::findOne(['partnerid'=>$partnerid]);
			if(!$configuration){
				$resarr['code'] = "1001";
				$resarr['message'] = "partnerid not exist";
				return $_GET['jsoncallback'] . "(".json_encode($resarr).")";
			}
			$notify = new Wxnotify_pub();
			$darr = ['partnerid'=>$partnerid,'access_token'=>$access_token,'sign'=>$sign];
			$darr['key'] = $configuration['key'];
			$notify->data = $darr;
			$uwhere = $this->getuserid($access_token);
			//验证签名，并回应
			if($notify->checkSign2()==TRUE){
				$user = User::findOne(['id'=>$uwhere['userid'],'access_token'=>$uwhere['access_token']]);
				if($user){
					//验证签名，并回应
					if($user->is_subecribe==1){//已关注
						$resarr['code'] = "1";
						$resarr['message'] = 'user subscribe';
					}else{//未关注
						$resarr['code'] = "0";
						$resarr['message'] = 'user not subscribe';
					}
					//2.将成功结果发送给游戏方，处理过后，则直接返回true给微信
					return $_GET['jsoncallback'] . "(".json_encode($resarr).")";
				}else{
					$resarr['code'] = "1001";
					$resarr['message'] = "user not exist";
					return $_GET['jsoncallback'] . "(".json_encode($resarr).")";
				}
			}else{
				$resarr['code'] = "1001";
				$resarr['message'] = 'sign error';
				return $_GET['jsoncallback'] . "(".json_encode($resarr).")";
			}
		}
		
		
		/**
		 * 检测用户是否关注
		 */
		public function actionSubscribe(){
			$resarr = array();
			if(!isset($_GET['partnerid'])){ //厂商编号
				$resarr['code'] = "1001";
				$resarr['message'] = "partnerid required";
				echo json_encode($resarr);exit;
			}
		
			if(!isset($_GET['access_token'])){ //厂商编号
				$resarr['code'] = "1001";
				$resarr['message'] = "access_token required";
				echo json_encode($resarr);exit;
			}
		
			if(!isset($_GET['sign'])){  //签名
				$resarr['code'] = "1001";
				$resarr['message'] = "sign required";
				echo json_encode($resarr);exit;
			}
		
			$partnerid = Helper::filtdata($_GET['partnerid']);
			$access_token = Helper::filtdata($_GET['access_token']);
			$sign = Helper::filtdata($_GET['sign']);
			if(!$partnerid || !$access_token || !$sign){
				$resarr['code'] = "1001";
				$resarr['message'] = "parameter error";
				echo json_encode($resarr);exit;
			}
			$configuration = Configuration::findOne(['partnerid'=>$partnerid]);
			if(!$configuration){
				$resarr['code'] = "1001";
				$resarr['message'] = "partnerid not exist";
				echo json_encode($resarr);exit;
			}
			$notify = new Wxnotify_pub();
			$darr = ['partnerid'=>$partnerid,'access_token'=>$access_token,'sign'=>$sign];
			$darr['key'] = $configuration['key'];
			$notify->data = $darr;
			$uwhere = $this->getuserid($access_token);
			//验证签名，并回应
			if($notify->checkSign2()==TRUE){
				$user = User::findOne(['id'=>$uwhere['userid'],'access_token'=>$uwhere['access_token']]);
				if($user){
					//验证签名，并回应
					if($user->is_subecribe==1){//已关注
						$resarr['code'] = "1";
						$resarr['message'] = 'user subscribe';
					}else{//未关注
						$resarr['code'] = "0";
						$resarr['message'] = 'user not subscribe';
					}
					//2.将成功结果发送给游戏方，处理过后，则直接返回true给微信
					echo json_encode($resarr);exit;
				}else{
					$resarr['code'] = "1001";
					$resarr['message'] = "user not exist";
					echo json_encode($resarr);exit;
				}
			}else{
				$resarr['code'] = "1001";
				$resarr['message'] = 'sign error';
				echo json_encode($resarr);exit;
			}
		}
		
		  public function getuserid($string){
			$strindex = stripos($string,"|");
			$arr['userid'] = -1;
			$arr['access_token'] = -1;
			if($strindex){
				$strlen =  strlen ( $string );
				$index =$strindex - $strlen+1;
				$arr['userid'] = substr($string, $index, -4);
				$arr['access_token'] = substr($string,0, $index-1);
			}
			return $arr;
		}  
		
}