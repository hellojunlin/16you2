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
use common\redismodel\GameRedis;
use common\models\Gift;
use common\models\Refund;
use common\common\Phpexcelr;
use common\models\User;
use common\alisms\SendSms;
use common\common\Wxinutil;
use common\models\Sgame;
use common\redismodel\SgameRedis;

class SgameController extends BaseController{
    //游戏首页
    public function actionIndex() { 
        $gidarr = array();
        $pid = '';
        $cid = '';
        $managemodel = yii::$app->session['tomodel'];
        if($managemodel->type==1){//者游戏商
            $cid = $managemodel->g_p_id;
        }else{
            $pid = $managemodel->g_p_id;  //平台id
        }
        //分页
        $curPage = Yii:: $app->request->get( 'page',1);
        $pageSize = yii::$app->params['pagenum'];
        //搜索
        $value = Helper::filtdata(Yii:: $app->request->get('keyword',''));
        $select = Yii:: $app->request->get('selectval','');
        $search = ($value)?['like',$select,$value]: '';
        $query = (new \yii\db\Query())
        ->select('gg.unique,gg.id,gg.name,gg.gamenum,gg.game_url,gg.head_img,gg.createtime,gg.sort,gg.state')
        ->from('g_sgame AS gg') 
        ->orderBy('gg.sort DESC');
        $cid && ($query = $query->where(["gg.cid"=>$cid]));
        $pid && ($query = $query->where(["gp.id"=>$pid]));
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        $data['data'] =  ($data['data'])?$data['data']->all():'';
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/sgame/index.html';
        return $this->render('index', [
                'data' => $data, 
                'pages' => $pages,
                'value' => $value,
                'select'=>$select,
                'value'=>$value,
                ]);
    } 
    
    /**
     * 游戏添加页面
     */
    public function actionToadd() {
        $company = Company::find()->asArray()->all();
         return $this->render("add",[
                'company'=>$company,
         ]);
    }
  
    /**
     * 游戏编辑页面
     */
    public function actionToedit() {
    if(isset($_GET['id'])){
            $id = Helper::filtdata($_GET['id'],'INT');
            if($id){
                $game = Sgame::findOne(['id'=>$id]);
                if(!$game){//该游戏不存在
                    exit;
                }
                $company = Company::find()->asArray()->all();
                return $this->render("edit",[
                        'company'=>$company,
                        'game'=>$game,
                        ]);
            }
        }
    }
    
