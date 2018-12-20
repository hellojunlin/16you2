<?php
namespace app\controllers;
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
}