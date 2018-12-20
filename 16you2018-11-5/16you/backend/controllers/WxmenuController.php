<?php
namespace backend\controllers;

use Yii;
use backend\controllers\BaseController;
use yii\filters\VerbFilter;
use common\common\Helper;
use yii\data\Pagination;
use common\models\Wxmenu;
use common\common\Wxinutil;
/**
 * 微信自定义菜单类
 * WxmenuController
 */
class WxmenuController extends BaseController
{

    public function actionTomenu(){
      $data = yii::$app->params['replyVideo'];
      //菜单定位
      unset(yii::$app->session['localfirsturl']);
      yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/wxmenu/tomenu.html';
      return $this->render('tomenu',['data'=>$data]);
    }

    /**
     * 菜单首页
     * @return string
     */
    public function actionIndex(){
        $model = new Wxmenu(); 
        //查询语句
        $wxappid = Helper::filtdata(yii::$app->request->get('appid'));
        yii::$app->session['wxappid'] = $wxappid;
        $data = $model->find()->where(['wxappid'=>$wxappid])->asArray()->one();
        $arr = array();
        if($data){
          $content = json_decode($data['content']);
          foreach ($content as $k => $v) {
            $arr[$k]['wxappid'] = $data['wxappid'];
            $arr[$k]['name'] = $v->name;
            if(isset($v->sub_button)){//如果有子菜单
              foreach ($v->sub_button as $key => $val) {
                $arr[$k]['sub_button'][$key]['name'] = $val->name;
                $arr[$k]['sub_button'][$key]['type'] = $val->type;
                if($val->type == 'view'){
                  $arr[$k]['sub_button'][$key]['content'] = $val->content;
                }else{
                  $arr[$k]['sub_button'][$key]['key'] = $val->key;
                }
              }
            }else{
              if($v->type == 'view'){
                $arr[$k]['content'] = $v->content;
              }else{
                $arr[$k]['key'] = $v->key;
              }
              $arr[$k]['type'] = $v->type;
            }
          }
        }
        $count = count($arr);
        return $this->render('index', ['model' => $arr,'data'=>$data,'count'=>$count]);
    }

    /**
     * 进入添加菜单页面
     * @return [type] [description]
     */
    public function actionAdd(){
        if(!isset($_GET['appid'])){
          return $this->redirect('tomenu.html');
        }
        if(!isset($_SESSION['rev'])){
            yii::$app->session['rev'] = yii::$app->params['replyVideo'];
        }
        $replyvideo = yii::$app->session['rev'];
        $appid = Helper::filtdata($_GET['appid']);
        $findex = yii::$app->request->get('findex','-1');
        $name = $wxname = '';
        foreach($replyvideo as $v){
          if($v['appid']==$appid){
            $wxname = $v['name'];
          }
        }
        if(isset($_GET['name'])){
          $name = $_GET['name'];
        }
        return $this->render('add',['model'=>$replyvideo,'findex'=>$findex,'name'=>$name,'appid'=>$appid,'wxname'=>$wxname]);
    }

