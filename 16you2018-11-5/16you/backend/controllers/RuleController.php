<?php 
namespace backend\controllers;

use yii;
use backend\controllers\BaseController;
use common\models\Rule;
use common\common\Helper;

class RuleController extends BaseController{

    //规则首页
    public function actionIndex() { 
        //分页
        $curPage = Helper::filtdata(Yii:: $app->request->get( 'page',1));
        //搜索
        $value = Helper::filtdata(Yii:: $app->request->get('value',''));    
        $where = ($value)?['like','content',$value]: '';
        $data = Rule::find()->where($where)->orderBy('createtime desc')->asArray()->ALL();
        if($data){
            $rule = yii::$app->params['rule'];
            foreach ($data as $key => $val) { 
                foreach ($rule as $k => $v) { 
                    if($val['type']==$k){
                        $data[$key]['type'] = $v; 
                    }
                }
            }
        }
        //菜单定位
        unset(yii::$app->session['localsecondurl']);
        yii::$app->session['localfirsturl'] = yii::$app->params['backend'].'/rule/index.html';
        return $this->render('index', [
             'data' => $data,
             'value' => $value,
        ]);
    }  

    //添加
    public function actionToadd(){
        $rule = yii::$app->params['rule'];
        return $this->render('add',['rule'=>$rule]);
    }

    //编辑
    public function actionToedit(){
        if(!isset($_GET['id'])){
            exit;
        }
        $id = Helper::filtdata($_GET['id'],'INT');
        $rule = yii::$app->params['rule'];
        $model = Rule::findOne($id);
        return $this->render('edit',[
            'model'=>$model,
            'rule'=>$rule,
        ]);
    }

    public function actionCreate(){
        if(!isset($_POST['content'])){
            return $this->render('add');
        }
        $model = new Rule();
        $app = Yii::$app->request;
        if(isset($_POST['id'])){//编辑
          $id = Helper::filtdata($_POST['id'],'INT');
          $model = $model->find()->where(['id'=>$id])->one();
        }else{
            $model->createtime = time()+3600*8;
        }
        $starttime = $app->post('starttime');
        $endtime = $app->post('endtime');
        $model->starttime = $starttime?strtotime($starttime):'';
        $model->endtime = $endtime?strtotime($endtime):'';
        $model->content = trim($app->post('content'));
        $model->type = Helper::filtdata($app->post('type'));
        $model->state = Helper::filtdata($app->post('state'));
        if($model->save()){
            if($model->type==1){
                $rule = Rule::find()->where(['state'=>1,'type'=>1])->orderBy('endtime asc')->asArray()->all();
                yii::$app->cache->set('rule',$rule);
            }
            return $this->redirect('index.html');
        }
    }

    //删除规则
    public function actionDel(){
        if(!yii::$app->request->isAjax||!isset($_POST['id'])){
            return json_encode([
                'errorcode'=>'1001',
                'info'=>'数据错误,请稍后再试',
            ]);
        }

        $id = Helper::filtdata($_POST['id'],'INT');
        if($id){
            $rule = Rule::findOne($id);
            if($rule){
                if($rule->delete()){
                    if($rule->type==1){
                        $rule = Rule::find()->where(['state'=>1,'type'=>1])->orderBy('endtime asc')->asArray()->all();
                        yii::$app->cache->set('rule',$rule);
                    }
                    return json_encode([
                        'errorcode'=>'0',
                        'info'=>'删除成功',
                    ]);
                }else{
                    return json_encode([
                        'errorcode'=>'1002',
                        'info'=>'删除失败',
                    ]);
                }
            }else{
                return json_encode([
                    'errorcode'=>'1003',
                    'info'=>'找不到该数据',
                ]);
            }
        }else{
            return json_encode([
                'errorcode'=>'1004',
                'info'=>'网络错误',
            ]);
        }
    }
}