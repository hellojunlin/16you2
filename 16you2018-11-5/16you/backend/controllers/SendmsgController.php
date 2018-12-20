<?php
namespace backend\controllers;
use Yii;
use backend\controllers\BaseController;
use common\common\Helper;
use common\models\Sendmsg;
use yii\data\Pagination;
use common\common\Wxinutil;
use common\models\User;
use common\models\Category;


/**
 * /模板消息类
 */
class SendmsgController extends BaseController{
	 //模板消息
	 public function actionIndex(){
	 	$model = new Sendmsg();
	 	//分页
	 	$curPage = Yii:: $app->request->get( 'page',1);
	 	$pageSize = yii::$app->params['pagenum'];
	 	//搜索
	 	$title = Yii:: $app->request->get('title','');
	 	$search = ($title)?['like','os.title',$title]: '';
	 	$query = (new \yii\db\Query())
	 	->select('os.id ,os.t_id,os.data,os.createtime,os.title')
	 	->from('g_sendmsg AS os')
	 	->orderBy('os.createtime DESC');
	 	$data = Helper::getPages($query,$curPage,$pageSize,$search);
	 	$data['data'] =  ($data['data'])?$data['data']->all():''; 
	 	$pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
	 	//菜单定位
	 	unset(yii::$app->session['localfirsturl']);
	 	yii::$app->session['localsecondurl'] = yii::$app->params['backend'] . '/sendmsg/index.html';
	 	return $this->render('index', [
	 			'data' => $data,
	 			'pages' => $pages,
	 			'title' => $title,
	 			]);
	 } 
	
	 /**
	  * 跳转添加页面
	  */
	 public function actionToadd(){
	 	return $this->render('add');
	 }
	 
	 /**
	  * 跳转编辑页面
	  */
	 public function actionToedit(){
		 if(isset($_GET['id'])){
	 		$id = Helper::filtdata($_GET['id'],'INT');
	 		if(!$id){
	 			$this->redirect(['sendmsg/index']);
	 		}
	 		$sendmsg = Sendmsg::findOne(['id'=>$id]);
	 		return $this->render('edit',['sendmsg'=>$sendmsg]);
	 	 }
	 }
	 
