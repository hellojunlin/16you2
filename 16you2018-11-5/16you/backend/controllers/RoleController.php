<?php
namespace backend\controllers;

use Yii;
use backend\controllers\BaseController;
use yii\data\Pagination;
use common\common\Helper;
use common\models\Role;
use backend\assets\AppAsset;
use common\models\AuthItemChild;
use common\models\AuthMenu;
use common\models\MenuPermiss;

/**
 *角色管理类
 * @author lianshang
 *
 */
class RoleController extends BaseController{
	/**
	 * 跳转角色首页
	 */
	public function actionIndex(){
        $model = new Role();
        //分页
        $state = Yii:: $app->request->get('state','');
        $curPage = Yii:: $app->request->get( 'page',1);
        $pageSize = yii::$app->params['pagenum'];
        //搜索
        $value = Helper::filtdata(Yii:: $app->request->get('name',''));    
        $search = ($value)?['like','name',$value]: '';
        //查询语句
        $query = $model->find()->orderBy('updated_at DESC')->where(['type'=>1]);
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        $data['data'] =  ($data['data'])?$data['data']->asArray()->all():'';
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/role/index.html';
        return $this->render('index', [
             'data' => $data,
             'pages' => $pages,
        	 'search'=>$value,
        ]);
	}
	
	/**
	 * 进入添加角色页面
	 * @return [type] [description]
	 */
	public function actionToadd(){
		$menupermiss = (new \yii\db\Query())  //菜单
		->select('menu.name,menu.route,menu.weight,menu.id,mp.m_id,mp.permiss,item.description')
		->from('g_auth_menu as menu')
		->Join('right join','g_menu_permiss as mp','menu.id=mp.m_id')
		->Join('left join','g_auth_item as item','item.name=mp.permiss')
		->orderBy('menu.weight')
		->all();
		$mparr = array(); //存储权限 
		if($menupermiss){
			foreach ($menupermiss as $mp){
				$temparr = array();
				foreach ($menupermiss as $m){
					if($mp['name']==$m['name']){ 
						$temparr['route']= $mp['permiss'];
						$temparr['description']= $mp['description'];
					}
				}
				$mparr[$mp['name']][] = $temparr;
			}
		}
		return $this->render('add',[
			'menupermiss'=>$mparr,
		]);
	}
	
	/**
	 * 添加角色
	 */
	public function actionAdd(){
		if(Yii::$app->request->isAjax){
			$name = Helper::filtdata(yii::$app->request->post('name'));
			$description = Helper::filtdata(yii::$app->request->post('description'));
			if(!$name|| $description===false){
				return json_encode([
					'errorcode'=>'1001',
				    'info'=>'角色名称不能为空或描述填写错误',
				]);
				exit();
			}
			
			$res = yii::$app->authManager->getRole($name);
			if($res){//存在
				return json_encode([
					'errorcode'=>'1001',
				    'info'=>'角色名称已存在',
				]);
				exit();
			}
			$model = new Role();
			$role = new \yii\rbac\Role();
			$role->name = $name;
			$role->type = $model->type; 
			$role->description =  $description;
			$role->createdAt = time();
			$role->updatedAt = time(); 
			$createPost = yii::$app->authManager->add($role);
			if($createPost){
				if(isset($_POST['permission'])){
					$permissions =$_POST['permission'];
					foreach ($permissions as $per){
						$permission = Helper::filtdata($per);
						if(!$permission){
						return	json_encode([
							'errorcode'=>'1001',
							'info'=>'系统参数错误',
							]);
							exit();
						}
						$childObj = Yii::$app->authManager->getPermission($per);
						//给item_child写入数据（权限表）
						$res = Yii::$app->authManager->addChild( $role, $childObj );
						if(!$res){
						return	json_encode([
							'errorcode'=>'1001',
							'info'=>'插入数据失败',
							]);
							exit();
						}
					}
				}
				return  json_encode([
						'errorcode'=>'0',
						'info'=>'添加成功',
						]);
				exit();
			}else{
				return	json_encode([
							'errorcode'=>'1001',
							'info'=>'添加失败',
							]);
							exit();
			}
		}
	}
	
	/**
	 * 检测该角色是否存在
	 */
	public function actionCheckname(){
		if(Yii::$app->request->isAjax&&isset($_POST['name'])){
			$name = Helper::filtdata(Yii::$app->request->post('name'),'STRING');
			if(!$name){
				return 2;
			}
			//yii::$app->authManager->getRole($name)
			$res = yii::$app->authManager->getRole($name);
			if($res){//存在
				return 1;
			}
		}
	}
	
