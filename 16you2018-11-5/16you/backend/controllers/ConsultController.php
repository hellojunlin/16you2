<?php

namespace backend\controllers;

use Yii;
use common\models\Consult;
use backend\controllers\BaseController;
use yii\data\Pagination;
use common\common\Helper;
use common\redismodel\ConsultRedis;
use common\redismodel\GameRedis;
use common\models\Game;

/**
 * 前台页面类
 */
class ConsultController extends BaseController{
    /**
     * 进入前台页面记录页.
     * @return mixed
     */
    public function actionIndex(){
        $model = new Consult();
        //分页
        $curPage = Helper::filtdata(Yii:: $app->request->get( 'page',1));
        $pageSize = 50;
        //搜索
        $value = Helper::filtdata(Yii:: $app->request->get('value',''));    
        $search = ($value)?['like','title',$value]: '';
        $query = (new \yii\db\Query())
                ->select('C.label,G.name,C.content,C.createtime,C.state,C.id,C.title,C.type,C.sort')
                ->from('g_consult AS C') 
                ->leftJoin('g_game AS G','G.id = C.gid')
                ->orderBy('C.createtime desc');
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        $data['data'] && $data['data'] = $data['data']->all();
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/consult/index.html';
        return $this->render('index', [
             'data' => $data,
             'pages' => $pages,
             'value' => $value,
        ]);
    }

    /**
     * 添加咨询页面
     */
    public function actionAdd() {
      /*   $game = GameRedis::find()->limit(10000)->asArray()->All();
        if(!$game){ */
          $game = Game::find()->select('id,name')->orderBy('id desc')->limit(10000)->asArray()->ALL();
       /*  } */
        return $this->render("add",[
            'game'=>$game,
        ]);
    }

    /**
     * 加载编辑页面
     */
    public function actionEdit($id){
        $model = $this->findModel($id);
        /* $game = GameRedis::find()->limit(10000)->asArray()->All();
        if(!$game){ */
          $game = Game::find()->select('id,name')->orderBy('id desc')->limit(10000)->asArray()->ALL();
        /* } */
        $model->starttime = $model->starttime?$model->starttime:time();
        return $this->render( 'edit', [
            'model' => $model,
            'game'=>$game,
        ]);
    }

    /**
     * 添加页面数据
     */
    public function actionCreate()
    {
        if(!isset($_POST['title'])||!isset($_POST['content'])||!isset($_POST['label'])||!isset($_POST['state'])){
            return $this->redirect('index.html');
        }
        $app = YII::$app->request;
        $id = Helper::filtdata($app->post('id'),'INT');//接收id
        $model = new Consult();
        $model1 = new ConsultRedis();
        if($id){//有id则是修改数据
            $model = $model->findOne($id);
            $model2 = $model1->findOne(['cid'=>$id]);
            if($model2){
                $model1 = $model2;
            }
        }
        $explode = explode('&%#',$app->post('gid'));
        $model->gid = $explode['0'];
        $model->title = Helper::filtdata($app->post('title'));
        $model->label = Helper::filtdata($app->post('label'));
        $model->content = $app->post('content');
        $model->state = Helper::filtdata($app->post('state'));
        $model->type = Helper::filtdata($app->post('type'));
        $model->starttime = Helper::filtdata(strtotime($app->post('starttime')));
        $model->sort = Helper::filtdata($app->post('sort'));
        $model->createtime = time();
        if($model->save()){
          //添加、修改redis
          $model1->cid = $model->id;
          $model1->gid = $model->gid;
          $model1->game_name = $explode['1'];
          $model1->title = $model->title;
          $model1->label = $model->label;
          $model1->content = $model->content;
          $model1->state = $model->state;
          $model1->createtime = $model->createtime;
          $model1->starttime = $model->starttime;
          $model1->sort = $model->sort;
          $model1->type = $model->type;
          $model1->save();
          return $this->redirect('index.html');
        }
    }

    /**
     * 启用禁用资讯
     * @param integer $id
     * @return mixed
     */
    public function actionState(){
        $id = Helper::filtdata(Yii::$app->request->post('id'),'INT');
        $state = Helper::filtdata(Yii::$app->request->post('state'),'INT');
        $model = $this->findModel($id);//修改mysql数据
        if($state == 0){//判断是禁用还是启用
            $model->state = 1;//启用
        }else{
            $model->state = 0;//禁用
        }
        if($model->save()){
          $redis = ConsultRedis::findOne(['cid'=>$id]);//修改redis数据
          $redis->state = $model->state;
          $redis->save();
            return json_encode([
              'info'=>'修改成功',
              'errorcode'=>0,
            ]);
        }else{
            return json_encode([
              'info'=>'修改失败',
              'errorcode'=>1001,
            ]);
         }
    }
    
    /**
     * 删除资讯
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
      $id = Helper::filtdata(Yii::$app->request->post('id'),'INT');
      $res = $this->findModel($id)->delete();//删除mysql数据
      if($res){
        ConsultRedis::deleteAll(['cid'=>$id]);//删除redis数据
        return 1;//'删除成功！';
      }else{
        return 0;//'删除失败！';
      }
    }


    /**
     * Finds the home model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return home the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Consult::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
