<?php

namespace backend\controllers;

use Yii;
use common\models\Gift;
use common\models\Game;
use backend\controllers\BaseController;
use yii\data\Pagination;
use common\common\Helper;
use common\redismodel\GiftreceiveRedis;

/**
 * 礼包类
 */
class GiftController extends BaseController{
	  /**
     * 进入礼包记录页.
     * @return mixed
     */
    public function actionIndex() { 
    	$model = new Gift();
    	//分页
    	$curPage = Yii:: $app->request->get( 'page',1);
    	$pageSize = yii::$app->params['pagenum'];
    	//搜索
    	$value = Helper::filtdata(Yii:: $app->request->get('keyword',''));
    	$select = Yii:: $app->request->get('selectval','');
    	$search = ($value)?['like',$select,$value]: '';
    	$query = $model->find()->orderBy('createtime desc')->groupBy('number')->select('count(number) as c_num,number,id,gift_name,game_image,game_name,content,CDKEY,payment,type,gifttype')->where('state=0');
    	$data = Helper::getPages($query,$curPage,$pageSize,$search);
    	$data['data'] =  ($data['data'])?$data['data']->asArray()->all():'';
    	$pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
    	//菜单定位
    	unset(yii::$app->session['localfirsturl']);
    	yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/gift/index.html';
    	return $this->render('index', [
    			'data' => $data, 
    			'pages' => $pages,
    			'value' => $value,
    			'select'=>$select,
    		]);
    } 
    

    /**
     * 进入添加礼包页面
     * @return [type] [description]
     */
    public function actionToadd(){
        $game =  Game::find()->where('state!=2')->limit(10000)->orderBy('id desc')->asArray()->all();//游戏
        return $this->render('add',[
            'game'=>$game,
        ]);
    }

    /**
     * 接收添加数据
     */
    public function actionCreate(){
        if(!isset($_POST['gift_name'])||!isset($_POST['game_name'])){
        	return $this->redirect('add.html');
            exit;
        }
        $i = 0;
        $count = 0;
        $arr = array();
        $app = Yii::$app->request;
        $gift_name = Helper::filtdata($app->post('gift_name'));
        $CDKEY = explode("\r\n",Helper::filtdata($app->post('CDKEY')));
        $game = explode('%$#',Helper::filtdata($app->post('game_name')));
        $content = Helper::filtdata($app->post('content'));
        $payment = Helper::filtdata($app->post('payment'));
        $type = Helper::filtdata($app->post('type'));
        $gifttype = Helper::filtdata($app->post('gifttype'));
        $validtime = Helper::filtdata($app->post('validtime',''));
        $number = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        if(is_array($CDKEY)){
            $count = count($CDKEY);
            foreach ($CDKEY as $v) {
            	$cdkey = (!$v && $gifttype==4)?'-1':$v;   //入群礼包
                $_gift = new Gift();
                if(trim($v)){
                    $_gift->CDKEY = $v;
                    $_gift->number = $number;
                    $_gift->gift_name = $gift_name;
                    $_gift->game_name = $game['0'];
                    $_gift->game_image = $game['1'];
                    $_gift->gid = $game['2'];
                    $_gift->content = $content;
                    $_gift->payment = $payment;
                    $_gift->createtime = time();
                    $_gift->state = 0;
                    $_gift->type = $type;
                    $_gift->gifttype = $gifttype;
                    $_gift->validtime = empty($validtime)?'':strtotime($validtime);
                    if($_gift->save()){
                        !($count==0 && $gifttype==4) && $i++;
                    }else{
                        $arr[] = $v;
                    }
                }else{
                	switch ($gifttype){
                		case 0: echo '新手礼包的兑换码不能为空<br/>';break;
                		case 1: echo '节日礼包的兑换码不能为空<br/>';break;
                		case 2: echo '活动礼包的兑换码不能为空<br/>';break;
                		case 3: echo '首发礼包的兑换码不能为空<br/>';break;
                	}
                }
            }
        }
        if($count==0){
            return '没有输入礼包激活码';
        }elseif($count==$i){
            return $this->redirect('index.html');
        }else{
            return '数据有'.($count-$i).'条未成功导入';
        }
    }

    /**
     * 加载编辑页面
     */
    public function actionToedit($id){
        $model = Gift::findOne($id);
        if($model){
            $game =  Game::find()->where('state!=2')->limit(10000)->orderBy('id desc')->asArray()->all();//游戏
            $model->CDKEY = Gift::find()->where(['number'=>$model->number])->select(['CDKEY','state'])->asArray()->ALL();
        }else{
            $game = '';
        }
        return $this->render( 'edit', [
    		'model' => $model,
            'game'=>$game,
		]);
    }


    /**
     * 接收编辑数据
     */
    public function actionUpdate(){
        if(!isset($_POST['number'])||!isset($_POST['gift_name'])||!isset($_POST['game_name'])){
            exit;
        }
        $app = Yii::$app->request;
        $number = Helper::filtdata($app->post('number'),'INT');
        $game = explode('%$#',Helper::filtdata($app->post('game_name')));
        $content = Helper::filtdata($app->post('content'));
        $gift_name = Helper::filtdata($app->post('gift_name'));
        $payment = Helper::filtdata($app->post('payment'));
        $type = Helper::filtdata($app->post('type'));
        $gifttype = Helper::filtdata($app->post('gifttype'));
        $CDKEY = explode("\r\n",Helper::filtdata($app->post('CDKEY')));
        $CDKEY = (!$CDKEY && $gifttype==4)?'-1':$CDKEY;   //入群礼包
        $res = \Yii::$app->db->createCommand("UPDATE g_gift SET game_name=:game_name,game_image=:game_image,gid=:gid,gift_name=:gift_name,content=:con,payment=:payment,type=:type,gifttype=:gifttype WHERE number=:number",[':payment'=>$payment,':number'=>$number,':gift_name'=>$gift_name,':game_name'=>$game['0'],':game_image'=>$game['1'],':gid'=>$game['2'],':con'=>$content,':type'=>$type,':gifttype'=>$gifttype])->execute();
        if($res || $res==0){
            $gift = Gift::find()->where(['number'=>$number])->select('CDKEY')->all();
            if($gift){
                $arr = '';
                foreach ($gift as $key => $value) {
                    if(in_array($value['CDKEY'],$CDKEY)){
                        UNSET($gift[$key]);
                    }else{
                        $arr[] = $value['CDKEY'];
                    }
                }
                if($arr){
                    Gift::deleteAll(['CDKEY'=>$arr]);
                }
            }
            return $this->redirect('index.html');
        }
    }

    /**
     * 删除礼包
     * @param integer $number
     * @return mixed
     */
    public function actionDel(){
        $number = Helper::filtdata(Yii::$app->request->post('number'),'INT');
        $res = Gift::deleteAll(['number'=>$number]);
        if($res){
            return json_encode([
                'errorcode'=>0,
                'info'=>'删除成功',
            ]);
        }else{
            return json_encode([
                'errorcode'=>1011,
                'info'=>'删除失败',
            ]);
        }
    }

    //改变礼包状态
    public function actionChangestate(){
        $CDKEY = Helper::filtdata(yii::$app->request->post('CDKEY'));
        $gift = Gift::findOne(['CDKEY'=>$CDKEY]);
        if($gift){
            if($gift->state==1){
                $gift->state = 0;
            }else{
                $gift->state = 1;
            }
            if($gift->save()){
                return 1;
            }else{
                return 0;
            }
        }
    }
}