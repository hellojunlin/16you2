<?php 
namespace backend\controllers;

use yii;
use backend\controllers\BaseController;
use common\common\Helper;
use yii\data\Pagination;
use common\models\Manage;

/**
 * 后台账号管理
 * @author lianshang
 *
 */
class AdminController extends BaseController{
    //账号记录页
    public function actionIndex() { 
        $managemodel = yii::$app->session['tomodel'];
        if($managemodel->type==1){//者游戏商
            $cid = $managemodel->g_p_id;
        }else{
            $pid = $managemodel->g_p_id; //平台id
        }
        //分页
        $curPage = Yii:: $app->request->get('page',1);
        $pageSize = yii::$app->params['pagenum'];
        //搜索
        $value = Helper::filtdata(Yii:: $app->request->get('keyword',''));
        $search = ($value)?['like','username',$value]: '';
        $query = Manage::find()->where(['type'=>4]);
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        $data['data'] =  ($data['data'])?$data['data']->all():'';
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/admin/index.html';
        return $this->render('index', [
                'data' => $data, 
                'pages' => $pages,
                'value' => $value,
                'value'=>$value,
                ]);
    } 
    
    /**
     * 添加页面
     */
    public function actionToadd() {
    	 $role =   yii::$app->authManager->getRoles();
    	 return $this->render("add",[
    	 		'role'=>$role,
    	 ]);
    }
    
    /**
     * 编辑页面
     */
    public function actionToedit() {
    	if(isset($_GET['id'])){
            $id = Helper::filtdata($_GET['id'],'INT');
            if($id){
                $manage = Manage::findOne(['id'=>$id]);
                if($manage){
			        $role =  yii::$app->authManager->getRoles();
	                return $this->render("edit",[
	                			'manage'=>$manage,
	                			'role'=>$role,
	                			]);
                }
            }
        }
    }
    
    /**
     * 开服记录添加编辑
     */
    public function actionAdd(){
        if(yii::$app->request->isAjax && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['role']  ) 
            && isset($_POST['state'])){
            $username = Helper::filtdata($_POST['username']);
            $password = Helper::filtdata($_POST['password']);
            $role = Helper::filtdata($_POST['role']);
            $state = Helper::filtdata($_POST['state'],'INTEGER');
            $datastrarr = ['账号'=>$username];
            $checkres = helper::checkingdata($datastrarr,2);
            if($checkres['errorcode']=='1001'){
                return json_encode ( [
                        'info' => '您好,请填写'.$checkres['info'],
                        'errorcode' => '1001'
                        ] );
                exit ();
            }
            $manager = new Manage();
            $isboolean = true;   //是否需要检验用户名是否存在
            if(isset($_POST['id'])){//编辑
                $id = Helper::filtdata($_POST['id'],'INT');
                if(!$id){
                    return json_encode([
                            'errorcode'=>1001,
                            'info'=>'网络异常，稍后在试！！！',
                            ]);
                }
                $manager = $manager->findOne(['id'=>$id]);
                if($manager){
                	if($manager->username == $username){
                		$isboolean = false;
                	}
                }else{
                	return json_encode([
                			'errorcode'=>1001,
                			'info'=>'后台管理账号不存在！！！',
                			]);
                }
            }else{
                $manager->created_at = time();
            }
            
            if($isboolean){
            	$managerres = Manage::findOne(['username'=>$username]);
            	if($managerres){
            		return json_encode([
            				'errorcode'=>1001,
            				'info'=>'账号已存在，请重换一个',
            				]);
            		exit;
            	}
            }
            $manager->username = $username;
            $password && $manager->password = htmlspecialchars(trim(rand(1000,9999).md5($password)));;
            $manager->state = $state;
            $manager->role = $role;
            $manager->updated_at = time();
            $manager->g_p_id = 0;
            $manager->type = 4;  //0 平台商， 1游戏商  4：普通管理员
            $manager->remark = '普通管理员';
            if($manager->save()){
            	if(isset($_POST['role'])){
            		yii::$app->authManager->revokeAll($manager->id);//先删除所有角色
            	}
            	$role = yii::$app->authManager->getRole($_POST['role']);
            	$res = yii::$app->authManager->assign($role, $manager->id); //重新添加角色
            	$info = (isset($_POST['id']))? '编辑成功':'添加成功';
                return json_encode([
                        'errorcode'=>0,
                        'info'=>$info,
                        ]);
            }else{
                $info = (isset($_POST['id']))? '编辑失败':'添加失败';
                return json_encode([
                        'errorcode'=>1001,
                        'info'=>$info,
                        ]);
            }
        }
    }
    
    /**
     * 判断添加时的账号是否存在
     */
    public function actionUniqueone(){
    	$username = Helper::filtdata(Yii::$app->request->post('username'));
    	$res = Manage::findOne(['username'=>$username]);
    	if($res){
    		$code = 1;
    		if(isset($_POST['id'])){ //编辑
    			($res->id == $_POST['id']) && $code =0;
    		}
    		return $code;
    	}else{
    		return 0;
    	}
    }
   
    
    /**
     * 删除记录
     * @return string
     */
    public function actionDel(){
        if(yii::$app->request->isAjax || isset($_POST['id'])){
            $id = Helper::filtdata(Yii::$app->request->post('id'),'INT');
            if(!$id){
                return json_encode([
                        'errorcode'=>'1001',
                        'info'=>'系统参数错误',
                        ]);
            }
            $res = Manage::deleteAll(['id'=>$id]);
            if($res){
                return json_encode([
                        'errorcode'=>0,
                        'info'=>'删除成功',
                        ]);
            }else{
                return json_encode([
                        'errorcode'=>1001,
                        'info'=>'删除失败',
                        ]);
            }
        }
    }
    
    /**
     * 启用 禁用 改变状态
     */
    public function actionChangestate(){
        if(yii::$app->request->isAjax || isset($_POST['id']) ||isset($_POST['state'])){
            $id = Helper::filtdata($_POST['id'],'INT');
            $state = Helper::filtdata($_POST['state'],'INTEGER');
            if(!$id || $state===false){
                return json_encode([
                        'errorcode'=>1001,
                        'info'=>'网络异常，稍后在试',
                        ]);
            }
            $manage = Manage::findOne(['id'=>$id]);
            if(!$manage){
                return json_encode([
                        'errorcode'=>1001,
                        'info'=>'该账号不存在,请刷新在试',
                        ]);
            }
            $manage->state = $state;
            if($manage->save()){
                $info = ($state==0)?'禁止成功':'启用成功';
                return json_encode([
                        'errorcode'=>0,
                        'info'=>$info,
                        ]);
            }else{
                return json_encode([
                        'errorcode'=>1001,
                        'info'=>'该游戏不存在,请刷新在试',
                        ]);
            }
        }
    }
}