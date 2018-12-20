<?php 
namespace frontend\controllers;

use yii;
use frontend\controllers\BaseController;
use common\models\Game;
use common\common\Helper;
use common\redismodel\GameRedis;

/**
 * 游戏首页 分类
 */
class CategoryController extends BaseController{
    //首页
    public function actionIndex() { 
        $cur = yii::$app->request->get('page',1);
        $cate = Helper::filtdata(yii::$app->request->get('puid',0));//类型
        $pageSize = 100;
        $model = Game::find()->where(['state'=>1,'game_type'=>$cate]);
        $game = $model->offset(($cur-1)*$pageSize)->limit($pageSize)->orderBy('sort desc')->asArray()->all();//热门游戏分类
        $_model = clone $model;
        $count = $_model->count();
        $cur_p = $cur*$pageSize;
        $count = ($cur_p<$count)?1:0;//判断是否有数据
        if(yii::$app->request->isAjax){
            foreach ($game as $k => $v) {
                $v['label'] = $v['label']?json_decode($v['label']):'';
                $game[$k] = $v;
            }
            return json_encode(['info'=>$game,'errorcode'=>$count]);
        }
        switch ($cate) {
            case '0':
                $cate_name = '驰骋沙场';
                break;
            case '1':
                $cate_name = '交换人生';
                break;
            case '2':
                $cate_name = '商场老将';
                break;
            case '3':
                $cate_name = '棋逢对手';
                break;
            default:
                $cate_name = '';
                break;
        }
        return $this->render("index",[
            'game'=>$game,
            'cate'=>$cate,
            'count'=>$count,
            'cate_name'=>$cate_name,
        ]);
    } 

    /**
     * 休闲游戏
     */
    public function actionLeisure(){
        if(yii::$app->cache['Casualgame']){
            $game = yii::$app->cache['Casualgame'];
        }else{
            $game = Game::find()->where(['state'=>1,'type'=>2])->orderBy('sort desc')->asArray()->all();//休闲游戏
            yii::$app->cache['Casualgame'] = $game;
        }
        yii::$app->session['typemenu'] = 7;
        return $this->render("index",[
                'game'=>$game,
        ]);
    }
    
    
    /**
     * 详情页
     */
    public function actionDetail() {
        if(isset($_GET['id'])){
            $gid = Helper::filtdata($_GET['id'],'INT');
            if(!$gid){
                $this->redirect('/index/index.html');
            }
            $game = GameRedis::findOne(['id'=>$gid]);
            if(!$game){
                $game = Game::findOne(['id'=>$gid]);
                if(!$game){ //游戏不存在
                    return $this->redirect('/index/index.html');
                }
                $this->savegameredis($game);//游戏记录保存到redis
            }
            $hotgame = array_slice(yii::$app->cache->get('hotgame'),0,8);//热门游戏
            return $this->renderPartial("detail",[
                    'game'=>$game,
                    'hotgame'=>$hotgame,
            ]);
        }else{
            $this->redirect('/index/index.html');
        }
    }     
    
    /*
     * 保存游戏记录到redis
    */
    private function savegameredis($game){
        $gameredis = new GameRedis();
        $gameredis->name = $game->name;
        $gameredis->cid = $game->cid;
        $gameredis->descript = $game->descript;
        $gameredis->unique= $game->unique;
        $gameredis->state = $game->state;
        $gameredis->label = $game->label;
        $gameredis->intro = $game->intro;
        $gameredis->game_url =$game->game_url;
        $gameredis->type = $game->type;
        $gameredis->sort =$game->sort;
        $gameredis->image = $game->image;
        $gameredis->head_img = $game->head_img;
        $gameredis->game_type = $game->game_type;
        $gameredis->createtime = $game->createtime;
        $gameredis->save();
    }

    /**
     * 最近在玩页
     */
    public function actionNearplay() { 
        return $this->render("nearplay"); 
    }  
}