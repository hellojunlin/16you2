<?php
namespace frontend\controllers;
use yii;
use yii\web\Controller;

/**
* 公共类
*/
class CommonController extends Controller
{
	//跳转错误页面
	public function actionError(){
		return $this->renderPartial('error');
	}

	/**
	 * 大天使之剑
	 */
	public function actionIndex(){
		return $this->renderPartial('index');
	}
}