<?php
namespace backend\controllers;

use Yii;
use backend\controllers\BaseController;
use yii\data\Pagination;
use common\common\Helper;
use common\models\Role;
use common\models\Permission;
use common\models\AuthItemChild;
use common\models\AuthMenu;
use common\models\AuthItem;
use common\models\MenuPermiss;
class PermissionController extends BaseController{
	
	/**
	 * 跳转权限首页
	 */
	public function actionIndex(){ 
        $model = new Permission();
        //分页
        $state = Yii:: $app->request->get('state','');
        $curPage = Yii:: $app->request->get( 'page',1);
        $pageSize = yii::$app->params['pagenum'];
        //搜索
        $value = Yii:: $app->request->get('name','');    
        $search = ($value)?['like','item.name',$value]: '';
        //查询语句
        $query = (new \yii\db\Query())
        ->select('item.name,item.description,item.created_at,item.updated_at,mp.m_id,mp.id')
        ->from('g_auth_item as item')
        ->Join('left join','g_menu_permiss as mp','item.name=mp.permiss')
        ->where("item.type=2")
        ->orderBy('item.updated_at desc');
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        $data['data'] = ($data['data'])?$data['data']->all():'';
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);  
        $fmenu = AuthMenu::find()->asArray()->all();
        
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/permission/index.html';
        return $this->render('index', [
             'data' => $data,
             'pages' => $pages,
        	 'search'=>$value,
             'fmenu'=>$fmenu,
        ]);
	}
	
	/**
	 * 进入添加权限页面
	 * @return [type] [description]
	 */
	public function actionToadd(){
		$fmenu = AuthMenu::find()->asArray()->orderBy('weight')->all();
		return $this->render('add',[
				'fmenu'=>$fmenu,
		]);
	}
	
	/**
	 * 添加权限
	 */
	public function actionAdd(){
		if(Yii::$app->request->isAjax){
			$name = Helper::filtdata(yii::$app->request->post('name'));
			$description = Helper::filtdata(yii::$app->request->post('description'));
			$menu_id = Helper::filtdata(yii::$app->request->post('fmenu'));
			if($menu_id===false){
				return json_encode([
						'errorcode'=>'1001',
						'info'=>'请往菜单管理添加菜单',
						]);
				exit();
			}
			if($name==''||$description==''){
				return json_encode([
	                        'errorcode'=>'1001',
						    'info'=>'权限名称或描述不能为空',
		     			]);
				exit();
			}
			
			if(!isset($_POST['type'])){ //添加
				$res = yii::$app->authManager->getPermission($name);
				if($res){//存在
					return json_encode([
							'errorcode'=>'1001',
							'info'=>'权限名称已存在',
							]);
					exit();
				}
			}
		
			$model = new Permission();
			$permission = new \yii\rbac\Permission();
			$permission->name = $name;
			$permission->type = $model->type;
			$permission->description =  $description;
			$permission->createdAt = time();
			$permission->updatedAt = time();
			
			if(isset($_POST['type'])){//编辑
				$id = Helper::filtdata(yii::$app->request->post('id'));
				if($menu_id===false){
					return json_encode([
							'errorcode'=>'1001',
							'info'=>'参数错误',
							]);
					exit();
				}
				$menupermiss = MenuPermiss::findOne(['id'=>$id]);
			}else{//保存
				$menupermiss = new MenuPermiss();
			}
			
			//菜单权限表
			$menupermiss->m_id = $menu_id;
			$menupermiss->permiss = $name;
			
			$createPost = '';
			if(isset($_POST['type'])){//编辑
				    $menupermiss->update();
				 	$createPost = yii::$app->authManager->update($name, $permission);
			}else{//添加
				if($menupermiss->save()){
					$createPost = yii::$app->authManager->add($permission);
				}
			}
			if($createPost){
				$info = (isset($_POST['type']))? '修改成功':'添加成功';
				return json_encode([
	                        'errorcode'=>'0',
						    'info'=>$info,
		     			]);
				exit();
			}else{
				$info = (isset($_POST['type']))? '修改失败':'添加失败';
				return json_encode([
	                        'errorcode'=>'1001',
						    'info'=>$info,
		     			]);
				exit();
			}
		}
	}
	
	/**
	 * 检测该权限是否存在
	 */
	public function actionCheckname(){
		if(Yii::$app->request->isAjax&&isset($_POST['name'])){
			$name = Helper::filtdata(Yii::$app->request->post('name'),'STRING');
			if(!$name){
				return 2;
			}
			$res = yii::$app->authManager->getPermission($name);
			if($res){//存在
				return 1;
			}
		}
	}
	
	/**
	 * 跳转到编辑页面
	 */
	public function actionToedit(){
		if(isset($_GET['name'])&&isset($_GET['mid'])&&isset($_GET['id'])){
			$name = Helper::filtdata($_GET['name']);
			$mid =  Helper::filtdata($_GET['mid']);//菜单id
			$id = Helper::filtdata($_GET['id']); //菜单权限表id
			if(!$name ||!$mid ||!$id){
				echo '参数错误';
				exit();
			}
			$permission = yii::$app->authManager->getPermission($name); 
			$fmenu = AuthMenu::find()->asArray()->orderBy('weight')->all();
			if($permission){
		      return $this->render('edit',[
		       		'permission'=>$permission,
		      		'fmenu'=>$fmenu,
		      		'mid'=>$mid,
		      		'id'=>$id,
              ]);		
			}
		}
	}
	
	
	/**
	 * 删除权限
	 */
	public function actionDel(){
		if(isset($_POST['name'])&&isset($_POST['mid'])){
			$name = Helper::filtdata($_POST['name']);
			$mid = Helper::filtdata($_POST['mid']);
			if(!$name ||!$mid){
				return json_encode([
						'info'=>'参数错误',
						'errorcode'=>1001,
				]);
				exit();
			}
			$permission = yii::$app->authManager->getPermission($name);
			$res = yii::$app->authManager->remove($permission);
			
			$menures = MenuPermiss::deleteAll(['id'=>$mid]);
			if($res && $menures){
				return json_encode([
	                  'info'=>'删除成功',
	                  'errorcode'=>0,
				]);
			}else{
				return json_encode([
						'info'=>'删除失败',
						'errorcode'=>1001,
				]);
			}
	   }
	}
	
}

?>