<?php
namespace backend\controllers;
use yii;
use backend\controllers\BaseController;
use common\models\DefaultSetting;
use common\models\SettingProportion;
use common\models\Game;
use common\models\Company;
use yii\data\Pagination;
use common\common\Helper;

/**
 * 游戏分成比例设置
 */
class SettingController extends BaseController{

	//分成默认比例
	public function actionDefault(){
		$curPage = Yii:: $app->request->get( 'page',1);
		$pageSize = yii::$app->params['pagenum'];
		$keyword = Yii:: $app->request->get('keyword','');    
        $value = Helper::filtdata(Yii:: $app->request->get('value','')); 
		$search = ($value)?['like',$keyword,$value]: '';
		$query = (new \yii\db\Query())
                ->select('compname,proportion,D.createtime,gid,cid')
                ->from('g_default_setting AS D')
                ->leftJoin('g_company AS C','C.id = D.cid')
                ->orderBy("D.createtime desc");
		$data = Helper::getPages($query,$curPage,$pageSize,$search);
		if($data['data']){
        	$data['data'] = $data['data']->all();
        	if(yii::$app->cache->get('gamearr')){
	            $game = yii::$app->cache->get('gamearr');
	        }else{
	            $game =  Game::find()->where(['state'=>1])->limit(10000)->orderBy('sort desc')->asArray()->all();//游戏
	            //yii::$app->cache->set('gamearr',$game);
	        }
	        $arr = $arr1 = array();
	        if($game){
	        	foreach ($data['data'] as $key => $valu) {
	        		$arr1 = '';
	        		$arr = explode(',',$valu['gid']);
        			$arr2 = explode('&#@',$valu['proportion'])['0'];
	        		foreach ($game as $k => $v) {
        				if(in_array($v['id'],$arr)){
        					$arr1[] = $v['name'];
        					if(count($arr1)>21){
        						break;
        					}
        				}
        			}
        			if(count($arr1)>20){
        				$arr1 = array_slice($arr1,0,20);
        				$arr1[] = '......';
        			}
        			$data['data'][$key]['proportion'] = $arr2;
        			$data['data'][$key]['gname'] = $arr1;
        		}
	        }
        }
		$pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
		//菜单定位
		unset(yii::$app->session['localfirsturl']);
		yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/setting/default.html';
		return $this->render('default',[
			'data'=>$data,
			'value'=>$value,
			'keyword'=>$keyword,
			'pages'=>$pages
		]);
	}

	//重置分成比例
	public function actionProportion(){
		$curPage = Yii:: $app->request->get( 'page',1);
		$pageSize = yii::$app->params['pagenum'];
		$keyword = Yii:: $app->request->get('keyword','');    
        $value = Yii:: $app->request->get('value',''); 
		$search = ($value)?['like',$keyword,$value]: '';
		$query = (new \yii\db\Query())
                ->select('D.id,compname,proportion,D.createtime,gid,cid,effective_time')
                ->from('g_setting_proportion AS D')
                ->leftJoin('g_company AS C','C.id = D.cid')
                ->orderBy("D.createtime desc");
		$data = Helper::getPages($query,$curPage,$pageSize,$search);
        if($data['data']){
        	$data['data'] = $data['data']->all();
	        $game =  Game::find()->where(['state'=>1])->limit(10000)->orderBy('sort desc')->asArray()->all();//游戏
	        $arr = array();
	        if($game){
	        	foreach ($data['data'] as $key => $valu) {
	        		$arr1 = '';
	        		$arr = explode(',',$valu['gid']);
        			$arr2 = explode('&#@',$valu['proportion'])['0'];
	        		foreach ($game as $k => $v) {
        				if(in_array($v['id'],$arr)){
        					$arr1[] = $v['name'];
        					if(count($arr1)>21){
        						break;
        					}
        				}
        			}
        			if(count($arr1)>20){
        				$arr1 = array_slice($arr1,0,20);
        				$arr1[] = '......';
        			}
        			$data['data'][$key]['proportion'] = $arr2;
        			$data['data'][$key]['gname'] = $arr1;
        		}
	        }
        }
		$pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
		//菜单定位
		unset(yii::$app->session['localfirsturl']);
		yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/setting/proportion.html';
		return $this->render('proportion',[
			'data'=>$data,
			'value'=>$value,
			'keyword'=>$keyword,
			'pages'=>$pages
		]);
	}

	/**
     * 添加页面
     */
    public function actionToadd() {
    	$default = Company::find()->where('role!=-1')->select('id,compname')->asArray()->ALL();
        return $this->render("add",[
            'model'=>$default,
        ]);
    }

	/**
     * 编辑页面
     */
    public function actionToedit() {
    	$cid = Helper::filtdata(yii::$app->request->get('cid',''));
    	$model = (new \yii\db\Query())
	    		->select('compname,D.cid,proportion,effective_time')
	            ->from('g_setting_proportion AS D')
	            ->leftJoin('g_company AS C','C.id = D.cid')
	            ->where(['cid'=>$cid])
	            ->One();
	    $model['proportion'] =explode('&#@',$model['proportion']);
        return $this->render("edit",[
            'model'=>$model,
        ]);
    }

    /**
     * 存数据
     */
    public function actionCreate(){
    	if(!isset($_POST['cid'])||!isset($_POST['game'])||!isset($_POST['plate'])){
    		return $this->redirect('default.html');
    		exit;
    	}
    	$app = yii::$app->request;
    	$model = new SettingProportion();
        $game = Game::find()->where(['state'=>1])->limit(10000)->orderBy('sort desc')->asArray()->all();//游戏
        $cid = Helper::filtdata($app->post('cid',''));
        $arr = array();
        if($game){
        	foreach ($game as $v) {
        		if($v['cid']==$cid){
        			$arr[] = $v['id'];
        		}
        	}
        }
    	$game = Helper::filtdata($app->post('game',''));
    	$plate = Helper::filtdata($app->post('plate',''));
    	$model->cid = $cid;
        $model->proportion = $game.'&#@'.$plate;
    	$model->effective_time = strtotime(Helper::filtdata($app->post('effective_time','')));
    	$model->gid = implode(',',$arr);
    	$model->createtime = time();
    	if($model->save()){
    		return $this->redirect('proportion.html');
    	}
    }

    /**
     * 删除
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        $id = Helper::filtdata(Yii::$app->request->post('id'),'INT');
        if($id){
            $res = SettingProportion::deleteALL(['id'=>$id]);
            if($res){
                return 1;//'删除成功！';
            }else{
                return 0;//'删除失败！';
            }
        }
    }
}