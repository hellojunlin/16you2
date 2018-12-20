<?php

namespace backend\controllers;

use Yii;
use backend\models\Manage;
use yii\data\ActiveDataProvider;
use backend\controllers\BaseController;
use yii\data\Pagination;
use common\common\Helper;

/**
 * 管理员类
 */
class ManageController extends BaseController{
    /**
     * 进入管理员记录页.
     * @return mixed
     */
    public function actionIndex(){

        $model = new Manage();
        //分页
        $curPage = Yii:: $app->request->get( 'page',1);
        $pageSize = yii::$app->params['pagenum'];
        //搜索
        $value = Yii:: $app->request->get('value','');    
        $search = ($value)?['like','username',$value]: '';
        $query = $model->find()->orderBy('created_at DESC');
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        $data['data'] =  ($data['data'])?$data['data']->all():'';
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        yii::$app->session['title'] = '管理员管理';
        return $this->render('index', [
           'data' => $data,
           'pages' => $pages,
        	 'search'=>$value,
        ]);
    }

    /**
     * 进入添加管理员页面
     * @return [type] [description]
     */
    public function actionAdd(){
    	$role =	yii::$app->authManager->getRoles();
      return $this->render('add',[
      		'role'=>$role,
      ]);
    }

    /**
     * 接收添加数据
     */
    public function actionCreate(){
        if(!isset($_POST['username'])||!isset($_POST['password'])||!isset($_POST['state'])||!isset($_POST['role'])){
        	return $this->redirect('add.html');
            exit;
        }
        $app = Yii::$app->request;
        $username = Helper::filtdata($app->post('username'));
        $state = Helper::filtdata($app->post('state'));
        $password = htmlspecialchars(trim(rand(1000,9999).md5($app->post('password'))));
        $state = Helper::filtdata($app->post('state'),'INT');
        $role = Helper::filtdata($app->post('role'));
        if($username===false || $state===false ||$password===false ||$state===false ||$role===false){
        	return $this->redirect('add.html');
        	exit;
        }
        
        $model = new Manage();
        $model->username = $username;
        $model->state = $state;
        $save_dir = dirname(dirname(__FILE__)).'/web/media/images/head_img/';
        $model->head_img = Helper::upload('file','jpeg',$save_dir);
        $model->password = $password;
        $model->state = $state;
        $model->created_at = time()+(3600*8);
        $model->updated_at = time()+(3600*8);
        $model->role = $role;
        if($model->save()){
        	$role = yii::$app->authManager->getRole($_POST['role']);
        	$res = yii::$app->authManager->assign($role, $model->id);
        	if($res){
        		return $this->redirect('index.html');
        	}
            
        }
    }

    /**
     * 加载编辑页面
     */
    public function actionEdit($id){
        $model = $this->findModel($id);
        $assignment = yii::$app->authManager->getAssignments($id);
        $roles =	yii::$app->authManager->getRoles();
        $role = '';
        if(!empty($assignment)){
        	foreach ($assignment as $ass){
        		$role = yii::$app->authManager->getRole($ass->roleName);
        	}
        }
        return $this->render( 'edit', [
        		'model' => $model,
        		'roles'=>$roles,
        		'role'=>$role,
				]);
    }

    /**
     * Updates an existing Manage model.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate(){
    	if(!isset($_POST['username'])||!isset($_POST['password'])||!isset($_POST['state'])){
           return $this->redirect('add.html');
        }
        $id = Helper::filtdata(Yii::$app->request->post('id'),'INT');
        $app = Yii::$app->request;
        $model = $this->findModel($id);
        $model->username = Helper::filtdata($app->post('username'));
        $model->state = Helper::filtdata($app->post('state'));
        if($_FILES['file']['name']){
       		$save_dir = dirname(dirname(__FILE__)).'/web/media/images/head_img/';
        	$model->head_img = Helper::upload('file','jpeg',$save_dir);
        }
        if($app->post('password')){
          $model->password = htmlspecialchars(trim(rand(1000,9999).md5($app->post('password'))));
        }
        $model->state = Helper::filtdata($app->post('state'),'INT');
        $model->updated_at = time()+(3600*8);
        $model->role = Helper::filtdata($app->post('role'));
        if($model->save()){
        	if(isset($_POST['role'])){
        		$role = yii::$app->authManager->getRole($_POST['role']);
        		yii::$app->authManager->revokeAll($id);//先删除所有角色
        		$res = yii::$app->authManager->assign($role, $model->id); //重新添加角色
        		if($res){
        			 return $this->redirect('index.html');
        		}else{
        			return $this->redirect('add.html');
        		}
        	}
           
        }
    }

   /**
     * 判断添加时的管理员账号是否存在
     */
    public function actionUsername(){
        $username = Helper::filtdata(Yii::$app->request->post('username'),'STRING');
        $res = Manage::findOne(['username'=>$username]);
        if($res)
            return 1;
        else
        	return 0;

    }

    /**
     * 删除管理员
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        $id = Helper::filtdata(Yii::$app->request->post('id'),'INT');
        $res = $this->findModel($id);
        if($res){
        	$res->delete();
        	@unlink(dirname(dirname(__FILE__)).'/web/media/images/head_img/'.$res->head_img);
            return 1;//'删除成功！';
        }else{
            return 0;//'删除失败！';
        }
    }

        /**
     * 启用禁用管理员
     * @param integer $id
     * @return mixed
     */
    public function actionState(){
        $id = Helper::filtdata(Yii::$app->request->post('id'),'INT');
        $state = Helper::filtdata(Yii::$app->request->post('state'),'INT');
        $model = $this->findModel($id);
        if($state == 0){//判断是禁用还是启用
            $model->state = 1;//启用
        }else{
            $model->state = 0;//禁用
        }
        if($model->save()){
            return json_encode([
              'info'=>'修改成功',
              'errorcode'=>0,
            ]);
        }else{
            return json_encode([
              'info'=>'修改失败',
              'errorcode'=>1001,
            ]);
         }
    }

    /**
     * Finds the Manage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Manage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Manage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
