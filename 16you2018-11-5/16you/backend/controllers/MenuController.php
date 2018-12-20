<?php
namespace backend\controllers;

use Yii;
use backend\controllers\BaseController;
use common\models\AuthMenu;
use common\common\Pages;
use yii\data\Pagination;
use common\common\Helper;

/**
 * 后台菜单管理
 * @author lianshang
 *
 */
class MenuController extends BaseController{
	/**
	 * 跳转菜单首页
	 */
	public function actionIndex(){ 
		$model = new AuthMenu(); 
        //分页
        $state = Yii:: $app->request->get('state','');
        $curPage = Yii:: $app->request->get( 'page',1);
        $pageSize = yii::$app->params['pagenum'];
        //搜索
        $value = Helper::filtdata(Yii:: $app->request->get('keyword',''));    
        $search = ($value)?['like','name',$value]: '';
        //查询语句
        $query = $model->find()->where(['parent'=>-1])->orderBy('id DESC');
        $data =  Helper::getPages($query,$curPage,$pageSize,$search);
        $data['data'] =  ($data['data'])?$data['data']->asArray()->all():'';
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]); 
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/menu/index.html';
        return $this->render('index', [
             'data' => $data,
             'pages' => $pages,
        	 'search'=>$value,
        ]);
	}
	
	/**
	 * 跳转添加页面
	 */
	public function actionToadd(){
		$menu = new AuthMenu();
		$fmenu = $menu->find()->asArray()->all();
		return $this->render('add',[
				'fmenu' => $fmenu,
		]);
	}
	
	/**
	 * 添加菜单
	 */
	public function actionAdd(){
		$fmenu = Helper::filtdata(Yii:: $app->request->post('fmenu','')); //父级菜单
		$name = Helper::filtdata(Yii:: $app->request->post('name',''));
		$route = Helper::filtdata(Yii:: $app->request->post('route',''));
		$icon = Helper::filtdata(Yii:: $app->request->post('icon',''));
		$param = Helper::filtdata(Yii:: $app->request->post('param',''));
		$weight = Helper::filtdata(Yii:: $app->request->post('weight','0'),'INTEGER');//包括0
		if($fmenu===false ||$name===false ||$route===false ||$icon===false ||$param===false || $weight===false){
		          json_encode([
	                     'errorcode'=>1001,
	                     'info'=>'系统参数错误',
		          ]);
		}
		//验证数据
		$dataarr = ['父级菜单'=>$fmenu,'菜单名称'=>$name,'权重'=>$weight];
		$dres =  $this->checkingdata($dataarr,2);
		if($dres['errorcode']=='1001'){
			return json_encode ( [
					'info' => '您好，'.$dres['info'].'不能为空,请填完整！',
					'errorcode' => '1001'
					] );
			exit ();
		}
		$pdataarr = ['路由'=>$route,'小图标'=>$icon,'参数'=>$param];
		$pdres = $this->checkingdata($pdataarr,1);
		if($pdres['errorcode']=='1001'){
			return json_encode ( [
					'info' => '您好，'.$pdres['info'].'填写错误,请填完整！',
					'errorcode' => '1001'
					] );
			exit ();
		}
		
		if(isset($_POST['id'])){//编辑
			$id = Helper::filtdata($_POST['id']);
			if(!$id){
				return json_encode([
						'info'=>'参数错误',
						'errorcode'=>1001,
						]);
			}
			$menu = AuthMenu::findOne(['id'=>$id]);
		}else{//保存
			$menu = new AuthMenu();
		}
		
		$menu->name = $name;
		$menu->parent = $fmenu;
		$menu->route = $route;
		$menu->icon = $icon;
		$menu->param = $param;
		$menu->weight = $weight;
		
		if($menu->save()){
			 $info = isset($_POST['id'])?'修改成功':'添加成功';
			 return json_encode ( [
					'info' => $info,
					'errorcode' => '0'
					] );
		}else{
			 $info =isset($_POST['id'])?'修改失败':'添加失败';
			 return json_encode ( [
					'info' => $info,
					'errorcode' => '1001'
					] );
		}
	}
	
	/**
	 * 检测数据
	 * $type=1 检测数据不为false
	 * $type =2 字符串类型不为空
	 * @param unknown $data
	 * @return multitype:number unknown |multitype:number
	 */
	private function checkingdata($data,$type=1){
		if($type==1){
			foreach ($data as $k=>$d){
				if($d===false){
					return ['info'=>$k,'errorcode'=>1001];$break;
				}
			}
		}elseif($type==2){
			foreach ($data as $k=>$d){
				if($d==''){
					return ['info'=>$k,'errorcode'=>1001];$break;
				}
			}
		}
		return ['errorcode'=>0];
	}
	
	
	/**
	 * 查看子菜单
	 */
	public function actionChildmenu(){
		if(isset($_POST['id'])){
			$id = Helper::filtdata($_POST['id']);
			if(!$id){
				return json_encode([
						'info'=>'参数错误',
						'errorcode'=>1001,
				]);
			}
			$menu = new AuthMenu();
		    $cmenu = $menu->find()->where(['parent'=>$id])->asArray()->all();
			if($cmenu){
				$info=$cmenu;
				$errorcode=0;
			}else{
				$info='暂无子菜单';
				$errorcode=1001;
			}
			return json_encode([
					'info'=>$info,
					'errorcode'=>$errorcode,
			]);
			
		}
		
	}
	
	
	/**
	 * 跳转编辑页面
	 */
	public function actionToedit(){
		if(isset($_GET['id'])){
			$id = Helper::filtdata(yii::$app->request->get('id'));
			if(!$id){
				$this->render('index');
			}
			$authmenu = new AuthMenu();
			$fmenu = $authmenu->find()->asArray()->all();
			$menuarr = array(); //存储需要编辑的内容
			foreach ($fmenu as $m){
				if($m['id']==$id){
					$menuarr['id'] = $m['id'];
					$menuarr['name'] = $m['name'];
					$menuarr['parent'] = $m['parent'];
					$menuarr['route'] = $m['route'];
					$menuarr['param'] = $m['param'];
					$menuarr['icon'] = $m['icon'];
					$menuarr['weight'] = $m['weight'];
				}
			}
			return $this->render('edit',[
	             'fmenu'=>$fmenu,
				'menuarr'=>$menuarr,
			]);
		}
	}
	
	/**
	 * 编辑页面
	 */
	/* public function actionEdit(){
		if(isset($_POST['id'])){
			$id = Helper::filtdata($_POST['id']);
			$name = Helper::filtdata(Yii:: $app->request->post('name',''));
			$route = Helper::filtdata(Yii:: $app->request->post('route',''));
			$icon = Helper::filtdata(Yii:: $app->request->post('icon',''));
			$param = Helper::filtdata(Yii:: $app->request->post('param',''));
			if(!$id || $route==='false' || $icon==='false' || $param==='false'){
				$this->render('index');
			}
			$authmenu = AuthMenu::findOne(['id'=>$id]);
			$authmenu->name = $name;
			$authmenu->route = $route;
			$authmenu->icon = $icon;
			$authmenu->param = $param;
			
			if($authmenu->save()){
				$this->redirect('/menu/index.html');
			}else{
				echo '编辑失败';
			}
		}
	} */
	
	
	/**
	 * 删除父级菜单
	 */
	public function actionDel(){
		if(isset($_POST['id'])){
		   $id = Helper::filtdata($_POST['id']);
		   if(!$id){
		   	return json_encode([
		   			'info'=>'参数错误',
		   			'errorcode'=>1001,
		   			]);
		   	exit();
		   }
		   $res = AuthMenu::deleteAll(['id'=>$id]);
		   if(isset($_POST['type'])){//删除该菜单下的所有子菜单
		   	AuthMenu::deleteAll(['parent'=>$id]);  
		   }
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
	
	//删除子菜单
	public function actionDelcmenu(){
		if(isset($_POST['id'])){
			$id = Helper::filtdata($_POST['id']);
			if(!$id){
				return json_encode([
						'info'=>'参数错误',
						'errorcode'=>1001,
						]);
				exit();
			}
			$res = AuthMenu::deleteAll(['id'=>$id]);
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
}

?>