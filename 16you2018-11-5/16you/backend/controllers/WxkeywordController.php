<?php
namespace backend\controllers;

use Yii;
use backend\controllers\BaseController;
use common\common\Helper;
use yii\data\Pagination;
use common\models\Wxkeyword;
use common\common\Wxinutil;
use frontend\controllers\ResponseController;

/**
 * 微信回复关键字类
 * Wxkeyword controller
 */
class WxkeywordController extends BaseController
{

    /**
     * 关键字管理
     *
     * @return string
     */
    public function actionIndex(){
        $model = new Wxkeyword();
        if(!isset($_SESSION['rev'])){
          yii::$app->session['rev'] = yii::$app->params['replyVideo'];
        }
        $replyvideo = yii::$app->session['rev'];
        //分页
        $curPage = Yii:: $app->request->get( 'page',1);
        $pageSize = yii::$app->params['pagenum'];
        //搜索
        $value1 = Helper::filtdata(Yii:: $app->request->get('value',''));
        $search = ($value1)?['like','keyword',$value1]: '';
        $query = $model->find()->orderBy('createtime desc');
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        if($data['data']){
          $data['data'] = $data['data']->asArray()->all();
          foreach ($data['data'] as $key => $val) {
              foreach ($replyvideo as $k => $v) {
                  if($v['appid']==$val['wxappid']){
                      $data['data'][$key]['wxname'] = $v['name'];
                  }
              }
              $arr = '';
              if($val['type']==1){
                $data['data'][$key]['content'] = json_decode($val['content']);
              }elseif($val['type']==2){//判断是否是图文
                $content = json_decode($val['content']);
                foreach ($content as $ke => $value) {
                  $arr[] = $value->title;
                }
                $data['data'][$key]['content'] = implode($arr,'、');
              }elseif($val['type']==4){
                $content = json_decode($val['content']);
                $data['data'][$key]['content'] = '视频标题：'.$content->vtitle.';  简介：'.substr($content->vintroduction,0,50);
              }
          }
        }
        $pages = new Pagination(['totalCount' =>$data[ 'count'],'pageSize'=>$pageSize]);
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/wxkeyword/index.html';
        return $this->render('index', [
           'data' => $data,
           'pages' => $pages,
           'value'=> $value1,
        ]);
    }

    /**
     * 进入添加活动页面
     * @return [type] [description]
     */
    public function actionAdd(){
        if(!isset($_SESSION['rev'])){
            yii::$app->session['rev'] = yii::$app->params['replyVideo'];
        }
        $replyvideo = yii::$app->session['rev'];
        return $this->render('add',['model'=>$replyvideo]);
    }

    /*进入编辑页面*/
    public function actionEdit(){
      if(!isset($_GET['id'])){
        return $this->redirect('index.html');
        exit;
      }
      $id = Helper::filtdata(yii::$app->request->get('id'));
      $res = $this->findModel($id);
      if(!isset($_SESSION['rev'])){
        yii::$app->session['rev'] = yii::$app->params['replyVideo'];
      }
      $res1 = yii::$app->session['rev'];
      foreach($res1 as $v){
        if($v['appid']==$res->wxappid){
          $res->wxappid = $v['name'];
        }
      }
      if($res){
        $count = 0;
        if($res->type==1){
          $res->content = json_decode($res->content);
        }elseif($res->type==2){
          $res->content = json_decode($res->content);
          $count = count($res->content); 
        }elseif($res->type==4){
          $res->content = json_decode($res->content);
        }
        return $this->render('edit',['model'=>$res,'count'=>$count]);
      }
    }

    /*接收数据、保存*/
    public function actionCreate(){
      if(!isset($_POST['type'])){
        return $this->redirect('/wxkeyword/index.html');
      }
      $app = yii::$app->request;
      $model = new Wxkeyword();
      if(isset($_POST['id'])){
        $id = Helper::filtdata($app->post('id',''),'INT');
        $model = $model->findOne($id);
       $model->content = json_decode($model->content);
      }else{
        $model->wxappid = Helper::filtdata($app->post('wxappid',''));
      }
      $type = Helper::filtdata($app->post('type',''),'INT');
      $model->type = $type;
      $model->keyword = Helper::filtdata($app->post('keyword',''));
      $model->sort = Helper::filtdata($app->post('sort'));
      switch ($type) {
  			case '1'://文本
  				$model->content = json_encode(trim($app->post('content','')));
  				break;
  			case '2'://图文
  				$arr = array();
  				$title = $app->post('title','');
  				$description = $app->post('description','');
  				$url = $app->post('url','');
  				$path = dirname(dirname(__FILE__)).'/web/media/wxin/news/';
  				foreach ($_FILES['file']['name'] as $k => $v) {
  					if($v){
  						if(!is_dir($path)) mkdir($path,0777,true);
  						$new_name = uniqid().rand(100,999).'.jpeg';
  						$arr[$k]['image'] = yii::$app->params['backend'].'/media/wxin/news/'.$new_name;
  						move_uploaded_file($_FILES['file']['tmp_name'][$k],$path.$new_name);//上传文件
  						($model->content)&& @unlink($path.json_decode($model->content)[$k]->image);
  					}else{
  						if(!$model->content){
  							$arr[$k]['image'] = '';
  						}else{
  							$arr[$k]['image'] = json_decode($model->content)[$k]->image;
  						}
  					}
  					$arr[$k]['title'] = $title[$k];
  					$arr[$k]['description'] = $description[$k];
  					$arr[$k]['url'] = $url[$k];
  				}
  				$arr&&($arr = json_encode($arr));
  				$model->content = $arr;
  				break;
  			case '3'://图片
  				if($_FILES['files']['name']){
  					$path = dirname(dirname(__FILE__)).'/web/media/wxin/image/';
  					$imagesdir = Helper::upload('files','jpeg',$path); 
  					$wxinutil = new Wxinutil(); 
  					$model->content = $wxinutil->toImages($model->wxappid,'/media/wxin/image/'.$imagesdir);//获取media_id
  					@unlink($path.$model->filename);
  					$model->filename = '/media/wxin/image/'.$imagesdir;
  				}
  				break;
  			case '4'://视频 
  				$vfile = Helper::filtdata($app->post('vefile'));
  	            $vtitle = Helper::filtdata($app->post('vtitle'));//标题
  	            $vintroduction = Helper::filtdata($app->post('vintroduction'));//简介
  	            if($model->content){
  	            	$media = json_decode($model->content)->media_id;
  	            }
  	            if(!isset($media)){
  	                $media = Wxinutil::toVideo($model->wxappid,$vfile,$vtitle,$vintroduction);
  	                $model->filename = $vfile;
  	            }
  	            $model->content = json_encode(['media_id'=>$media,'vtitle'=>$vtitle,'vintroduction'=>$vintroduction]);
  	            break;
  		}
      $model->createtime = time();
      if($model->save()){
        $this->redirect('index.html');
      }
    }


   /**
     * 判断添加关键字该微信所对应关键字是否存在
     */
    public function actionKeyword(){
        $keyword = Helper::filtdata(Yii::$app->request->post('keyword'));
        $res = Wxkeyword::findOne(['keyword'=>$keyword]);
        if($res){
            return 1;
        }
    }

    /**
     * 删除关键字
     * @param integer $id
     * @return mixed
     */
    public function actionDel()
    {
        if(yii::$app->request->isAjax&&isset($_POST['id'])){
            $id = Helper::filtdata(Yii::$app->request->post('id'),'INT');
            $res = $this->findModel($id)->delete();
            if($res){
                return 1;
            }else{
                return 0;
            }
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
        if (($model = Wxkeyword::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
