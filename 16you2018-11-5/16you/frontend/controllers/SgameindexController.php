<?php 
namespace frontend\controllers;

use yii;
use frontend\controllers\BaseController;
use common\common\Helper;
use common\redismodel\GameRedis;
use common\redismodel\SgameRedis;
use common\models\Sgame;
class SgameindexController extends BaseController{
	//首页
    public function actionIndex() { 
    	$limitnum = yii::$app->params['limitnum'];
        //获取游戏信息
        if(yii::$app->cache->get('sgamearr')){//不存在热门游戏或者游戏的缓存时则从数据库读取
        	$sgamearr = array_slice(yii::$app->cache->get('sgamearr'),0,50);
        }else{
        	$this->getGame();
        	$sgamearr = array_slice(yii::$app->cache->get('sgamearr'),0,50);
        }
        $allgame =  yii::$app->cache->get('sgamearr');
        $user = yii::$app->session['user'];
        yii::$app->session['typemenu'] = 6;
        $time1 = strtotime(date("Y-m-d"));
        $time2 = strtotime("2017-9-25");
        $days = round(($time1-$time2)/3600/24);
        return $this->render("smallgame",[   
        		'user'=>$user, 
        		'sgamearr'=>$sgamearr,
        		'allgame'=>$allgame,
        		'days'=>$days,
        ]);
    } 
    
    //首页
    public function actionIndex2() {
    	$limitnum = yii::$app->params['limitnum'];
    	//获取游戏信息
    	if(yii::$app->cache->get('sgamearr')){//不存在热门游戏或者游戏的缓存时则从数据库读取
    		$sgamearr = array_slice(yii::$app->cache->get('sgamearr'),0,50);
    	}else{
    		$this->getGame();
    		$sgamearr = array_slice(yii::$app->cache->get('sgamearr'),0,50);
    	}
    	$allgame =  yii::$app->cache->get('sgamearr');
    	$user = yii::$app->session['user'];
    	yii::$app->session['typemenu'] = 6;
    	return $this->render("smallgame2",[
    			'user'=>$user,
    			'sgamearr'=>$sgamearr,
    			'allgame'=>$allgame,
    			]);
    }
    
    /**
     * 获取热门游戏和其他游戏
     */
    private function getGame(){
    	$sgamearr = SgameRedis::find()->where(['state'=>1])->asArray()->all();
    	$sgamearr && $sgamearr = Helper::quick_sort($sgamearr,'sort');//按某个字段排序
    	if(!$sgamearr){//redis不存在时，则去数据库查
    		$sgamearr = Sgame::find()->where(['state'=>1])->orderBy('sort desc')->asArray()->all();//游戏
    		if($sgamearr){//存在保存数据库
    			$this->savegameredis($sgamearr,2);
    		}
    	}
    	yii::$app->cache->set('sgamearr',$sgamearr);
    	$resarra = array();
    	$resarra['sgamearr'] = $sgamearr;
    	return $resarra;
    }
    
    /*
     * 保存游戏记录到redis
    */
    private function savegameredis($sgame,$type=1){
    	if($type==1){
    		$gameredis = new GameRedis();
    		$gameredis->name = $sgame->name;
    		$gameredis->descript = $sgame->descript;
    		$gameredis->unique= $sgame->unique;
    		$gameredis->state = $sgame->state;
    		$gameredis->game_url =$sgame->game_url;
    		$gameredis->sort =$sgame->sort;
    		$gameredis->head_img = $sgame->head_img;
    		$gameredis->createtime = $sgame->createtime;
    		$gameredis->save();
    	}else if($type==2){
    		foreach ($sgame as $g){
    			$gameredis = new GameRedis();
    			$gameredis->name = $g['name'];
    			$gameredis->descript = $g['descript'];
    			$gameredis->unique= $g['unique'];
    			$gameredis->state = $g['state'];
    			$gameredis->game_url = $g['game_url'];
    			$gameredis->sort =$g['sort'];
    			$gameredis->head_img = $g['head_img'];
    			$gameredis->createtime = $g['createtime'];
    			$gameredis->save();
    		}
    	}
    }
    
    /**
     * 小游戏开始页面
     */
    public function actionSgamestart(){
    	echo 123;
    }
}