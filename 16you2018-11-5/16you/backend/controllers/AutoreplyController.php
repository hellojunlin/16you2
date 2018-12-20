<?php
namespace backend\controllers;

use yii;
use backend\controllers\BaseController;
use common\models\Autoreply;
use backend\controllers\ResponseController;
use common\common\Helper;
use common\common\Wxinutil;
use yii\data\Pagination;

/**
 * @author He
 */

class AutoreplyController extends BaseController
{	
	/*进入列表*/
	public function actionIndex(){
		$model = new Autoreply();
		if(!isset($_SESSION['rev'])){
            yii::$app->session['rev'] = yii::$app->params['replyVideo'];
        }
        $replyvideo = yii::$app->session['rev'];
		//分页
		$curPage = Yii:: $app->request->get( 'page',1);
		$pageSize = yii::$app->params['pagenum'];
		//搜索
		$value1 = Helper::filtdata(Yii:: $app->request->get('value',''));
		$search = ($value1)?['like','content',$value1]: ''; 
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
		$pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
		//菜单定位
		unset(yii::$app->session['localfirsturl']);
		yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/autoreply/index.html';
		return $this->render('index', [
				'data' => $data,
				'pages' => $pages,
				'value' => $value1,
				]);
	}

	/*进入添加页面*/
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
        $wxname = '';
        foreach($res1 as $v){
        	if($v['appid']==$res->wxappid){
        		$wxname = $v['name'];
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
			return $this->render('edit',['model'=>$res,'count'=>$count,'wxname'=>$wxname]);
		}
	}

	/*接收数据、保存*/
	public function actionCreate(){
		if(!isset($_POST['type'])){
			return $this->redirect('/autoreply/index.html');
		}
		$app = yii::$app->request;
		$auto = new Autoreply();
		if(isset($_POST['id'])){
			$id = Helper::filtdata($app->post('id',''),'INT');
			$auto = $auto->findOne($id);
			$auto_content = json_decode($auto->content);
		}
		$auto->wxappid = Helper::filtdata($app->post('wxappid',''));
		$auto->sort = Helper::filtdata($app->post('sort')); 
		$type = Helper::filtdata($app->post('type',''),'INT');
		$auto->type = $type;
		switch ($type) {
			case '1'://文本
				$auto->content =json_encode(trim($app->post('content','')));
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
						($auto->content)&& @unlink($path.json_decode($auto->content)[$k]->image);
					}else{
						if(!$auto->content){
							$arr[$k]['image'] = '';
						}else{
							$arr[$k]['image'] = json_decode($auto->content)[$k]->image;
						}
					}
					$arr[$k]['title'] = $title[$k];
					$arr[$k]['description'] = $description[$k];
					$arr[$k]['url'] = $url[$k];
				}
				$arr&&($arr = json_encode($arr));
				$auto->content = $arr;
				break;
			case '3'://图片
				if($_FILES['files']['name']){
					$path = dirname(dirname(__FILE__)).'/web/media/wxin/image/';
					$imagesdir = Helper::upload('files','jpeg',$path); 
					$wxinutil = new Wxinutil(); 
					$auto->content = $wxinutil->toImages($auto->wxappid,'/media/wxin/image/'.$imagesdir);//获取media_id
					@unlink($path.$auto->filename);
					$auto->filename = '/media/wxin/image/'.$imagesdir;
				}
				break;
			case '4'://视频
				$vfile = Helper::filtdata($app->post('vefile'));
	            $vtitle = Helper::filtdata($app->post('vtitle'));//标题
	            $vintroduction = Helper::filtdata($app->post('vintroduction'));//简介
	            if($auto->content){
	            	$media = json_decode($auto->content)->media_id;
	            }
	            if(!isset($media)){
	                $media = Wxinutil::toVideo($auto->wxappid,$vfile,$vtitle,$vintroduction);
	                $auto->filename = $vfile;
	            }
	            $auto->content = json_encode(['media_id'=>$media,'vtitle'=>$vtitle,'vintroduction'=>$vintroduction]);
	            break;
		}
		$auto->createtime = time();
		$auto->state = Helper::filtdata($app->post('state',''),'INT');
		if($auto->save()){
			$this->redirect('index.html');
		}
	}

    //上传视频
    public function actionTovideo(){
        if($_FILES['vfile']['name']){
            $files = $_FILES['vfile'];
            $new_path = dirname(dirname(__FILE__)).'/web/media/wxin/video';//文件路劲
            $suffix = trim(strrchr($files['name'],'.'),'.');
            if(!is_dir($new_path)){
              mkdir($new_path,0777,true);
            } 
            $newname = uniqid().rand(100,999).'.'.$suffix;
            $path_suffix = $new_path.'/'.$newname;
            move_uploaded_file($files['tmp_name'],$path_suffix);//上传文件
            return 'true_/media/wxin/video/'.$newname;
        }
    }

	/**
     * 删除
     * @param integer $id
     * @return mixed
     */
    public function actionDel()
    {
        $id = Helper::filtdata(Yii::$app->request->post('id'),'INT');
        $res = $this->findModel($id)->delete();
        if($res){
            return 1;//'删除成功！';
        }else{
            return 0;//'删除失败！';
        }
    }

    /**
     * Finds the Phoneinfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Phoneinfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Autoreply::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}