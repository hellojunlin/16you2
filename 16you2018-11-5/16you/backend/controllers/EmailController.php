<?php

namespace backend\controllers;

use Yii;
use common\models\Email;
use common\models\Gift;
use common\models\Game;
use backend\controllers\BaseController;
use yii\data\Pagination;
use common\common\Helper;

/**
 * 邮件类
 */
class EmailController extends BaseController{
      /**
     * 进入邮件记录页.
     * @return mixed
     */
    public function actionIndex() { 
        $model = new Email();
        //分页
        $curPage = Yii:: $app->request->get( 'page',1);
        $pageSize = yii::$app->params['pagenum'];
        //搜索
        $value = Helper::filtdata(Yii:: $app->request->get('keyword',''));
        $select = Yii:: $app->request->get('selectval','');
        $search = ($value)?['like',$select,$value]: '';
        $query = $model->find()->groupBy('uniqid')->orderBy('createtime desc');
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        $data['data'] =  ($data['data'])?$data['data']->asArray()->all():'';
        if($data['data']){
            foreach ($data['data'] as $key => $val) {
                $_Email = clone $model;
                $res = $_Email->find()->where(['uniqid'=>$val['uniqid']])->select('uid')->all();
                foreach ($res as $v) {
                    if($v['uid']==1){
                        $uid[] = '发给所有用户';
                    }else{
                        $uid[] = $v['uid']; 
                    }
                }
                $data['data'][$key]['uid'] = implode('、',$uid);
                $uid = array();
            }
        }
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/Email/index.html';
        return $this->render('index', [
                'data' => $data, 
                'pages' => $pages,
                'value' => $value,
                'select'=>$select,
            ]);
    } 
    

    /**
     * 进入添加邮件页面
     * @return [type] [description]
     */
    public function actionToadd(){
        $gift = Gift::find()->where(['type'=>2,'state'=>0])->SELECT('gift_name,number')->groupBy('number')->orderBy('createtime desc')->asArray()->all();
        return $this->render('add',['gift'=>$gift]);
    }

    /**
     * 接收添加数据
     */
    public function actionCreate(){
        if(!isset($_POST['title'])||!isset($_POST['content'])||!isset($_POST['uid'])){
            return $this->redirect('add.html');
            exit;
        }
        $i = 0;
        $count = 0;
        $arr = array();
        $app = Yii::$app->request;
        $title = Helper::filtdata($app->post('title'));//邮件标题
        $content = Helper::filtdata($app->post('content'));//邮件内容
        $uid = explode("\r\n",Helper::filtdata($app->post('uid')));//用户id
        $num = Helper::filtdata($app->post('gift_content'));//礼包number
        $state = Helper::filtdata($app->post('state'));
        $number = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        if($num){
            $res = Gift::find()->where(['number'=>$num,'state'=>0])->select('game_name,id,CDKEY,number')->asArray()->all();
        }else{
            $res = array();
        }
        $model = new Email();
        if(is_array($uid)){
            $count = count($uid);
            foreach ($uid as $k=>$v) {
                $_Email = clone $model;
                if(trim($v)){
                    $_Email->uid = $v;
                    $_Email->uniqid = $number;
                    $_Email->content = $content;
                    $_Email->title = $title;
                    $_Email->state = $state;
                    $_Email->gift_number = ($res&&isset($res[$k]))?$res[$k]['number']:'';
                    $_Email->gift_content = ($res&&isset($res[$k]))?'游戏名称：'.$res[$k]['game_name'].'<br/>礼包兑换码：'.$res[$k]['CDKEY']:'';
                    $_Email->createtime = time();
                    if($_Email->save()){
                        //更改礼包信息
                        $qgift = Gift::find()->where(['state'=>0,'uid'=>0,'number'=>$_Email->gift_number])->one();
                        if($qgift){
                            $qgift->state =1;
                            $qgift->uid = $v;
                            $qgift->save();
                        }
                        $i++;
                    }else{
                        $arr[] = $v;
                    }
                }
            }
        }
        if($count==0){
            return '没有输入邮件激活码';
        }elseif($count==$i){
            return $this->redirect('index.html');
        }else{
            return '数据有'.($count-$i).'条未成功写入';
        }
    }

