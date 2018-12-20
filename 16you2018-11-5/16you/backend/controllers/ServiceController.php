<?php 
namespace backend\controllers;

use yii;
use backend\controllers\BaseController;
use common\common\Helper;
use common\models\Company;
use yii\data\Pagination;
use common\models\Game;
use common\models\Order;
use common\models\DefaultSetting;
use common\models\SettingProportion;
use common\models\Plateform;
use common\models\Gift;
use common\models\Newservice;

class ServiceController extends BaseController{
 
    //新服首页
    public function actionIndex() { 
        $gidarr = array();
        $pid = '';
        $cid = '';
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
        $search = ($value)?['like','gg.name',$value]: '';
        $query = (new \yii\db\Query())
        ->select('gg.name,gn.service_code,gn.open_time,gn.state,gn.createtime,gn.id')
        ->from('g_newservice AS gn') 
        ->leftJoin('g_game AS gg','gg.id = gn.gid')
        ->orderBy('gn.createtime DESC')
        ->where($search);
        $cid && ($query = $query->andWhere(["gg.cid"=>$cid]));
        $pid && ($query = $query->andWhere(["gp.id"=>$pid]));
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        $data['data'] =  ($data['data'])?$data['data']->all():'';
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/service/index.html';
        return $this->render('index', [
                'data' => $data, 
                'pages' => $pages,
                'value' => $value,
                ]);
    } 
    
    /**
     * 开服记录添加页面
     */
    public function actionToadd() {
        $game = Game::find()->limit(10000)->asArray()->ALL();
        return $this->render("add",[
                 'game'=>$game,
        ]);
    }
    
    /**
     * 开服记录编辑页面
     */
    public function actionToedit() {
    if(isset($_GET['id'])){
            $id = Helper::filtdata($_GET['id'],'INT');
            if($id){
                $newservice = Newservice::findOne(['id'=>$id]);
                if($newservice){
		          $game = Game::find()->select('id,name')->limit(10000)->asArray()->ALL();
                  return $this->render("edit",[
                			'newservice'=>$newservice,
                			'game'=>$game,
                			]);
                }
                
            }
        }
    }
    
    /**
     * 开服记录编辑页面
     */
    public function actionTocopyadd() {
    	if(isset($_GET['id'])){
    		$id = Helper::filtdata($_GET['id'],'INT');
    		if($id){
    			$newservice = Newservice::findOne(['id'=>$id]);
    			if($newservice){
    				$game = Game::find()->select('id,name')->limit(10000)->asArray()->ALL();
    				return $this->render("copyadd",[
    						'newservice'=>$newservice,
    						'game'=>$game,
    						]);
    			}
    
    		}
    	}
    }
    
    /**
     * 开服记录添加编辑
     */
    public function actionAdd(){
        if(yii::$app->request->isAjax && isset($_POST['gid']) && isset($_POST['service_code']) && isset($_POST['opentime']  ) 
            &&  isset($_POST['state']) ){
            $gid = Helper::filtdata($_POST['gid'],'INT');
            $service_code = Helper::filtdata($_POST['service_code']);
            $opentime = Helper::filtdata($_POST['opentime']);
            $state = Helper::filtdata($_POST['state'],'INTEGER');
            $datastrarr = ['游戏名称'=>$gid,'区号'=>$service_code,'开服时间'=>$opentime];
            $checkres = helper::checkingdata($datastrarr,2);
            if($checkres['errorcode']=='1001'){
                return json_encode ( [
                        'info' => '您好,请填写'.$checkres['info'],
                        'errorcode' => '1001'
                        ] );
                exit ();
            }
            $service = new Newservice();
            if(isset($_POST['id'])){//编辑
                $id = Helper::filtdata($_POST['id'],'INT');
                if(!$id){
                    return json_encode([
                            'errorcode'=>1001,
                            'info'=>'网络异常，稍后在试！！！',
                            ]);
                }
                $service = $service->findOne(['id'=>$id]);
            }else{
                $service->createtime = time();
            }
            $service->gid = $gid;
            $service->service_code = $service_code;
            $service->open_time = strtotime($opentime);
            $service->state = $state;
            if($service->save()){
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
            $res = Newservice::deleteAll(['id'=>$id]);
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
            $service = Newservice::findOne(['id'=>$id]);
            if(!$service){
                return json_encode([
                        'errorcode'=>1001,
                        'info'=>'改游戏不存在,请刷新在试',
                        ]);
            }
            $service->state = $state;
            if($service->save()){
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