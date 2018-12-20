<?php

namespace backend\controllers;

use Yii;
use backend\controllers\BaseController;
use yii\data\Pagination;
use common\common\Helper;
use common\models\Carousel;

/**
 * 首页轮播类
 */
class CarouselController extends BaseController{
    /**
     * 进入轮播记录页.
     * @return mixed
     */
    public function actionIndex(){
        //分页
        $model = new Carousel();
        //分页
        $curPage = Yii:: $app->request->get( 'page',1);
        $pageSize = yii::$app->params['pagenum'];
        $value = Helper::filtdata(Yii:: $app->request->get('value',''));    
        $search = ($value)?['like','url',$value]: '';
        //查询语句
        $query = $model->find()->orderBy('sort desc');
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        $data['data'] = ($data['data'])?$data['data']->asArray()->all():'';
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        //菜单定位
        unset(yii::$app->session['localsecondurl']);
        yii::$app->session['localfirsturl'] = yii::$app->params['backend'].'/carousel/index.html';
        return $this->render('index', [
             'data' => $data,
             'pages' => $pages,
             'value' => $value,
        ]);
    }

    /**
     * 进入添加轮播页面
     * @return [type] [description]
     */
    public function actionAdd(){
      return $this->render('add');
    }

    /**
     * 接收数据
     */
    public function actionCreate()
    {

        $model = new Carousel();
        $app = Yii::$app->request;
        $newname = '';
        if(isset($_POST['id'])){
            $id = Helper::filtdata($app->post('id'));
            $model = $model->findOne($id);
            $newname = Helper::filtdata($app->post('oldimage'));
        }
        $res = true;
        if(isset($_FILES['image']) && $_FILES['image']['name']){
            //上传视频
            $files = $_FILES['image'];
            $new_path = dirname(dirname(__FILE__)).'/web/media/images/carousel/';//文件路劲
            $suffix = trim(strrchr($files['name'],'.'),'.');
            if(!is_dir($new_path)){
              mkdir($new_path,0777,true);
            } 
            $newname = uniqid().rand(100,999).'.'.$suffix;
            $path_suffix = $new_path.'/'.$newname;
            $res = move_uploaded_file($files['tmp_name'],$path_suffix);
        }
        if($res){//上传文件
          $model->image = $newname;
          $model->url = Helper::filtdata($app->post('url'));
          $model->state = Helper::filtdata($app->post('state'),'INT');
          $model->sort = Helper::filtdata($app->post('sort'),'INT');
          $model->remark = Helper::filtdata($app->post('remark'));
          $model->createtime = time();
          if($model->save()){
              $carousel = Carousel::find()->limit(5)->where(['state'=>$model->state])->orderBy('sort desc')->asArray()->all(); 
              if($model->state==1){//首页
                 yii::$app->cache->set('carousel',$carousel);
              }elseif($model->state==2){//商城
                 yii::$app->cache->set('product',$carousel); 
              }
              $info = (isset($_POST['id']))?'编辑成功':'添加成功';
              return json_encode(['true',$info]);
          }else{
              $info = (isset($_POST['id']))?'编辑失败':'添加失败';
              return json_encode(['false',$info]);
          }
        }else{
          return json_encode(['false','图片上传失败']);
        }
    }

    /**
     * 加载编辑页面
     */
    public function actionEdit($id){
        $model = $this->findModel($id);
        return $this->render( 'edit', [
            'model' => $model
        ]);
    }

    /**
     * 删除轮播
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        $id = Helper::filtdata(Yii::$app->request->post('id'),'INT');
        $res = $this->findModel($id);
        if($res){
          @unlink(dirname(dirname(__FILE__)).'/web/media/images/carousel/'.$res->image);
          $res->delete();
          $carousel = Carousel::find()->limit(4)->where(['state'=>$res->state])->orderBy('sort desc')->asArray()->all();
          if($res->state==1){//首页
             yii::$app->cache->set('carousel',$carousel);
          }elseif($res->state==2){//商城
             yii::$app->cache->set('product',$carousel); 
          }
          return 1;//删除成功
        }else{
          return 0;//删除失败
        }
    }

    /**
     * Finds the Manage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Manage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Carousel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 异步上传图片
     */
    public function actionSubimg(){
        if(Yii::$app->request->isAjax){
          $imgb = $_POST ['imgbase64'];
          $img = array ();
          $imgdir = dirname(dirname(__FILE__)).'/web/media/images/carousel/';
          $image = Helper::imgurl($imgb, $imgdir);
          if ($image) {
            return json_encode ( [
                'info' => '图片截取成功',
                'errorcode' => '0',
                'imgurl'=>$image
            ] );
          } else {
            return json_encode ( [
                'info' => '您好，图片异常，请更换其它图片',
                'errorcode' => '1001'
            ] );
            exit;
          }
        }
    }
}
