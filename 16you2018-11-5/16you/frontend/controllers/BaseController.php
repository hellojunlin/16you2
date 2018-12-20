<?php 
namespace frontend\controllers;

use yii;
use yii\web\Controller;
use common\models\Game;
use common\common\Shareutil;
use common\models\Plateform;

/**
 *基类
 * @author he
 */
class BaseController extends Controller{
	public function init() { 
		$puid = '16you';
		if(isset($_GET['puid'])){//判断平台id是否正常传输
			$puid = htmlspecialchars(trim($_GET['puid']));
		}
		if(!yii::$app->session['plateform']||yii::$app->session['plateform']==null||$puid！=yii::$app->session['puid']){//平台信息不存在或者平台发现改变时则重新获取平台信息
			$platname = Plateform::find()->where(['punid'=>$puid])->one();//查该平台的信息
			if(!$platname){//该平台不存在则找16游平台信息
				$platname = Plateform::find()->where(['punid'=>'16you'])->one();//查该平台的信息
			}
			yii::$app->session['plateform'] = $platname;
		}
		yii::$app->session['puid'] = $puid;
		//判断是否已经获取到openid
		if(!yii::$app->session['user']){
			yii::$app->session['server'] = $_SERVER['REQUEST_URI'];
			$this->redirect('/wxauth/auth.html');
			yii::$app->end();
		}
		
		$share = NEW Shareutil();//微信分享
		$appid = yii::$app->params['wxinfo']['appid'];
		$secret = yii::$app->params['wxinfo']['secret'];
		$signPackage = $share->getSignPackage($appid,$secret);
		YII::$app->session->set('signPackage',$signPackage);//微信分享的参数
	}
}