    /**
     * 加载编辑页面
     */
    public function actionToedit($id){
        $model = Email::find()->where(['uniqid'=>$id])->asArray()->all();
        $a_num = '';
        $bgift = '';
        $gift = '';
        $uid = '';
        if($model){
            $a_num = array('title'=>'','content'=>'','state'=>'','gift_content'=>'','gift_number'=>'','uniqid'=>'','createtime'=>'');
            foreach ($model as $key => $value) {
                $a[] = $value['uid'];
                $a_num['title'] = $value['title'];
                $a_num['content'] = $value['content'];
                $a_num['state'] = $value['state'];
                $a_num['uniqid'] = $value['uniqid'];
                $a_num['createtime'] = $value['createtime'];
                if($value['gift_number']){
                    $a_num['gift_number'] = $value['gift_number'];
                    $a_num['gift_content'] = $value['gift_content'];
                }
            }
            $uid = implode("\r\n",$a);
            if($a_num){
                $num = $a_num['gift_number'];
                $bgift = Gift::find()->where(['number'=>$num])->select('gift_name,number')->asArray()->one(); 
                $num = ['!=','number',$num];
            }else{
                $num = '';
            }
            $gift = Gift::find()->where(['type'=>2,'state'=>0])->andWhere($num)->groupBy('number')->orderBy('createtime desc')->asArray()->all();//礼包
        }
        return $this->render( 'edit', [
            'uid' => $uid,
            'gift'=>$gift,
            'model'=>$model,
            'bgift'=>$bgift,
            'anum'=>$a_num,
        ]);
    }


    /**
     * 接收编辑数据
     */
    public function actionUpdate(){
        if(!isset($_POST['uniqid'])||!isset($_POST['title'])||!isset($_POST['content'])){
            exit;
        }
        //$connection = Yii::$app->db->beginTransaction();//开启事务
        $i = 0;
        $count = 0;
        $arr = array();
        $app = Yii::$app->request;
        $uniqid = Helper::filtdata($app->post('uniqid'),'INT');
        $title = Helper::filtdata($app->post('title'));//邮件标题
        $content = Helper::filtdata($app->post('content'));//邮件内容
        $uid = explode("\r\n",Helper::filtdata($app->post('uid')));//用户id
        $num = Helper::filtdata($app->post('gift_content'));//礼包number
        $gift_number = Helper::filtdata($app->post('gift_number'));//number
        $state = Helper::filtdata($app->post('state'));
        $number = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        if($gift_number){
            $agift = \Yii::$app->db->createCommand("UPDATE g_gift SET uid=0,state=0 where number=:num and state=1",['num'=>$gift_number])->execute();
        }else{
            $agift = true;
        }
        $res = array();
        if(($state==1) &&$num){//state=1礼包已被分配领取的，$num为该礼包的批次
            $res = Gift::find()->where(['number'=>$num,'state'=>0])->select('game_name,id,CDKEY,number')->asArray()->all();
        }
        $model = new Email();
        if(is_array($uid)){
            $count = count($uid);
            foreach ($uid as $k=>$v) {
                $_Email = clone $model;
                if(trim($v)){
                    $_Email->uid = $v;
                    $_Email->uniqid = $number;
                    $_Email->content = $content;
                    $_Email->title = $title;
                    $_Email->state = $state;
                    $_Email->gift_number = ($res&&isset($res[$k]))?$res[$k]['number']:'';
                    $_Email->gift_content = ($res&&isset($res[$k]))?'游戏名称：'.$res[$k]['game_name'].'<br/>礼包兑换码：'.$res[$k]['CDKEY']:'';
                    $_Email->createtime = time();
                    if($_Email->save()){
                        //更改礼包信息
                        $qgift = Gift::find()->where(['state'=>0,'uid'=>0,'number'=>$_Email->gift_number])->one();
                        if($qgift){
                            $qgift->state =1;
                            $qgift->uid = $v;
                            $qgift->save();
                        }
                        $i++;
                    }else{
                        $arr[] = $v;
                    }
                }
            }
        }
        $aemail = Email::deleteAll(['uniqid'=>$uniqid]);
        if($count==0){
            //$connection->rollBack();//事物回滚
            return '没有输入邮件激活码';
        }elseif(($count==$i)&&($agift==0||$agift)&&$aemail){
            //$connection->commit();//事物提交
            return $this->redirect('index.html');
        }else{
            //$connection->rollBack();//事物回滚
            return '数据有'.($count-$i).'条未成功写入';
        }
    }
    
    /**
     * 删除邮件
     * @param integer $uniqid
     * @return mixed
     */
    public function actionDel()
    {
        $uniqid = Helper::filtdata(Yii::$app->request->post('uniqid'),'INT');
        $num = Helper::filtdata(Yii::$app->request->post('num'),'INT');
        $res = Email::deleteAll(['uniqid'=>$uniqid]);
        if($res){
            \Yii::$app->db->createCommand("UPDATE g_gift SET uid=0,state=0 where number=:num and state=1",['num'=>$num])->execute();
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
}