	 /**
	  * 添加
	  */
	 public function actionAdd(){
	 	if(yii::$app->request->isAjax && isset($_POST['keyword']) && isset($_POST['temp'])){
	 		$temp = Helper::filtdata($_POST['temp']);    //模板id
	 		$title = Helper::filtdata($_POST['title']);   //标题
	 		$url = Helper::filtdata($_POST['url']);   //跳转链接
            $keywordarr = $_POST['keyword'];    //模板内容数据    数组
            $colorarr = $_POST['color'];  //颜色数组
            $dataarr = array();   //内容与颜色数组合并
            foreach ($keywordarr as $k=>$v){
            	$dataarr[$k]['v'] = $keywordarr[$k];
            	$dataarr[$k]['c'] = $colorarr[$k];
            }
	 		if(!$temp || empty($keywordarr)){ 
	 			return json_encode([
	 					'errorcode'=>1001,
	 					'info'=>'网络异常，稍后在试！！！',
	 					]);
	 		}
	 		$sendmsg = new Sendmsg();
	 		if(isset($_POST['id'])){//编辑
	 			$id = Helper::filtdata($_POST['id'],'INT');
	 			if(!$id){
	 				return json_encode([
	 						'errorcode'=>1001,
	 						'info'=>'网络异常，稍后在试！！！',
	 						]);
	 			}
	 			$sendmsg = $sendmsg->findOne(['id'=>$id]);
	 		}else{
	 			$sendmsg->createtime = time();
	 		}
	 		$sendmsg->t_id = $temp;
	 		$sendmsg->data = json_encode($dataarr);
	 		$sendmsg->title = $title;
	 		$sendmsg->url = $url;
	 		if($sendmsg->save()){
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
	  * 删除
	  * @return string
	  */
	 public function actionDel(){
	 	if(isset($_POST['id'])){
	 		$id = Helper::filtdata(Yii::$app->request->post('id'),'INT');
	 		if(!$id){
	 			return json_encode([
	 					'errorcode'=>'1001',
	 					'info'=>'系统参数错误',
	 					]);
	 		}
	 		$res = Sendmsg::deleteAll(['id'=>$id]);
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
	  * 群发
	  * @return string
	  */
	 public function actionSend(){
	 	if(isset($_POST['id'])){
	 		$id = Helper::filtdata(Yii::$app->request->post('id'),'INT');
	 		if(!$id){
	 			return json_encode([
	 					'errorcode'=>'1001',
	 					'info'=>'网络异常，稍后在试！',
	 					]);
	 			exit;
	 		}
	 		//发送模板消息
	 		$sendmsg = Sendmsg::findOne(['id'=>$id]);
	 		if($sendmsg){
	 			$template_id = $sendmsg->t_id;
	 			$dataarr = json_decode($sendmsg->data,true);
	 			$arr= Array();  //模板消息数据
	 			$firstkeyvalue = isset(current($dataarr)['v'])?urlencode(current($dataarr)['v']):'';
	 			$firstcolor = isset(current($dataarr)['c'])?urlencode(current($dataarr)['c']):'000000';
	 			$endkeyvalue = isset(end($dataarr)['v'])?urlencode(end($dataarr)['v']):'';
	 			$endcolor = isset(end($dataarr)['c'])?urlencode(end($dataarr)['c']):'000000';
	 			//取出第一个和最后一个元素
	 			$arr['first'] = array('value'=>$firstkeyvalue,'color'=>"#".$firstcolor);
	 			$arr['remark'] = array('value'=>$endkeyvalue,'color'=>"#".$endcolor);
	 			//删除第一个和最后一个元素
	 			array_shift($dataarr);
	 			array_pop($dataarr);
	 			foreach($dataarr as $k=>$a){
	 				$keyvalue = isset($a['v'])?urlencode($a['v']) : '';
	 				$keycolor = isset($a['c'])?urlencode($a['c']) : '000000';
	 				$arr['keyword'.($k+1)] = array("value"=>$keyvalue,"color"=>"#".$keycolor);
	 			}
	 			$tmpurl = $sendmsg->url;
	 			$wxutil = new Wxinutil();
	 			$result = false;
	 			$openidarr = User::find()->asArray()->select('openid')->all();
	 			if($openidarr){
	 				foreach ($openidarr as $openid){
	 					if($openid!=''){
	 						$result = $wxutil->sendTmpMessage($openid['openid'],$template_id,$tmpurl,$arr);
	 					}
	 				}	 
	 			}
	 			if($result){
	 				return json_encode([
	 						'errorcode'=>'0',
	 						'info'=>'群发成功',
	 						]);
	 				exit;
	 			}else{
	 				return json_encode([
	 						'errorcode'=>'1001',
	 						'info'=>'网络异常，群发失败',
	 						]);
	 				exit;
	 			}
	 			
	 		}
	 	}
	 }
	 
	 
	 
	 
	 /**
	  * 群发
	  * @return string
	  */
	 public function actionSendself(){
	 	if(isset($_POST['id'])){
	 		$id = Helper::filtdata(Yii::$app->request->post('id'),'INT');
	 		if(!$id){
	 			return json_encode([
	 					'errorcode'=>'1001',
	 					'info'=>'网络异常，稍后在试！',
	 					]);
	 			exit;
	 		}
	 		//发送模板消息
	 		$sendmsg = Sendmsg::findOne(['id'=>$id]);
	 		if($sendmsg){
	 			$template_id = $sendmsg->t_id;
	 			$dataarr = json_decode($sendmsg->data,true);
	 			$arr= Array();  //模板消息数据
	 			$firstkeyvalue = isset(current($dataarr)['v'])?urlencode(current($dataarr)['v']):'';
	 			$firstcolor = isset(current($dataarr)['c'])?urlencode(current($dataarr)['c']):'000000';
	 			$endkeyvalue = isset(end($dataarr)['v'])?urlencode(end($dataarr)['v']):'';
	 			$endcolor = isset(end($dataarr)['c'])?urlencode(end($dataarr)['c']):'000000';
	 			//取出第一个和最后一个元素
	 			$arr['first'] = array('value'=>$firstkeyvalue,'color'=>"#".$firstcolor);
	 			$arr['remark'] = array('value'=>$endkeyvalue,'color'=>"#".$endcolor);
	 			//删除第一个和最后一个元素
	 			array_shift($dataarr);
	 			array_pop($dataarr);
	 			foreach($dataarr as $k=>$a){
	 				$keyvalue = isset($a['v'])?urlencode($a['v']) : '';
	 				$keycolor = isset($a['c'])?urlencode($a['c']) : '000000';
	 				$arr['keyword'.($k+1)] = array("value"=>$keyvalue,"color"=>"#".$keycolor);
	 			}
	 			$tmpurl = $sendmsg->url;
	 			$wxutil = new Wxinutil();
	 			$result = false;
	 			$result = $wxutil->sendTmpMessage('onEnHjspPYL8hnRn8mfhmk-K1UCs',$template_id,$tmpurl,$arr);
	 			if($result){
	 				return json_encode([
	 						'errorcode'=>'0',
	 						'info'=>'群发成功',
	 						]);
	 				exit;
	 			}else{
	 				return json_encode([
	 						'errorcode'=>'1001',
	 						'info'=>'网络异常，群发失败',
	 						]);
	 				exit;
	 			}
	 				
	 		}
	 	}
	 }
	 
	 
	 /**
	  * 无限极分类生成树方法
	  * @param
	  * @return 
	  */
	 private function subtree($arr,$id=0) {
	 	if(!$arr)
	 		return false;
	 	 
	 	static $subs = array(); //子孙数组
	 	foreach ($arr as $v) {
	 		if ($v['pid'] == $id) {
	 			$subs[] = (int)$v['id']; //举例说array('id'=>1),
	 			$this->subtree($arr,$v['id']); //递归
	 		}
	 	}
	 	return $subs;
	 }
	 
}