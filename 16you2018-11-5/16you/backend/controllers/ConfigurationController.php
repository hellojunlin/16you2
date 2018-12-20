<?php 
namespace backend\controllers;

use yii;
use backend\controllers\BaseController;
use common\common\Helper;
use common\models\Configuration;
use common\models\Game;
use yii\data\Pagination;

class ConfigurationController extends BaseController{

    /*进入配置页面*/
    public function actionIndex(){
        //分页
        $curPage = Helper::filtdata(Yii:: $app->request->get( 'page',1));
        $keyword = Helper::filtdata(Yii:: $app->request->get( 'keyword'));
        $value = Helper::filtdata(Yii:: $app->request->get( 'value'));
        $gid = Helper::filtdata(Yii:: $app->request->get('gid',''));
        $gname = Helper::filtdata(Yii:: $app->request->get( 'gname'));
        $selected = $value?['like',$keyword,$value]:'';
        $pageSize = yii::$app->params['pagenum'];
        $query = (new \yii\db\Query())
                ->select('gc.id,gg.name,gc.key,gc.type_url,gc.partnerid,gc.api_url,gc.createtime')
                ->from('g_configuration AS gc') 
                ->leftJoin('g_game AS gg','gg.id = gc.gid')
                ->orderBy('gc.createtime desc');
        $gid && $query->andWhere(['gg.id'=>$gid]);
        $data = Helper::getPages($query,$curPage,$pageSize,$selected); 
        $data['data'] =  ($data['data'])?$data['data']->all():'';
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/configuration/index.html';
        $game = Game::find()->orderBy('id desc')->limit(100)->asArray()->ALL();
        return $this->render('index', [
             'data' => $data,
             'pages' => $pages,
             'game' => $game,
             'gid' => $gid,
        	 'gname'=>$gname,
             'keyword' => $keyword,
             'value' => $value,
        ]);
    } 

    /**
     * 跳转添加页面
     * @return Ambigous <string, string>
     */
    public function actionAdd(){
        $game = Game::find()->select(['id','name'])->orderBy('id desc')->asArray()->all();
        return $this->render('add',['game'=>$game]);
    }

    /*进入编辑页面*/
    public function actionEdit(){
        $id = Helper::filtdata(yii::$app->request->get('id',''),'INT');
        $Config = Configuration::find()->WHERE(['id'=>$id])->one();
        $game = Game::find()->WHERE(['id'=>$Config->gid])->select(['name'])->one();
        return $this->render('edit',['model'=>$Config,'game'=>$game]);
    }

    /**
       * 添加、编辑
       */
    public function actionCreate(){
        if(isset($_POST['id'])){//编辑
          $id = Helper::filtdata($_POST['id']);
          if(!$id) echo '数据错误，请稍后再试';

          $model = Configuration::findOne($id);
        }else{//保存
            $model = new Configuration();
            $model->key = md5(rand(10000,99999).uniqid());
            $model->partnerid = md5(rand(10000,99999).uniqid().rand(10000,99999));
        }
        $model->gid = Helper::filtdata($_POST['gid'],'INT');
        $model->type_url = Helper::filtdata($_POST['type_url']);
        $model->api_url = Helper::filtdata($_POST['api_url']);
        $model->createtime = time();
        if($model->save()){
            return $this->redirect('index.html');
        }
    }

    public function actionDelete(){
        if(isset($_POST['id'])){
            $id = Helper::filtdata(Yii::$app->request->post('id'),'INT');
            if(!$id){
                return 0;
            }
            $id = Helper::filtdata(Yii::$app->request->post('id'),'INT');
            if($id){
                $res = Configuration::deleteALL(['id'=>$id]);
                if($res){
                    return 1;//'删除成功！';
                }else{
                    return 0;//'删除失败！';
                }
            }
        }
    }
}