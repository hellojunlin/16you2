<?php
namespace api\controllers;

use yii;
use yii\web\Controller;
use common\common\Shareutil;
use common\common\Helper;

/**
 * @author He
 */

class IndexController extends Controller
{	
	public function actionIndex(){
		if(isset($_GET['locaturl'])){
			$url = Helper::filtdata($_GET['locaturl']);
			//截取分享后的参数 ?from=groupmessage&isappinstalled=0
			/* if(strstr($url,'?from=', TRUE)){
			 $url = strstr($url, '?from=', TRUE);
			}  */
			$share = new Shareutil();//微信分享
			$appid = yii::$app->params['wxinfo']['appid'];
			$secret = yii::$app->params['wxinfo']['secret'];
			$signPackage = $share->getSignPackage($appid,$secret,$url);
			return $_GET['jsoncallback'] . "(".json_encode($signPackage).")";
		}
	}
}