    /**
     * 接收添加数据
     */
    public function actionCreate(){   
      $appid = trim(yii::$app->session['wxappid']);
      $findex = trim($_POST ['findex']); // 父级菜单在数组中的索引位置
      $name = trim($_POST ['name']); // 菜单名称
      $type = trim($_POST ['type']); // 菜单类型：click , view
      $key = ($type=='click')?trim($_POST ['key']):''; // 菜单类型对应的字段类型
      $content = trim(yii::$app->request->post('content'));
      $wxmenu = new Wxmenu();      
      $menu = $wxmenu->find ()->where ( [ 'wxappid' => $appid ] )->one ();
      if ($findex == -1) { // 添加一级菜单
        $newbtn = array ();
        $newbtn ['name'] = $name;
        $newbtn ['type'] = $type;
        if ($newbtn ['type'] == 'view') {
          $newbtn ['content'] = $content;
        }else{
          $newbtn ['key'] = $key;
        }
        if ($menu != null) { // 当已存在一级菜单时
          $menuarr = json_decode ( $menu->content );
          $menuarr [] = $newbtn;
          $menu->content = json_encode ( $menuarr );
          $res = $menu->save ();
        } else { // 不存在菜单，新建
          $menuarr = array ();
          $menuarr [] = $newbtn;
          $wxmenu->content = json_encode ( $menuarr );
          $wxmenu->wxappid = $appid;
          $wxmenu->createtime = time();
          $res = $wxmenu->save ();
        }
      } else { // 添加二级菜单
        $contentarr = json_decode ( $menu->content );
        $fmenu = $contentarr [$findex]; // 找到对应的父级菜单
                                       // var_dump(isset ( $fmenu->sub_button ));
        if (isset ( $fmenu->sub_button )) { // 存在子菜单
          $sub_button = $fmenu->sub_button;
          $newsubbtn = array ();
          $newsubbtn ['name'] = $name;
          $newsubbtn ['type'] = $type;
          if ($newsubbtn ['type'] == 'view') {
            $newsubbtn ['content'] = $content;
          }else{
            $newsubbtn ['key'] = $key;
          }
          $subarr = $contentarr [$findex]->sub_button;
          $subarr [] = $newsubbtn;
          $contentarr [$findex]->sub_button = $subarr;
          $menu->content = json_encode ( $contentarr );
          $res = $menu->save ();
        } else { // 不存在子菜单
          $sub_button ['name'] = $name;
          $sub_button ['type'] = $type;
          if ($sub_button ['type'] == 'view') {
            $sub_button ['content'] = $content;
          }else{
            $sub_button ['key'] = $key;
          }
          $contentarr [$findex]->sub_button [] = $sub_button;
          if ($contentarr [$findex]->type == 'view') {
            unset ( $contentarr [$findex]->content);
          }else{
            unset ( $contentarr [$findex]->key );
          }
          unset ( $contentarr [$findex]->type );
          $menu->content = json_encode ( $contentarr );
          $res = $menu->save ();
        }
      }
      return $this->redirect('/wxmenu/index.html?appid='.$appid); 
    }

  /**
   * 生成菜单到公众号
   */
  public function actionCreatmenutowx() {
    if(yii::$app->request->isAjax){
      $appid = \yii::$app->session->get('wxappid');
      $menudata = isset($_POST ['menudata'])?$_POST['menudata']:'';
      $menuarr = unserialize($menudata); // 反序列化对象
      $wxutil = new Wxinutil ();
      if($appid!=null){
        $res = $wxutil->createMenu($menuarr,$appid);
        $msg = json_decode($res);
        if($msg->errmsg=='ok'){
          return json_encode([
            'errorcode'=>0,
            'info'=>'生成成功',
          ]);
        }else{
          return json_encode([
            'errorcode'=>1001,
            'info'=>$msg->errmsg,
          ]);
        }
      }else{
        return json_encode([
            'errorcode'=>1002,
            'info'=>'请选择公众号',
          ]);
      }
    }
  }
  
  /**
   * 删除菜单
   */
  public function actionDelmenu() {
    if(yii::$app->request->isAjax){
      $appid = \yii::$app->session->get('wxappid');
      if(!$appid){//检查session是否过期
        return '网络错误,请稍后再试';
        exit;
      }
      $menudata = Wxmenu::findOne(['wxappid'=>$appid]);
      if(!$menudata){
        return '数据异常,请稍后再试';
        exit;
      }
      $contentarr = json_decode ( $menudata->content );
      $findex = isset($_POST['findex'])?$_POST['findex']:-1;
      $tindex = isset($_POST['tindex'])?$_POST['tindex']:-1;
      if ($tindex==-1) { // 删除二级菜单
        array_splice ( $contentarr ,$findex,1); // 删除指定的数组
        if(!$contentarr){
          $menudata->delete();
          return 1;
        }
      } else{ // 删除一级菜单
        array_splice ( $contentarr [$findex]->sub_button,$tindex,1); // 删除指定的数组
        if(!$contentarr [$findex]->sub_button){
          array_splice ( $contentarr ,$findex,1);
          if(!$contentarr){
            $menudata->delete();
            return 1;
          }
        }
      }
      $menudata->content = json_encode ( $contentarr );
      if($menudata->save ()){
        return 1;
      }else{
        return '删除失败';
      }
    }
  }
  
