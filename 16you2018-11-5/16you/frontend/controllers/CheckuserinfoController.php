<?php
namespace frontend\controllers;

use yii;
use yii\web\Controller;
use common\common\Helper;
use common\common\Getuserinfoutil;
use common\models\User;
use common\models\Configuration;
use common\pay\Wxnotify_pub;
use common\common\Wxinutil;
use common\redismodel\UserRedis;

/**
 * @author junlin
 */

class CheckuserinfoController extends Controller
{	
		/**
		 * 检测用户是否关注
		 */
		public function actionChecksubscribe(){
			$resarr = array();
			if(!isset($_POST['partnerid'])){ //厂商编号
				$resarr['code'] = "1001";
				$resarr['message'] = "partnerid required";
				echo json_encode($resarr);exit;
			}
			
			if(!isset($_POST['access_token '])){ //厂商编号
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
						if($userobject->is_subecribe==1){//已关注
							$resarr['code'] = 1;
							$resarr['message'] = 'user subscribe';
						}else{//未关注
							$resarr['code'] = 0;
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
				$resarr['code'] = 0;
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