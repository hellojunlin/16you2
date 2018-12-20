<?php 
namespace backend\controllers;

use yii;
use backend\controllers\BaseController;

class ClicksController extends BaseController{
	//埋点统计
    public function actionIndex() { 
        $data = $this->actionhttp();
    	//菜单定位 
    	unset(yii::$app->session['localfirsturl']);
    	yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/clicks/index.html';
        return $this->render("index",['data'=>$data]);
    } 
    
    //来源统计
    public function actionSource() { 
        $data = $this->actionhttp();
        //菜单定位 
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/clicks/source.html';
        return $this->render("source",['data'=>$data]); 
    }               
         
    //用户统计 
    public function actionUser() {  
        $data = $this->actionhttp();
        //菜单定位 
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/clicks/user.html';
    	return $this ->render('user',['data'=>$data]); 
    }
            
    //渠道统计 
    public function actionPlat() {  
        $data = $this->actionhttp();
        //菜单定位 
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/clicks/plat.html';
        return $this ->render('plat',['data'=>$data]); 
    }

    //IP统计 
    public function actionIp() {  
        $data = $this->actionhttp();
        //菜单定位 
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/clicks/ip.html';
        return $this ->render('ip',['data'=>$data]); 
    }

    //账号密码加密
    public function actionHttp(){
        $data = yii::$app->params['countsystem'];//获取账号密码
        $res = md5(md5($data['1']).$data['0']);
        return $res.'!!@'.$data['2'];
    }
}