    /**
     * 游戏添加编辑
     */
    public function actionAdd(){
        if(yii::$app->request->isAjax && isset($_POST['name']) && isset($_POST['unique']) && isset($_POST['descript']) 
            &&  isset($_POST['state']) && isset($_POST['game_url']) && isset($_POST['sort'])){

            $name = Helper::filtdata($_POST['name']);
            $unique = Helper::filtdata($_POST['unique']);
            $descript = Helper::filtdata($_POST['descript']);
            $state = Helper::filtdata($_POST['state'],'INTEGER');
            $gamenum = (isset($_POST['gamenum']))?$_POST['gamenum']:'';
            $game_url = Helper::filtdata($_POST['game_url']);
            $sort = Helper::filtdata($_POST['sort'],'INTEGER');
            $h_img = (isset($_POST['logo']))?Helper::filtdata($_POST['logo']):'0';
            // 描述  标签 简介允许为空
            $datastrarr = ['游戏名称'=>$name,'游戏唯一标识'=>$unique,'游戏链接'=>$game_url];
            $checkres = helper::checkingdata($datastrarr,2);
            if($checkres['errorcode']=='1001'){
                return json_encode ( [
                        'info' => '您好,请填写'.$checkres['info'],
                        'errorcode' => '1001'
                        ] );
                exit ();
            }
            
            $dataarr = ['状态'=>$state,'排序'=>$sort];
            $check_res = helper::checkingdata($dataarr,1);
            if($check_res['errorcode']=='1001'){
                return json_encode ( [
                        'info' => '您好'.$check_res['info'].'填写不正确,请重写！',
                        'errorcode' => '1001'
                        ] );
                exit ();
            }
            $sgame = new Sgame();
            $sgameredis = new SgameRedis();
            if(isset($_POST['id'])){//编辑
                $id = Helper::filtdata($_POST['id'],'INT');
                if(!$id){
                    return json_encode([
                            'errorcode'=>1001,
                            'info'=>'网络异常，稍后在试！！！',
                            ]);
                }
                $sgame = $sgame->findOne(['id'=>$id]);
                $sgameredis = $sgameredis->findOne(['id'=>$id]);
                $sgameredis = ($sgameredis)?$sgameredis:new SgameRedis();
               
            }else{
                $sgame->createtime = time();
            }
            $sgame->name = $name;
            $sgame->descript = $descript;
            $sgame->unique= $unique;
            $sgame->state = $state;
            $sgame->gamenum = $gamenum;
            $sgame->game_url = $game_url;
            $sgame->sort = $sort;
            $sgame->head_img = $h_img;
            if($sgame->save()){
                //保存到redis中
                $sgameredis->id = $sgame->id;
                $sgameredis->name = $name;
                $sgameredis->descript = $descript;
                $sgameredis->unique= $unique;
                $sgameredis->state = $state;
                $sgameredis->gamenum = $gamenum;
                $sgameredis->game_url = $game_url;
                $sgameredis->sort = $sort;
                $sgameredis->head_img = $h_img;
                $sgameredis->createtime = $sgame->createtime;
                $sgameredis->save();
                if(!isset($_POST['id'])){
                    $info = '添加成功';
                }else{
                    $info = '编辑成功';
                }
                $this->setCache();
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
     * 更新游戏缓存
     * 
     */
    private function setCache(){
            $sgamearr = Sgame::find()->where(['state'=>1])->orderBy('sort desc')->asArray()->select('id,name,descript,gamenum,head_img,game_url')->all();//非热门游戏  
           /*  $hotsgame = array();
            if($sgamearr){
                $index = 1;
                foreach ($sgamearr as $k=>$game){
                    if($index<=50 && $game['type']==1){ //存前50条热门游戏
                        $hotsgame[] = $game;  
                        unset($sgamearr[$k]);  //删除游戏
                        $index++;
                    }
                }
            }
            yii::$app->cache->set('hotsgame',$hotsgame); //热门游戏 */
            yii::$app->cache->set('sgamearr',$sgamearr);
    }
    
    
    /**
     * 删除游戏
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
            $res = Sgame::deleteAll(['id'=>$id]);
            if($res){
                Gift::deleteAll(['gid'=>$id]);
                $this->setCache();
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
            $sgame = Sgame::findOne(['id'=>$id]);
            if(!$sgame){
                return json_encode([
                        'errorcode'=>1001,
                        'info'=>'改游戏不存在,请刷新在试',
                        ]);
            }
            $sgame->state = $state;
            if($sgame->save()){
                $sgameredis = SgameRedis::findOne(['id'=>$id]);
                $sgameredis = ($sgameredis)?$sgameredis:new SgameRedis();
                //保存到redis中
                $sgameredis->id = $sgame->id;
                $sgameredis->name = $sgame->name;
                $sgameredis->descript = $sgame->descript;
                $sgameredis->unique= $sgame->unique;
                $sgameredis->state = $sgame->state;
                $sgameredis->gamenum = $sgame->gamenum;
                $sgameredis->game_url = $sgame->game_url;
                $sgameredis->sort = $sgame->sort;
                $sgameredis->head_img = $sgame->head_img;
                $sgameredis->createtime = $sgame->createtime;
                $sgameredis->save();
                $this->setCache();
                $info = ($state==0)?'禁止成功':'启用成功';
                return json_encode([
                        'errorcode'=>0,
                        'info'=>$info,
                        ]);
            }else{
                return json_encode([
                        'errorcode'=>1001,
                        'info'=>'该小游戏不存在,请刷新在试',
                        ]);
            }
        }
    }
    
    
    /**
     * 异步上传图片
     */
    public function actionSubimg() {
        if (Yii::$app->request->isAjax) {
            $imgb = Helper::filtdata($_POST ['imgbase64']);
            if(!$imgb){
                return json_encode ( [
                        'info' => '系统参数错误',
                        'errorcode' => '1002'
                        ] );
                exit ();
            }
            $imgdir = yii::$app->basePath . "/web/media/images/sgame/";
            if (! is_dir ( $imgdir )) {
                mkdir ( $imgdir, 0777 );
            }
            $savename = uniqid () . '.jpeg';
            $savepath = $imgdir . $savename;
            $claerBase64 = explode ( ',', $imgb );
            $image = $this->base64_to_img ( $imgb, $savepath );
            if ($image) {
                return json_encode ( [
                        'info' => '您好，图片保存成功',
                        'errorcode' => '0',
                        'imgurl' => $savename
                        ] );
            } else {
                return json_encode ( [
                        'info' => '您好，图片异常，请更换其它图片',
                        'errorcode' => '1001'
                        ] );
                exit ();
            }
        }
    }
    
    /**
     * 将base64码转为图片
     *
     * @param unknown $base64_string
     * @param unknown $output_file
     * @return unknown
     */
    private function base64_to_img($base64_string, $output_file) {
        $ifp = fopen ( $output_file, "wb" );
        fwrite ( $ifp, base64_decode ( $base64_string ) );
        fclose ( $ifp );
        return ($output_file);
    }
    
    /**
     * 异步删除图片
     */
    public function actionDelimg() {
        if (Yii::$app->request->isAjax &&isset($_POST ['imgsrc'])) {
            $imgurl = Helper::filtdata($_POST ['imgsrc']);
            $url = yii::$app->basePath . '/web' . $imgurl;
            $res = @unlink ( $url );
            if ($res) {
                return json_encode ( [
                        'info' => '删除成功！',
                        'errorcode' => 0
                        ] );
            }else{
                return json_encode ( [
                        'info' => '删除失败！',
                        'errorcode' => '1001'
                        ] );
                exit ();
            }
        }
    }
    

    /**
     * 数组排序
     */
    private function sigcol_arrsort($data,$col,$type=SORT_DESC){
    	$arr = array();
    	if(is_array($data)){
    		$i=0;
    		foreach($data as $k=>$v){
    			if(key_exists($col,$v)){
    				$arr[$i] = $v[$col];
    				$i++;
    			}else{
    				continue;
    			}
    		}
    	}else{
    		return false;
    	}
    	array_multisort($arr,$type,$data);
    	return $data;
    }

    //上传图片
    public function actionFilephoto(){
        if(!isset($_FILES)){
            return json_encode(['false','未检测到有图片信息']);
        }
        //获取图片信息
        $file = $_FILES['myFile'];
        //获取图片后缀名
        $suffix = substr(strrchr($file['name'], '.'), 1);
        $new_name = uniqid().round('1000,9999').'.'.$suffix;
        $imgdir = yii::$app->basePath . "/web/media/images/sgame/";
        if (! is_dir ( $imgdir )) {
            mkdir ( $imgdir, 0777 );
        }
        $res = move_uploaded_file($file["tmp_name"],$imgdir.$new_name);
        if($res){
            return json_encode(['true',$new_name]);
        }else{
            return json_encode(['false','上传图片失败']);
        }
    }

    
}