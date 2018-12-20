<?php 
namespace frontend\controllers;

use yii;
use frontend\controllers\BaseController;
use common\models\Gift;
use common\redismodel\GiftreceiveRedis;
use common\common\Helper;
use common\models\Plateform;
/**
 * 礼包 
 * @author he
 */
class GiftController extends BaseController{
    //我的礼包
    public function actionIndex() {
        $cur = Helper::filtdata(yii::$app->request->post('page',1));
        $pageSize = 20;
        $user = yii::$app->session['user'];
        $uid = $user->id;
        $gift = Gift::find()->where(['state'=>1,'type'=>1])->andWhere(['=','uid',$uid])->offset(($cur-1)*$pageSize)->limit($pageSize)->orderBy('createtime DESC')->asArray()->all();
        if(yii::$app->request->isAjax){
            if($gift){
                return json_encode([
                    'errorcode'=>0,
                    'info'=>$gift
                ]);
            }else{
                $info =($cur>1)? '已加载所有礼包':'暂时没有可领礼包';
                return json_encode([
                        'errorcode'=>1002,
                        'info'=>$info,
                ]);
            }
        }else{
            $puid = yii::$app->session['puid']; //平台id
            $pname = Plateform::find()->where(['punid'=>$puid])->select('pname')->one();
            $pname = $pname?$pname->pname:'';
            return $this->render('index',[
                    'gift'=>$gift, 
                    'pname'=>$pname, 
            ]); 
        }
    }
    
    //异步加载礼包
    public function actionGetgift(){
        if(!yii::$app->request->isAjax||!isset($_POST['page'])){
            return json_encode([
                    'errorcode'=>1000,
                    'info'=>'数据错误，请稍后再试',
            ]);
        }
        $cur = Helper::filtdata(yii::$app->request->post('page',1));
        $pageSize = 10;
        $uid = yii::$app->session['user']->id;
        $cur1 = $cur*$pageSize-$pageSize;
        $gift = \Yii::$app->db->createCommand("SELECT count(number) as num,content,game_name,game_image,gift_name,gid,state,number from g_gift where state=0 AND type=1 AND number not in (select number from g_gift where type=1 and uid=:uid) group By number order By createtime desc limit $cur1,$pageSize",[':uid'=>$uid])->queryAll();
        if($gift){ 
            foreach ($gift as $key => $value) {
                if($value['num']==0){
                    unset($gift[$key]);
                }
            }
        }
        if($gift){
            array_merge($gift);
            return json_encode([
                'errorcode'=>0,
                'info'=>$gift
            ]);
        }else{
            $info =($cur>1)? '已加载所有礼包':'暂时没有可领礼包';
            return json_encode([
                    'errorcode'=>1002,
                    'info'=>$info,
            ]);
        }
    }
    
    //礼包领取
    public function actionGift(){
        if(!yii::$app->request->isAjax||!isset($_POST['number'])){
            return json_encode([
                    'errorcode'=>1001,
                    'info'=>'数据错误，请稍后再试',
            ]);
        }
        $gift = Gift::find()->where(['number'=>$_POST['number'],'state'=>0])->one();
        if($gift){
            $user = yii::$app->session['user'];
            $gift->state = 1;
            $gift->uid = $user->id;
            $gift->createtime = time();
            if($gift->save()){
                  $redis = new GiftreceiveRedis();
                  $redis->gid = $gift->gid;
                  $redis->uid = $user->id;
                  $redis->CDKEY = $gift->CDKEY;
                  $redis->number = $gift->number;
                  $redis->game_name = $gift->game_name;
                  $redis->gift_name = $gift->gift_name;
                  $redis->username = $user->username;
                  $redis->game_image = $gift->game_image;
                  $redis->content = $gift->content;
                  $redis->save();
                  $res['gift_name'] = $gift->gift_name;
                  $res['CDKEY'] = $gift->CDKEY;
                  $res['content'] = $gift->content;
                  $res['gid'] = $gift->gid;
                  return json_encode([
                        'errorcode'=>0,
                        'info'=>$res,
                 ]);
            }
        }else{
            return json_encode([
                    'errorcode'=>1002,
                    'info'=>'礼包已被领完，请领其他礼包',
            ]);
        }
    }

    //首页礼包
    public function actionTogift(){ 
        yii::$app->session['typemenu'] = 5;
        $cur = Helper::filtdata(yii::$app->request->post('page',0));
        $game_name = Helper::filtdata(yii::$app->request->post('gamename',''));
        $pageSize = 5;
        $cur1 = $cur*$pageSize;
        $user = yii::$app->session['user'];
        $uid = $user->id;//用户id
        $uuid = "AND F.number not in (select number from g_gift where uid=$uid)";
        $gamename = $game_name?"AND F.game_name like '%$game_name%'":'';
        $gift = \Yii::$app->db->createCommand("SELECT COUNT(*) AS packet,T.num,T.content,T.number,T.gift_name,T.game_name,T.game_image,T.CDKEY,T.gid,T.descript,T.validtime,T.gifttype FROM (SELECT G.sort,count(F.number) as num,F.content,F.number,F.gift_name,F.game_name,F.game_image,F.CDKEY,F.validtime,F.gid,G.descript,F.gifttype from g_game G left join g_gift F on G.id=F.gid where F.state=0 AND F.type=1 AND G.state=1 $gamename $uuid group By gid,number) AS T group BY T.gid order By T.sort desc limit $cur1,$pageSize")->queryAll();
        if($gift){ 
            foreach ($gift as $key => $value) {
                if($value['num']==0){
                    unset($gift[$key]);
                }
            }
        }
        if(yii::$app->request->isAjax){
            if($gift){
                array_merge($gift);
                return json_encode([
                    'errorcode'=>0,
                    'info'=>$gift
                ]);
            }else{
                $info =($cur>0)? '已加载所有礼包':'暂时没有可领礼包';
                return json_encode([
                        'errorcode'=>1002,
                        'info'=>$info,
                ]);
            }
        }else{
            return $this->render('togift',[
                'gift'=>$gift,
                'user'=>$user,
                'game_name'=>$game_name
            ]);
        }
    }

    //查看更多礼包
    public function actionMoregift(){
        if(!yii::$app->request->isAjax||!yii::$app->request->post('data')){
            return json_encode([
                'errorcode'=>1001,
                'info'=>'网络错误，请稍后再试',
            ]);
        }
        $data = explode('!@%',Helper::filtdata(yii::$app->request->post('data')));
        $gid = $data['0'];
        $number = $data['1'];
        $uid = yii::$app->session['user']->id;//用户id
        $gift = \Yii::$app->db->createCommand("SELECT gift_name,count(number) as num,content,number,gid,CDKEY,game_name,gifttype FROM g_gift WHERE state=0 AND type=1 AND gid=:gid and number!=:number1 AND number not in (select number from g_gift where type=1 AND uid=:uid) group BY number",[':gid'=>$gid,':uid'=>$uid,':number1'=>$number])->queryAll();
        if($gift){
            return json_encode([
                'errorcode'=>0,
                'info'=>$gift
            ]);
        }else{
            return json_encode([
                'errorcode'=>1002,
                'info'=>'已加载所有礼包',
            ]);
        }
    }
}