	/**
	 * 跳转到编辑页面
	 */
	public function actionToedit(){
		if(isset($_GET['name'])){
			$name = Helper::filtdata($_GET['name']);
			if(!$name){
				echo '参数错误';
				exit();
			}
			$role = yii::$app->authManager->getRole($name);
			$permission = \yii::$app->authManager->getChildren($name);
			$menupermiss = (new \yii\db\Query())  //菜单
			->select('menu.name,menu.route,menu.weight,menu.id,mp.m_id,mp.permiss,item.description')
			->from('g_auth_menu as menu')
			->Join('right join','g_menu_permiss as mp','menu.id=mp.m_id')
			->Join('left join','g_auth_item as item','item.name=mp.permiss')
			//->where("menu.parent!=-1")
			->orderBy('menu.weight')
			->all();
			$mparr = array(); //存储权限
			if($menupermiss){
				foreach ($menupermiss as $mp){
					$temparr = array();
					foreach ($menupermiss as $m){
						if($mp['name']==$m['name']){
							$temparr['route']= $mp['permiss'];
							$temparr['description']= $mp['description'];
						}
					}
					$mparr[$mp['name']][] = $temparr;
				}
			}
			if($role){
		        return $this->render('edit',[
		       		'role'=>$role,
		      		'permission'=>$permission,
		        	'menupermiss'=>$mparr,
                ]);		
			}
		}
	}
	
	/**
	 * 角色编辑
	 */
	public function actionEdit(){
		$name = Helper::filtdata(yii::$app->request->post('name')); //角色名称
		$description = Helper::filtdata(yii::$app->request->post('description')); //描述
		if(!$name || $description===false){
			return json_encode([
					'errorcode'=>'1001',
				    'info'=>'角色名称不能为空或描述填写错误',
				]);
				exit();
		}
		$model = new Role();
		$role = new \yii\rbac\Role();
		$role->name = $name;
		$role->type = $model->type;
		$role->description =  $description;
		$role->updatedAt = time();
		$createPost = yii::$app->authManager->update($name, $role); //更新角色
		Yii::$app->authManager->removeChildren($role);//删除所有权限
		if($createPost){
			if(isset($_POST['permission'])){//有设置权限
				$permissions =$_POST['permission'];
				foreach ($permissions as $per){
					$permission = Helper::filtdata($per);
					if(!$permission){
						return json_encode([
						'errorcode'=>'1001',
					    'info'=>'系统参数错误',
						]);
						exit();
					}
					$childObj = Yii::$app->authManager->getPermission($per);
					//给item_child写入数据（权限表）
					$res = Yii::$app->authManager->addChild($role, $childObj);//重新添加权限
					if(!$res){
						return json_encode([
						'errorcode'=>'1001',
					    'info'=>'编辑失败',
						]);
						exit();
					}
				}
			}
				return json_encode([
						'errorcode'=>'0',
						'info'=>'编辑成功',
						]);
				exit();
		}else{
			 return  json_encode([
							'errorcode'=>'1001',
							'info'=>'添加失败',
							]);
							exit();
		}
	}
	
