<?php 
namespace backend\controllers;

use yii;
use backend\controllers\BaseController;
use common\models\Product;
use common\common\Helper;
use yii\data\Pagination;

class ProductController extends BaseController{

    //商品首页
    public function actionIndex() { 
        $model = new Product();
        //分页
        $curPage = Yii:: $app->request->get( 'page',1);
		$pageSize =100;
        //搜索
        $value = Helper::filtdata(Yii:: $app->request->get('value',''));    
        $search = ($value)?['like','product_name',$value]: '';
        $query = $model->find()->orderBy('sort desc');
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        $data['data'] =  ($data['data'])?$data['data']->asArray()->all():'';
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        //菜单定位
        unset(yii::$app->session['localsecondurl']);
        yii::$app->session['localfirsturl'] = yii::$app->params['backend'].'/product/index.html';
        return $this->render('index', [
             'data' => $data,
             'pages' => $pages,
             'value' => $value,
        ]);
    } 
    
    /**
     * 商品添加页面
     */
    public function actionToadd() {
        return $this->render("add");
    }
    
    /**
     * 商品编辑页面
     */
    public function actionToedit($id) {
        $model = Product::findOne($id);
        return $this->render("edit",[
            'model'=>$model
        ]);
    }
    
    public function actionCreate(){
        if(!isset($_POST['product_name'])||!isset($_POST['prdouct_details'])){
            return $this->render('add');
        }
        $model = new Product();
        if(isset($_POST['id'])){
            $model = $model->findOne($_POST['id']);
        }else{
            $model->createtime = time();
        }
        $app = Yii::$app->request;
        $model->product_name = Helper::filtdata($app->post('product_name',''));
        $model->prdouct_details = trim($app->post('prdouct_details',''));
        $model->integral = Helper::filtdata($app->post('integral',''));
        $model->number = Helper::filtdata($app->post('number',''));
        $model->state = Helper::filtdata($app->post('state'));
        $model->type = Helper::filtdata($app->post('type'));
        $model->sort =  Helper::filtdata($app->post('sort'));
        if($_FILES['file']['name']){
            $save_dir = dirname(dirname(__FILE__)).'/web/media/images/product/';
            $model->image_url = Helper::upload('file','jpeg',$save_dir);
        }
        if($model->save()){
            return $this->redirect('index.html');
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
            $model = Product::findOne(['id'=>$id]);
            if(!$model){
                return json_encode([
                        'errorcode'=>1001,
                        'info'=>'该商品不存在,请刷新在试',
                        ]);
            }
            $model->state = $state;
            if($model->save()){
                $info = ($state==0)?'禁止成功':'启用成功';
                return json_encode([
                        'errorcode'=>0,
                        'info'=>$info,
                        ]);
            }else{
                return json_encode([
                        'errorcode'=>1001,
                        'info'=>'该商品不存在,请刷新在试',
                        ]);
            }
        }
    }
    
    /**
     * 判断添加时的商品是否存在
     */
    public function actionProductname(){
        $product_name = Helper::filtdata(Yii::$app->request->post('product_name'),'STRING');
        $res = Product::findOne(['product_name'=>$product_name]);
        if($res)
            return 1;
        else
            return 0;
    }  

    /**
     * 删除商品
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        $id = Helper::filtdata(Yii::$app->request->post('id'),'INT');
        if($id){
            $model = new Product();
            $res = $model->find()->where(['id'=>$id])->One();
            if($res->delete()){
                @unlink(dirname(dirname(__FILE__)).'/web/media/images/product/'.$res->image_url);
                return 1;//'删除成功！';
            }else{
                return 0;//'删除失败！';
            }
        }
    }
}