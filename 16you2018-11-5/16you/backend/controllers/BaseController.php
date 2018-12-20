<?php 
namespace backend\controllers;

use yii;
use yii\web\Controller;

/**
 *基类，用于判断管理员是否已登录
 * @author he
 */
class BaseController extends Controller{
	/**判断是否有权限
	 * (non-PHPdoc)
	 * @see \yii\web\Controller::beforeAction()
	 */
	public function beforeAction($action)
	{
		if (!parent::beforeAction($action)) {
			return false;
		}
		$controller = Yii::$app->controller->id;  //获取控制器
		$action = Yii::$app->controller->action->id;  //获取方法名
		$permissionName = $controller.'/'.$action;
		$tomodel = yii::$app->session['tomodel'];
		$role = yii::$app->session['role'];
		if($tomodel==''){
			return $this->redirect('/login/login.html');
		}
		if($role!='-1'){//系统默认管理员，不作限制
			$user =  yii::$app->session['tomodel'];              //Company::findOne(['compname'=>$compname]);
			$res = yii::$app->authManager->checkAccess($user['id'], $permissionName);
			if(!$res){
				return $this->redirect('/login/toerror.html');
			}
		}
	
		return true;
	}
	public function init() {
		 if(!isset(Yii::$app->session['tomodel'])||!isset(yii::$app->session['menu'])||!isset(yii::$app->session['mdata']) ||!isset(yii::$app->session['pid']) ||!isset(yii::$app->session['managetype'])){
		 	$this->redirect(['/login/login']);
		 	Yii::$app->end();
		 }
	}
}