	/**
	 * 删除角色
	 */
	public function actionDel(){
		if(isset($_POST['name'])){
			$name = Helper::filtdata($_POST['name']);
			if(!$name){
				return json_encode([
						'info'=>'参数错误',
						'errorcode'=>1001,
				]);
				exit();
			}
			$role = yii::$app->authManager->getRole($name);
			$res = yii::$app->authManager->remove($role);
			if($res){
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
	
	/**
	 * 授予权限
	 */
	public function actionToaward(){
		if(isset($_GET['name'])){
			$name = Helper::filtdata(yii::$app->request->get('name'));
			if(!$name){
				echo '参数错误';
				exit();
			}
		$permissions = yii::$app->authManager->getPermissions();
		return $this->render('award',[
	            'name'=>$name,
				'permission'=>$permissions,
	         	]);
		}
	}
	
	/**
	 * 查看权限
	 */
	public function actionLookpermiss(){
		if(isset($_POST['name'])){
			$name = Helper::filtdata(yii::$app->request->post('name'));
			if(!$name){
				return json_encode([
	                   'info'=>'参数错误',
						'errorcode'=>'1001',
				]);
			}
			$permissions = yii::$app->authManager->getPermissions(); //getPermissions();
			$permission =  \yii::$app->authManager->getChildren($name);
			if($permissions){
				return json_encode([
						'errorcode'=>'0',
						'name'=>$name,
						'permissions'=>$permissions,
						'permission' =>$permission,
						]);
			}else{
				return json_encode([
						'info'=>'暂时没有数据',
						'errorcode'=>'1002',
						]);
			}
			
		}
	}
	
	/**
	 * 修改用户权限
	 */
	public function actionUpdatepermiss(){
		if(isset($_POST['name'])&&isset($_POST['rolename'])){
			$name = Helper::filtdata(yii::$app->request->post('name'));
			$rname = Helper::filtdata(yii::$app->request->post('rolename'));
			if(!$name || !$rname){
				return json_encode([
						'info'=>'参数错误',
						'errorcode'=>'1001',
						]);
			}
			$auth =  AuthItemChild::findOne(['parent'=>$rname,'child'=>$name]);
			$authItemChild = new AuthItemChild();
			if($auth){
				$res = $authItemChild->deleteAll(['parent'=>$rname,'child'=>$name]);
				$type = 1; //区分是删除还是添加  1代表删除
			}else{
				$authItemChild->parent = $rname;
				$authItemChild->child = $name;
				$res = $authItemChild->save();
				$type = 2; //区分是删除还是添加  2代表添加
				
			}
			if($res){
				return json_encode([
						'info'=>'更新成功',
						'errorcode'=>'0',
						'type'=>$type,
						]);
			}else{
				return json_encode([
						'info'=>'更新失败',
						'errorcode'=>'0',
						]);
			}
		}
	}

	public function actionAssignpermiss(){
		$u_id = Yii::$app->session['u_id'];
		$data = \Yii::$app->db->createCommand("SELECT * FROM eat_auth_item_child")->queryAll();
		$authmenu = new AuthMenu();
		$menu = $authmenu->find()->asArray()->all();
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
		    $menuarr = $this->assignpermiss($menuarr, $data);
			var_dump($menuarr);
			exit();
		/* if($username!='admin'){
			$menuarr = $this->checkmenu($menuarr, $data);//获取该账号的权限菜单
		}  */
		Yii::$app->session['menu'] = $menuarr; //所有的菜单
		yii::$app->session['mdata'] = $data;   //用户的所有权限 
	  return $this->render('assignpermiss');
	}
	
	/**
	 * 异步获取权限
	 */
	public function actionGetpermiss(){
		if(isset($_POST['route'])){
			$route = Helper::filtdata($_POST['route']);
			if(!$route){
				return  json_encode([
	                'error'=>'1001',
					'info'=>'参数错误',
				]);
			}
			$data = \Yii::$app->db->createCommand("SELECT * FROM eat_auth_item_child where parent=:parent",[':parent'=>$route])->queryAll();
		   return json_encode([
		   		        'error'=>'0',
		   				'info'=>$data,
						]);
		}
	}
	
	/**
	 * 
	 * @param unknown $menuarr 所有菜单  数组
	 * @param unknown $dataarr  权限  数组
	 */
  public function assignpermiss($menuarr,$dataarr){
     	$tmparr=array(); //存储整个权限
   	    foreach ($menuarr as $menu){
   	    	foreach ($dataarr as $d){
   	    		if($menu['route']==$d['parent']){
   	    			$menu['cmenuroute'][]=$d['child'];
   	    			$menu[]=$menu['cmenuroute'];
   	    		}
   	    	}
   	    	
   	    	var_dump($menu);
   	    	
   	    	//foreach ($dataarr as $data){
   	    		/* if($menu['route']==$data['parent']){ //该菜单下的所有权限
   	    			foreach ($menu as $m){
   	    				$menu['cmenuroute'][] = $data['child']; //存储该菜单下的所有权限
   	    			}
   	    			$menuarr[] = $menu['cmenuroute'];
   	    		} */
   	    		/* foreach ($menu as $m){
   	    				$menu['cmenuroute'][] = 111; //存储该菜单下的所有权限
   	    		}
   	    		$menu[] = $menu['cmenuroute'];
   	    		$menuarr[] = $menu; */
   	    	//}
   	    	
   	    	
   	    }exit();
   	    var_dump($menuarr);exit();
   		return $menuarr;
   }	
	 
	
	/**
	 *
	 * @param unknown $menuarr 所有菜单
	 * @param unknown $dataarr  用户拥有的菜单
	 */
	/* public function checkmenu($menuarr,$dataarr){
		$temparr = array(); //临时数组 存储整个菜单
		foreach ($menuarr as $menu ){
			$arr = array();//临时数组 存储子菜单
			$bool_temp = false;
			foreach ($dataarr as $d ){
				if($menu['route']==$d['child']){  //一级跳转的菜单
					$temparr[] = $menu;
				}else{//存在子菜单的顶级菜单目录
					if(isset($menu['cmenu'])){ //存在子菜单
						foreach ($menu['cmenu'] as $cmenu){
							if($cmenu['route']==$d['child']){//该用户拥有该子菜单
								$arr[] = $cmenu;
								$bool_temp = true;
							}
						}
					}
				}
			}
			if($bool_temp){
				$menu['cmenu'] = $arr;
				$temparr[] = $menu;
			}
		}
		return $temparr;
	
	} */
}

?>