  /**
   * 跳转到编辑页面
   */
  public function actionToeditmenu(){
    $appid = \yii::$app->session->get ('wxappid');
    if(!isset($_SESSION['rev'])){
              yii::$app->session['rev'] = yii::$app->params['replyVideo'];
          }
    $res = yii::$app->session['rev'];
    $wxname = '';
    foreach($res as $v){
      if($v['appid']==$appid){
        $wxname = $v['name'];
      }
    }
    $menudata = Wxmenu::findOne(['wxappid' => $appid]);
    $contentarr = json_decode($menudata->content);
    $findex = isset($_GET['findex'])?$_GET['findex'] : -1;  //一级菜单下标
    $tindex = isset($_GET['tindex'])?$_GET['tindex'] : -1;  //二级菜单下标
    $jump = isset($_GET['jump'])?$_GET['jump'] : -1;  //判断是否编辑一级菜单
    $subarr = $contentarr[$findex];
    $fname = $contentarr[$findex]->name;
    $data = '';
    if($jump==-1){
        if($tindex==-1){
          $data = $subarr;
          $fname = '';
        }else{
            $data = $subarr->sub_button[$tindex];          
        }
    }
    
    return $this->render('edit', [
        'data'=>$data,
        'appid'=>$appid,
        'wxname'=>$wxname,
        'fname'=>$fname,
        'findex' => $findex,
        'tindex' => $tindex,
        'jump'=>$jump
        ] );
  }
  
  /**
   * 编辑菜单
   */
  public function actionUpdate(){
    $appid = \yii::$app->session->get ( 'wxappid' );
    $findex = $_POST ['findex']; // 父级菜单在数组中的索引位置
    $tindex = $_POST ['tindex']; //二级菜单在数组中的索引位置
    $jump = $_POST ['jump']; //判断是否编辑一级菜单
    if($jump==-1){
      $name = trim($_POST ['name']); // 菜单名称
      $type = trim($_POST ['type']); // 菜单类型：click , view
      $key = ($type=='click')?trim($_POST ['key']):''; // 菜单类型对应的字段类型
      $content = trim(yii::$app->request->post('content'));
    }
    $wxmenu = Wxmenu::find()->where(['wxappid'=>$appid])->one();
    $menuarr = json_decode ($wxmenu->content);
    if($tindex==-1&&$jump==-1) { // 更新一级菜单
        $newbtn = array ();
        $newbtn ['name'] = $name;
        $newbtn ['type'] = $type;
        if ($type == 'click') {
          $newbtn ['key'] = $key;
        } else {
          $newbtn ['content'] = $content;
        }
        $menuarr [$findex] = $newbtn;
        $wxmenu->content = json_encode ( $menuarr );
        $res = $wxmenu->save ();
    }else{ // 有二级菜单
      if($jump==0){//更新一级菜单
        $fname = trim(yii::$app->request->post('fname'));
        $menuarr [$findex]->name = $fname;
        $wxmenu->content = json_encode ( $menuarr );
        $res = $wxmenu->save ();
      }else{
        $contentarr = json_decode ( $wxmenu->content );
        $fmenu = $contentarr [$findex]; // 找到对应的父级菜单
        $sub_button = $fmenu->sub_button;
        $newsubbtn = array ();
        $newsubbtn ['name'] = $name;
        $newsubbtn ['type'] = $type;
        if ($type== 'click') {
          $newsubbtn ['key'] = $key;
        } else {
          $newsubbtn ['content'] = $content;
        }
        $subarr = $contentarr [$findex]->sub_button;
        $subarr [$tindex] = $newsubbtn;
        $contentarr [$findex]->sub_button = $subarr;
        $wxmenu->content = json_encode ( $contentarr );
        $res = $wxmenu->save ();
      }
    }
    if($res){
      $this->redirect('/wxmenu/index.html?appid='.$appid); 
    }
  }
  
  /**
   * 删除自定义菜单接口
   */
  public function actionDelallmenu(){
    $appid = \yii::$app->session->get('wxappid');
    if(!$appid){
      echo '请选择公众号';
    }
    $wxutil = new Wxinutil ();
    $res = $wxutil->delMenu($appid);
    $msg = json_decode($res);
      if($msg->errmsg=='ok'){
      echo '删除成功';
    }else{
      echo '删除失败';
    } 
  }
}
