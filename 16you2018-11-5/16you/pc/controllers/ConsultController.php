<?php
namespace pc\controllers;

use yii;
use pc\controllers\BaseController;
use common\models\Consult;
use common\common\Helper; 
use common\redismodel\ConsultRedis;
use common\redismodel\ConsultviewRedis;
use common\models\Game;
use yii\base\Object;
use common\redismodel\UserRedis;
use common\models\User;

/**
* 咨询
* @author He
*/
class ConsultController extends BaseController
{
	/**
     * 异步加载咨询
     * 从mysql查询数据
     * @return [type] [description]
     */
    public function actionGetconsult(){
    	if(!yii::$app->request->isAjax||!isset($_POST['page'])||!isset($_POST['consult_boolean'])){
    		return json_encode([
    				'errorcode'=>1000,
    				'info'=>'数据错误，请稍后再试',
    		]);
    	}
    	$cur = Helper::filtdata(yii::$app->request->post('page',1));
    	$consult_boolean = Helper::filtdata(yii::$app->request->post('consult_boolean',1));;
    	$pageSize = 20;
        $time = time();
        $consult = Consult::find()->where(['state'=>1])->andWhere(['<=','starttime',$time])->orderBy('type desc,sort desc,createtime desc')->offset(($cur-1)*$pageSize)->limit($pageSize)->asArray()->all();//从mysql查出咨讯
        if(!$consult){//判断是否有数据
            $info =($cur>1)? '已加载所有资讯':'暂时没有资讯';
            return json_encode([
                    'errorcode'=>1002,
                    'info'=>$info,
            ]);
        }
        foreach ($consult as $k=>$v) {//时间格式化
            if(isset($v['cid'])){
                $consult[$k]['id'] = $v['cid'];
            }
            $consult[$k]['createtime'] = date('m-d',$v['createtime']); 
        }
        if($cur==1 && $consult_boolean==1){//未看过资讯，需要更新consult_id字段为2
	        $user = \Yii::$app->session->get('user');
	        $consult_id = Consult::find()->select('max(id) as id')->one();  //资讯最大的id
	        $user && $userinfo = User::findOne(['id'=>$user->id]);
	        if(isset($userinfo) && $userinfo){//更新用户表consult_id字段
	        	$userinfo->consult_id = isset($consult_id->id)? $consult_id->id:'';
	        	$userinfo->save() && \Yii::$app->session->set('user',$userinfo);
	        	// $userredis = UserRedis::findOne(['id'=>$user->id]);
	        	// if($userredis){//保存到redis
	        	// 	$userredis->consult_id = $consult_id->id;
	        	// 	$userredis->save();
	        	// }
	        }
        }
        return json_encode([
                'errorcode'=>0,
                'info'=>$consult
        ]);
    }

    //资讯详情，从redis先查询数据，如果没有寻到该数据，则从数据查询
    public function actionDetail(){
        if(!isset($_GET['id'])){
            return $this->redirect('/game/list.html');
        }
        $id = Helper::filtdata($_GET['id'],'INT');
        if($id){
            // $model = ConsultRedis::findOne(['cid'=>$id]);
            // if(!$model){//redis没有数据
                $model = Consult::findOne($id);//从mysql查出咨讯
                if(!$model){
                	$this->redirect('/common/error.html');
                }else{
                	$game = Game::find()->where(['id'=>$model->gid])->select('name')->one();
           //          $game_name = $game?$game->name:'';
           //      	$_model = new ConsultviewRedis();
           //      	$_model->cid = $model->id;
          	// 		$_model->gid = $model->gid;
          	// 		$_model->game_name = $game_name;
		        	// $_model->title = $model->title;
		        	// $_model->label = $model->label;
		        	// $_model->content = $model->content;
		        	// $_model->state = $model->state;
		        	// $_model->createtime = $model->createtime;
		        	// $_model->save();
                }
            // }else{
            //     $model->id = $model->cid;//资讯的数据库id
            // }
            $redis = Consult::find()->where(['!=','id',$id])->limit(3)->orderBy('createtime desc')->asArray()->all();//其他资讯，除了本条的最新三条
            if(yii::$app->cache->get('hotgame')){
                $hotgame = array_slice(yii::$app->cache->get('hotgame'),0,8);//热门游戏
            }else{
                $hotgame = array();
            }
            $game = Game::find()->where(['!=','state',1])->orderBy('sort desc')->one();//底部一个游戏
            return $this->renderPartial('detail',[
                'model'=>$model,
                'redis'=>$redis,
                'hotgame'=>$hotgame,
                'game'=>$game,
            ]);
        }else{
            return $this->redirect('/game/list.html'); 
        }
    }
}