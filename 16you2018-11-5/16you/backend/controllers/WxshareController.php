<?php

namespace backend\controllers;

use Yii;
use common\models\Wxshare;
use common\redismodel\WxshareRedis;
use common\models\Game;
use backend\controllers\BaseController;
use yii\data\Pagination; 
use common\common\Helper;

/**
 * 微信分享类
 */
class WxshareController extends BaseController{
      /**
     * 进入礼包记录页.
     * @return mixed
     */
    public function actionIndex() { 
        //分页
        $curPage = Yii:: $app->request->get( 'page',1);
        $pageSize = yii::$app->params['pagenum'];
        //搜索
        $value = Helper::filtdata(Yii:: $app->request->get('keyword',''));
        $gid = Helper::filtdata(Yii:: $app->request->get('gid',''));
        $gname = Helper::filtdata(Yii:: $app->request->get( 'gname'));
        $search = ($value)?['like','title',$value]: '';
        $query = (new \yii\db\Query())
        ->select('gamename,id,title,desc,createtime')
        ->from('g_wxshare')
        ->orderBy('createtime desc');
        $gid && $query = $query->where(['like','gid',$gid]);
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        $data['data'] =  ($data['data'])?$data['data']->all():'';
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        //菜单定位
        $game = Game::find()->select("name,id")->limit('1000')->all();
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/wxshare/index.html';
        return $this->render('index', [
            'data' => $data, 
            'pages' => $pages,
            'value' => $value,
        	'gid' => $gid,
        	'gname'=>$gname,
            'game' => $game,
        ]);
    } 
    

    /**
     * 进入添加礼包页面
     * @return [type] [description]
     */
    public function actionToadd(){
        $game =  Game::find()->limit(1000)->orderBy('sort desc')->asArray()->all();//游戏
        return $this->render('add',[
            'game'=>$game,
        ]);
    }

    /**
     * 接收添加数据
     */
    public function actionCreate(){
        if(!yii::$app->request->isAjax){
            return json_encode([
                'info'=>'数据错误,请稍后再试',
                'errorcode'=>1000,
            ]);
        }
        $share = new Wxshare();
        if(isset($_POST['id'])){
            $id = Helper::filtdata(yii::$app->request->post('id','INT'));
            $share = $share->find()->where(['id'=>$id])->one();
        }
        $game = explode('%@!',Helper::filtdata($_POST['gid']));
        $share->gid = $game['0'];
        $share->gamename = $game['1'];
        $share->title = Helper::filtdata($_POST['title']);
        $share->desc = Helper::filtdata($_POST['desc']);
        $share->link = Helper::filtdata($_POST['link']);
        $share->createtime = time();
        $info = isset($id)?'编辑':'添加';
        if($share->save()){
            $redis = new WxshareRedis();
            $redis1 = $redis->find()->where(['id'=>$share->id])->one();
            if($redis1){
                $redis = $redis1;
            }else{
                $redis->id = $share->id;
            }
            $redis->gid = $share->gid;
            $redis->gamename = $share->gamename;
            $redis->title = $share->title;
            $redis->desc = $share->desc;
            $redis->link = $share->link;
            $redis->createtime = $share->createtime;
            $redis->save();
            return json_encode([
                'info'=>$info.'成功',
                'errorcode'=>0
            ]);
        }else{
            return json_encode([
                'info'=>$info.'失败',
                'errorcode'=>1001
            ]);
        }
    }

    /**
     * 加载编辑页面
     */
    public function actionToedit($id){
        $model = Wxshare::findOne($id);
        if($model){
            $game =  Game::find()->limit(100)->orderBy('sort desc')->asArray()->all();//游戏
        }else{
            $game = '';
        }
        return $this->render( 'edit', [
            'model' => $model,
            'game'=>$game,
        ]);
    }

    /**
     * 删除
     * @param integer $id
     * @return mixed
     */
    public function actionDel()
    {
        $id = Helper::filtdata(Yii::$app->request->post('id'),'INT');
        $res = Wxshare::deleteAll(['id'=>$id]);
        if($res){
            WxshareRedis::deleteAll(['id'=>$id]);
            return json_encode([
                'errorcode'=>0,
                'info'=>'删除成功',
            ]);
        }else{
            return json_encode([
                'errorcode'=>1011,
                'info'=>'删除失败',
            ]);
        }
    }
}