<?php 
namespace backend\controllers;

use yii;
use backend\controllers\BaseController;
use common\common\Getuserinfoutil;
use common\models\User;

class IndexController extends BaseController{
	//管理员首页
    public function actionIndex() { 
    	//菜单定位 
    	unset(yii::$app->session['localsecondurl']);
    	yii::$app->session['localfirsturl'] = yii::$app->params['backend'].'/index/index.html';
        return $this->render("index");
    } 
    
    public function actionToindex() { 
        return $this->render("details"); 
    }               
         
    //错误页面        
    public function actionToerror() {   
    	return $this ->renderPartial('error'); 
    }

    public function actionTest(){
        $stime=microtime(true);
        echo 'asdasdadsdsd';
        $etime=microtime(true);
        $total=($etime-$stime)*10000000; //计算差值
        echo "<br />[页面执行时间：{$total} ]秒";
    }
   
}