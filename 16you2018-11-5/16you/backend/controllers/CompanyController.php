<?php

namespace backend\controllers;

use Yii;
use common\models\Company;
use backend\controllers\BaseController;
use yii\data\Pagination;
use common\common\Helper;
use common\models\DefaultSetting;
use common\models\Manage;
use common\models\Plateform;

/**
 * 公司类
 */
class CompanyController extends BaseController{
    /**
     * 进入公司记录页.
     * @return mixed
     */
    public function actionIndex(){
    	$managemodel =  yii::$app->session['tomodel'];
    	if(yii::$app->session['role']==-1){  //超级管理员
    		$where = 'role!=-1';
    	}else{
    		$id = '';
    		if(yii::$app->session['tomodel']->type==0){//平台管理者
    			$plateform = Plateform::findOne(['id'=>$managemodel->g_p_id]);
    			if($plateform){
    				$id = $plateform->cid;  //平台商id
    			}
    		}elseif(yii::$app->session['tomodel']->type==1){ //流量主或者公司
    			$id = yii::$app->session['tomodel']->g_p_id;  //平台商id
    		}
    		($id)?$where = ['id'=>$id]:$where = ['id'=>-1];
    	} 
    	
        $model = new Company();
        //分页
        $curPage = Yii:: $app->request->get( 'page',1);
        $pageSize = yii::$app->params['pagenum'];
        //搜索
        $value = Helper::filtdata(Yii:: $app->request->get('value',''));    
        $search = ($value)?['like','compname',$value]: '';
        $query = $model->find()->where($where)->orderBy('createtime DESC');
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        $data['data'] =  ($data['data'])?$data['data']->all():'';
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        //菜单定位
        unset(yii::$app->session['localsecondurl']);
        yii::$app->session['localfirsturl'] = yii::$app->params['backend'].'/company/index.html';
        return $this->render('index', [
           'data' => $data,
           'pages' => $pages,
           'value'=>$value,
           'managemodel'=>$managemodel
        ]);
    }

    /**
     * 进入添加公司页面
     * @return [type] [description]
     */
    public function actionAdd(){
      $role =   yii::$app->authManager->getRoles();
      return $this->render('add',[
            'role'=>$role,
      ]);
    }

    /**
     * 接收添加数据
     */
    public function actionCreate(){
        if(!isset($_POST['compname'])||!isset($_POST['linkman'])||!isset($_POST['phone'])||!isset($_POST['role'])){
        	return $this->redirect('add.html');
            exit;
        }
        $app = Yii::$app->request;
        $password = htmlspecialchars(trim(rand(1000,9999).md5($app->post('password'))));
        $model = new Company();
        if(isset($_POST['id'])){
          $model = $model->findOne($_POST['id']);
        }
        $model->compname = Helper::filtdata($app->post('compname'));
        $model->role = Helper::filtdata($app->post('role'));
        $model->linkman = Helper::filtdata($app->post('linkman'));
        $model->phone = Helper::filtdata($app->post('phone'));
        $model->province = Helper::filtdata($app->post('province'));
        $model->city = Helper::filtdata($app->post('city'));
        $model->area = Helper::filtdata($app->post('area'));
        $model->address = Helper::filtdata($app->post('address'));
        $model->createtime = time();
        if($model->save()){
        	$manager = Manage::findOne(['type'=>1,'g_p_id'=>$model->id]);
       		 if($manager){
	 			    ($manager->username!=$model->phone) && $manager->username = $model->phone;
	 				($manager->password!=$password && $_POST['password']!='') && $manager->password =$password;($manager->role !=$model->role) && $manager->role = $model->role;
	 			     $manager->updated_at = time();
	 			     $manager->type = 1;
	 			     $manager->state = 1;
	 			     $manager->remark = $model->compname;
	 			}else{
	 				$manager = new Manage();
	 				$manager->username = $model->phone;
	 				$manager->password = $password;
	 				$manager->role = $model->role;
	 				$manager->state = 1;
	 				$manager->updated_at = time();
	 				$manager->created_at = time();
	 				$manager->g_p_id = $model->id;
	 				$manager->type = 1;  //0 平台， 1公司
	 				$manager->remark = $model->compname;
	 			}
        	
            if($manager->save()){
	 				if(isset($_POST['role'])){
	 					yii::$app->authManager->revokeAll($manager->id);//先删除所有角色
	 				}
	 				$role = yii::$app->authManager->getRole($_POST['role']);
	 				$res = yii::$app->authManager->assign($role, $manager->id); //重新添加角色
	 		}
            $default = new DefaultSetting();
            $dres = $default->find()->where(['cid'=>$model->id])->one();
            if(!$dres){
                $_de = clone $default;
                $_de->cid = $model->id;
                $_de->createtime = time();
                $_de->proportion = '50&#@50';
                $_de->gid = '';
                $_de->save();
            }
            if($res){
                 return $this->redirect('index.html');
            }else{
                return $this->redirect('add.html');
            }
        }
    }

    /**
     * 加载编辑页面
     */
    public function actionEdit($id){
        $model = $this->findModel($id);
        $assignment = yii::$app->authManager->getAssignments($id);
        $roles =    yii::$app->authManager->getRoles();
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
     * 判断添加时的公司名是否存在
     */
    public function actioncompname(){
        $compname = Helper::filtdata(Yii::$app->request->post('compname'),'STRING');
        $res = Company::findOne(['compname'=>$compname]);
        if($res)
            return 1;
        else
        	return 0;

    }

    /**
     * 删除公司
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        $id = Helper::filtdata(Yii::$app->request->post('id'),'INT');
        $company = $this->findModel($id);
        $manage = '';
        $company && $manage = Manage::findOne(['type'=>1,'g_p_id'=>$company->id]);
        $res = $company->delete();
        if($res){
        	$manage &&  $manage->delete();
        	$manage && yii::$app->authManager->revokeAll($manage->id);
            return 1;//'删除成功！';
        }else{
            return 0;//'删除失败！';
        }
    }

    /**
     * 管理员修改密码
     * @return [type] [description]
     */
    public function actionTopwd(){
        if(!Yii::$app->request->isAjax||!isset($_POST['pwd'])||!isset($_POST['newpwd']))
            return json_encode(['info'=>'数据参数错误','errorcode'=>1001]);
        
        $id = yii::$app->session['tomodel']->id;
        $pwd = md5(Helper::filtdata(Yii::$app->request->post('pwd')));
        $newpwd = md5(Helper::filtdata(Yii::$app->request->post('newpwd')));
        $app = Yii::$app->request;
        $model = $this->findModel($id);
        $oldpwd = substr($model->password,4);
        if($pwd==$oldpwd){
            $model->password = rand('1000','9999').$newpwd;
            if($model->save())
                return json_encode(['info'=>'保存成功','errorcode'=>0]);
            else
                return json_encode(['info'=>'保存失败,请稍后再试','errorcode'=>1002]);

        }else{
            return json_encode(['info'=>'旧密码输入错误','errorcode'=>1003]);
        }
    }
    
    /**
     * 判断手机号码是否存在
     */
    public function actionPhone(){
    	$phone = Helper::filtdata(Yii::$app->request->post('phone'),'STRING');
    	$res = Company::findOne(['phone'=>$phone]);
    	if($res){
    		$code = 1;
    		if(isset($_POST['id'])){ //编辑
    			$code = ($res->id == $_POST['id']) ?0:1;
    		}
    		return $code;
    	}else{
    		return 0;
    	}
    }
    

    /**
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Company::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
