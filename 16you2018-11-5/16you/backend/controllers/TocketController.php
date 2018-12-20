<?php
namespace backend\controllers;

use yii;
use backend\controllers\BaseController;
use common\common\Wxinutil;

/**
 * @author He
 */

class TocketController extends BaseController
{	
	public function actionIndex(){
		if(!isset($_SESSION['rev'])){
            yii::$app->session['rev'] = yii::$app->params['replyVideo'];
        }
        $replyvideo = yii::$app->session['rev'];
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/tocket/index.html';
        return $this->render('add',['model'=>$replyvideo]);
	}

	public function actionCreate(){
		if(!yii::$app->request->isAjax||!isset($_POST['tocket'])||!isset($_POST['wxappid'])){
			return json_encode([
				'info'=>'数据错误',
				'errorcode'=>1001
			]);
		}
		$tocket = htmlspecialchars(trim($_POST['tocket']));
		$appid = htmlspecialchars(trim($_POST['wxappid']));
		$imagedir = dirname(dirname(__FILE__)).'/web/media/wxin/tocket/';
		$wxinutil = new Wxinutil();
        $image = $wxinutil->toTocket($appid,$tocket,$imagedir);
        if($image){
        	return json_encode([
        		'info'=>'/media/wxin/tocket/'.$image,
				'errorcode'=>0
        	]);
        }else{
        	return json_encode([
        		'info'=>'生成失败',
				'errorcode'=>1002
        	]);
        }
	}
}