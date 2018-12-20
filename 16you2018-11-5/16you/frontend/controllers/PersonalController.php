<?php 
namespace frontend\controllers;

use yii;
use frontend\controllers\BaseController;
use common\common\Helper;
use common\models\UserSign;
use common\models\User;
use common\models\Exchange;
use common\models\Order;
use common\models\Game;
use common\models\Email;
use common\alisms\SendSms;
use common\redismodel\UserRedis;
use common\redismodel\GameRedis;
use common\models\Acquire;
use common\models\Integral;

/**
 * author：He
 */

class PersonalController extends BaseController{
	//个人中心首页
    public function actionIndex() { 
    	$user = yii::$app->session['user'];
        $uid = $user->id;
    	if(!$user){
    		$this->redirect('/wxauth/auth.html');
    		yii::$app->end();
    	}
      /*   $sign = UserSign::find()->where(['uid'=>$uid])->one();
        if($sign){
        	$cont = $sign->continuous;
        }else{
        	$cont = 0;
        }
        $sign_type = 0;
        $integral = $cont*10+50;
        if($sign){ 
            $time = strtotime(date('Y-m-d',time()));
            if($sign->endtime==$time){//今天已经签到过了
                $sign_type = 1;
            }
        } */
        $now = strtotime(date('Y-m-d'));
        $after = time();
        $where = "createtime BETWEEN $now AND $after";
        $exchange = Exchange::find()->where($where)->andWhere(['uid'=>$uid])->one();
        $order = yii::$app->db->createCommand("SELECT createtime,SUM(price) as price from g_order where state=2 and uid=:uid order By createtime",[':uid'=>$uid])->queryOne();
        $state = $order['createtime']?false:true;
        $vip['num'] = yii::$app->params['vip'][$user->vip];
        $vip['price'] = $order['price']; 
        $email = Email::find()->where(['uid'=>$user->Unique_ID,'type'=>0])->select('id')->one();//查询是否有未读发邮件 type 0未读1已读
        $puid = yii::$app->session['puid']; //平台id
        $usercurrency = User::find()->where(['id'=>$uid])->select('currencynum')->one(); //游币
        yii::$app->session['typemenu'] = 4;
        $view = yii::$app->view;
        $view->params['emaillayout'] = $email;
        $typearr = $this->getintegralsource($now, $after, $uid); //已获取的积分途经，页面不显示
        return $this->render("index",[
        	'user'=>$user,
            //'sign_type'=>$sign_type,
          //  'integral'=>$integral,
           /*  'vip'=>$vip,
            'state'=>$state,
            'exchange'=>$exchange, */
            'email'=>$email,
        	'usercurrency'=>$usercurrency,
        	'typearr'=>$typearr,
        ]);
    }   
    
    /*
     * 获取积分来源 ，如果有则页面不在显示
    */
    private function getintegralsource($starttime,$endtime,$uid){
    	$typearr = array();
    	$integralobj = Integral::find()->where(['or',['between','createtime',$starttime,$endtime],['type'=>[6,7]]])->andWhere(['uid'=>$uid])->asArray()->select('type')->all();  //查看今日是否已签到，充值以及完善信息等
    	if($integralobj){
    		foreach ($integralobj as $v){
    			$typearr[] = $v['type'];
    		}
    	}
    	return $typearr;
    }
    
    /**
     * 用户签到
     * 每日签到规则：（以一个星期为周期）第一天，奖励10分  第二天，奖励15分   第三天，奖励20分   第四天，奖励25分  第五天，奖励30分  第六天，奖励35分   第七天，奖励40分
     */
    public function actionUsersign(){
    	if(!yii::$app->request->isAjax){
    		return json_encode(['info'=>'网络异常，稍后在试','errorcode'=>1001]);
    	}
    	$uid = yii::$app->session['user']->id;
    	$user = User::findOne(['id'=>$uid]);
    	if(!$user){
    		return json_encode(['info'=>'网络异常，稍后在试','errorcode'=>1002]);
    	}
    	$signrecordarr = ($user->signrecode)? json_decode($user->signrecode,true) : '';   //获取签到记录
    	$signcount = $signrecordarr?count($signrecordarr):0;  //签到的天数
    	$time =  strtotime(date('Y-m-d'));//获取今日时间戳
    	$firstsign = $signrecordarr? current($signrecordarr): $time;   //取出第一天签到的记录
    	$mondaytime =  strtotime(date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600)));   //每周星期一时间戳
    	$gapdate = intval(($time - $mondaytime)/86400);   //今日与第一次签到的间隔天数
    	if(is_array($signrecordarr)&&in_array($time,$signrecordarr)){//今日已签到
    		return json_encode(['info'=>'今日已签到，请明日再来','errorcode'=>1003]);
    	}
    	if($signcount==0 || $signcount==7 || $gapdate>=7){//没有签到    已经签到7天    时间相隔超过7天 都清空之前数据 重新记录
    		$signrecordarr = array($time);
    		$integralnum = 10;  //积分
    	}else{
    		$signrecordarr[] = $time;
    		$signintegral = yii::$app->params['signintegral'];  //规则积分
    		$integralnum = isset($signintegral[$signcount+1])? $signintegral[$signcount+1]:10;
    	}
    	$connection = Yii::$app->db->beginTransaction();//开启事务
    	$integral = new Integral();
    	$integral->type = 3; //每日签到
    	$integral->integral = $integralnum;  //积分
    	$integral->uid = $uid;
    	$integral->createtime = time();
    	$user->integral = $user->integral+$integralnum;
    	$user->signrecode = json_encode($signrecordarr);
    	if($integral->save()&& $user->save()){
    		yii::$app->session['user'] = $user;
    		$connection->commit();//事物提交
    		return json_encode(['info'=>'签到成功','errorcode'=>0,'integral'=>$integralnum]);
    	}else{
    		$connection->rollBack();//事物回滚
    		return json_encode(['info'=>'网络异常，稍后在试','errorcode'=>1004]);
    	}
    }

    //签到
    public function actionUsersign2(){
        if(!yii::$app->request->isAjax){
            return json_encode(['info'=>'网络异常，稍后在试','errorcode'=>1000]);
        }
        $user = yii::$app->session['user'];
        $uid = $user->id;
        $model = new UserSign();
        $sign = $model->find()->where(['uid'=>$uid])->one();
        $endtime = strtotime(date('Y-m-d',time()));
        $integral = 50;
        if($sign){
        	$integral = $sign->continuous*10+$integral;
            if($sign->continuous<5){
                $sign->continuous = $sign->continuous+1;
            }
            $sign->endtime = $endtime;
            $res = $sign->save();
        }else{
            $model->uid = $uid;
            $model->continuous = 1;
            $model->endtime = $endtime;
            $model->createtime = time();
            $res = $model->save();
        }
        $user = User::findOne(['id'=>$uid]);
        if($user){
            $user->integral = $user->integral+$integral;  
            yii::$app->session['user'] = $user;
            if($user->save()){
            	$userredis = UserRedis::findOne(['id'=>$user->id]);
            	$userredis && $this->updateUserRedis($user,$userredis);
            } 
        }
        if($res){
            return json_encode(['info'=>'签到成功','errorcode'=>0]);
        }else{
            return json_encode(['info'=>'签到失败，请稍后再试','errorcode'=>1001]);
        }
    }
    
    
    /**
     * 实名认证    完善信息
     * $type  类型  6：实名认证   7：完善信息
     */
    public function actionRealnameauth(){
    	if(!yii::$app->request->isAjax){
    		return json_encode(['info'=>'网络异常，稍后在试','errorcode'=>1001]);
    	}
    	$type = Helper::filtdata($_POST['type'],'INT');   //类型  6：实名认证   7：完善信息
    	if(!$type || !($type！=6 && $type！=7)){
    	return json_encode(['info'=>'网络异常，稍后在试','errorcode'=>1001]);
    }
    	$uid = yii::$app->session['user']->id;
    	$integral = new Integral();
    	$integralres = Integral::findOne(['uid'=>$uid,'type'=>$type]);  //获取实名认证数据
    	if($integralres){//存在实名认证的记录
    	    return json_encode(['errorocde'=>1002,'info'=>'该积分已领取过']);
    	}
    	$user = User::findOne(['id'=>$uid]);
    	if(!$user){//用户不存在
    		return json_encode(['info'=>'网络异常，稍后在试','errorcode'=>1001]);
    	}
    	if($user->realname=='' || $user->IDcard=='' ){//未实名认证
    		$info = ($type==6)?'未实名认证' : '未完善信息';
    		return json_encode(['errorcode'=>1003,'info'=>$info]);
    	}
    	$integralnum = ($type==6)?500:1000;
    	$connection = Yii::$app->db->beginTransaction();//开启事务
    	$integral->type = $type; //实名认证
    	$integral->integral = 500;  //积分
    	$integral->uid = $uid;
    	$integral->createtime = time();
    	$user->integral = $user->integral+$integralnum;
    	if($integral->save() && $user->save()){
    		$connection->commit();//事物提交
    		return json_encode(['errorcode'=>0,'info'=>'领取成功','integral'=>$integralnum]);
    	}else{
    		$connection->rollBack();//事物回滚
    		return json_encode(['errorcode'=>1004,'info'=>'领取失败']);
    	}		
    }
    
    
    //绑定手机
    public function actionTophone(){
        $phone = yii::$app->session['user']->phone;
    	return $this->renderPartial('tophone',['phone'=>$phone]);
    }

    public function actionCreatephone(){
        if(!yii::$app->request->isAjax||!isset($_POST['num'])||!isset($_POST['code'])){
            return json_encode([
                'info'=>'网络异常，稍后在试',
                'errorcode'=>1000,
            ]);
        }
        $code = Helper::filtdata($_POST['code']);
        if(yii::$app->session['msgphone']==$code){
            unset(yii::$app->session['msgphone']);
            $id = yii::$app->session['user']->id;
            $user = User::findOne($id);
            if($user){
                $user->phone = htmlspecialchars(trim(yii::$app->request->post('num','')));
                $user->integral = $user->integral+500;
                if($user->save()){
                    $userredis = UserRedis::findOne($id);
                    $userredis->phone = $user->phone;
                    $userredis->save();
                    yii::$app->session['user'] = $user;
                    return json_encode([
                        'info'=>'绑定成功',
                        'errorcode'=>0,
                    ]);
                }else{
                    return json_encode([
                        'info'=>'绑定失败',
                        'errorcode'=>1001,
                    ]);
                }
            }
        }else{
            return json_encode([
                'info'=>'验证码输入错误',
                'errorcode'=>1002,
            ]);
        }
    }
    
    /**
     * 更新redis缓存
     * @param unknown $user
     * @param unknown $userredis
     */
    private function updateUserRedis($user,$userredis){
        if($userredis){//存在更新
            $userredis->phone = $user->phone;
            $userredis->integral = $user->integral;
            $userredis->save();
        }else{//重新保存
            $uredis = NEW UserRedis();
            $uredis->id = $user->id;
            $uredis->openid = $user->openid;
            $uredis->pid = $user->pid;
            $uredis->username = $user->username;
            $uredis->sex = $user->sex;
            $uredis->head_url = $user->head_url;
            $uredis->province = $user->province;
            $uredis->city = $user->city;
            $uredis->integral = $user->integral;
            $uredis->gid = $user->gid;
            $uredis->phone = $user->phone;
            $uredis->access_token = $user->access_token;
            $uredis->createtime = $user->createtime;
            $uredis->save();
        }
    }

    //vip页面
    public function actionVip(){
        $user = yii::$app->session['user'];
        if(!$user){
            $this->redirect('/wxauth/auth.html');
            yii::$app->end();
        }
        $price = \Yii::$app->db->createCommand("SELECT SUM(price) price from g_order where uid=:uid and state=2",[':uid'=>$user->id])->queryOne();
        $price = $price['price'];
        $num = $user->vip;
        $num1 = yii::$app->params['vip'][$num];
        return $this->renderPartial('vip',['num'=>$num,'price'=>$price,'num1'=>$num1]);
    }

    //实名认证
    public function actionReal(){
        return $this->renderPartial('real');
    }

    //填写实名
    public function actionRealedit(){
        $user = yii::$app->session['user'];
        if(isset($user->realname)&&$user->realname){
            $realname = mb_substr($user->realname,0,1).'**';
        }else{
            $realname = '';
        }
        if(isset($user->IDcard)&&$user->IDcard){
            $u_idfront = substr($user->IDcard,0,3);
            $u_idbehind = substr($user->IDcard,-4);
            $IDcard = $u_idfront.'***********'.$u_idbehind;
        }else{
            $IDcard = '';
        }
        return $this->renderPartial('realedit',[
            'realname'=>$realname,
            'IDcard'=>$IDcard
        ]);
    }

    //接收填写实名和身份证
    public function actionRealadd(){
        if(!yii::$app->request->isAjax||!isset($_POST['realname'])||!isset($_POST['IDcard'])){
            return json_encode([
                'info'=>'数据错误，请稍后再试',
                'errorcode'=>1001,
            ]);
        }
        $IDcard = Helper::filtdata($_POST['IDcard']);
        $res = User::findOne(['IDcard'=>$IDcard]);
        if($res){
            return json_encode([
                'info'=>'该身份证已存在,请重新输入',
                'errorcode'=>1002,
            ]);
        }else{
            $realname = Helper::filtdata($_POST['realname']);
            $user = yii::$app->session->get('user');
            $result = User::findOne($user->id);
            $result->IDcard = $IDcard;
            $result->realname = $realname;
            if($result->save()){
                $redis = UserRedis::findOne($result->id);
                $redis->IDcard = $IDcard;
                $redis->realname = $realname;
                $redis->save();
                yii::$app->session['user'] = $result;
                return json_encode([
                    'info'=>'认证成功',
                    'errorcode'=>0,
                ]);
            }else{
                return json_encode([
                    'info'=>'认证失败',
                    'errorcode'=>1003,
                ]);
            }
        }
    }

    //纠纷处理
    public function actionDispute(){
        return $this->renderPartial('dispute');
    }

    //用户指引
    public function actionUsergo(){
        return $this->renderPartial('usergo');
    }
    
    //账户安全
    public function actionAlipay(){
        $user = yii::$app->session['user'];
        $phone = (isset($user->phone)&&$user->phone)?$user->phone:false;
        $alipayaccount = (isset($user->alipayaccount)&&$user->alipayaccount)?$user->alipayaccount:false;
        $qq = (isset($user->qq)&&$user->qq)?$user->qq:false;
        $password = (isset($user->password)&&$user->password)?$user->password:false;
        return $this->renderPartial('alipay',[
            'phone'=>$phone,
            'alipayaccount'=>$alipayaccount,
            'qq'=>$qq,
            'password'=>$password,
        ]);
    }
    
    //填写账户信息
    public function actionAlipayedit(){
        $user = yii::$app->session['user'];
        if(isset($user->alipayname)&&$user->alipayname){
            $alipayname = mb_substr($user->alipayname,0,1).'**';
        }else{
            $alipayname = '';
        }
        if(isset($user->alipayaccount)&&$user->alipayaccount){
            $u_idfront = substr($user->alipayaccount,0,3);
            $u_idbehind = substr($user->alipayaccount,-3);
            $alipayaccount = $u_idfront.'***********'.$u_idbehind;
        }else{
            $alipayaccount = '';
        }
        return $this->renderPartial('alipayedit',[
            'alipayname'=>$alipayname,
            'alipayaccount'=>$alipayaccount
        ]);
    }
    
    //接收支付宝
    public function actionAlipayadd(){
        if(!yii::$app->request->isAjax||!isset($_POST['alipayname'])||!isset($_POST['alipayaccount'])){
            return json_encode([
                'info'=>'数据错误，请稍后再试',
                'errorcode'=>1001,
            ]);
        }
        $alipayaccount = Helper::filtdata($_POST['alipayaccount']);
        $res = User::findOne(['alipayaccount'=>$alipayaccount]);
        if($res){
            return json_encode([
                'info'=>'该支付宝账号已存在,请重新输入',
                'errorcode'=>1002,
            ]);
        }else{
            $alipayname = Helper::filtdata($_POST['alipayname']);
            $user = yii::$app->session->get('user');
            $result = User::findOne($user->id);
            $result->alipayaccount = $alipayaccount;
            $result->alipayname = $alipayname;
            if($result->save()){
                $redis = UserRedis::findOne($result->id);
                $redis->alipayaccount = $alipayaccount;
                $redis->alipayname = $alipayname;
                $redis->save();
                yii::$app->session['user'] = $result;
                return json_encode([
                    'info'=>'绑定成功',
                    'errorcode'=>0,
                ]);
            }else{
                return json_encode([
                    'info'=>'绑定失败',
                    'errorcode'=>1003,
                ]);
            }
        }
    }

    //填写密码
    public function actionTopwd(){
        $user = yii::$app->session['user'];
        $pwd = isset($user->password)?$user->password:'';
        return $this->renderPartial('topwd',['pwd'=>$pwd]); 
    }

    //接收密码
    public function actionAddpwd(){
        if(!yii::$app->request->isAjax||!isset($_POST['pwd'])){
            return json_encode([
                'info'=>'数据错误，请稍后再试',
                'errorcode'=>1001,
            ]);
        }
        $user = yii::$app->session->get('user');
        $id = $user->id;
        $pwd = Helper::filtdata($_POST['pwd']);
        $result = User::findOne($id);
        $result->password = $pwd;
        if($result->save()){
            yii::$app->session['user'] = $result;
            return json_encode([
                'info'=>'修改成功',
                'errorcode'=>0,
            ]);
        }else{
            return json_encode([
                'info'=>'修改失败',
                'errorcode'=>1003,
            ]);
        }
    }

    //填写qq
    public function actionToqq(){
        $user = yii::$app->session['user'];
        $qq = isset($user->qq)?$user->qq:'';
        return $this->renderPartial('toqq',['qq'=>$qq]); 
    }

    //接收qq
    public function actionAddqq(){
        if(!yii::$app->request->isAjax||!isset($_POST['qq'])){
            return json_encode([
                'info'=>'数据错误，请稍后再试',
                'errorcode'=>1001,
            ]);
        }
        $user = yii::$app->session->get('user');
        $id = $user->id;
        $qq = Helper::filtdata($_POST['qq']);
        $res = User::find()->where(['qq'=>$qq])->one();
        if($res&&($res->id!=$id)){
            return json_encode([
                'info'=>'该QQ已存在,请重新输入',
                'errorcode'=>1002,
            ]);
        }else{
            $result = User::findOne($id);
            $result->qq = $qq;
            if($result->save()){
                $redis = UserRedis::findOne($result->id);
                $redis->qq = $qq;
                $redis->save();
                yii::$app->session['user'] = $result;
                return json_encode([
                    'info'=>'绑定成功',
                    'errorcode'=>0,
                ]);
            }else{
                return json_encode([
                    'info'=>'绑定失败',
                    'errorcode'=>1003,
                ]);
            }
        }
    }

    //发送验证码
    public function actionAddsms(){
        if(!yii::$app->request->isAjax||!isset($_POST['phone'])){
            return json_encode([
                'info'=>'数据错误，请稍后再试',
                'errorcode'=>1001,
            ]);
        }
        $user = yii::$app->session->get('user');
        $id = $user->id;
        $phone = Helper::filtdata($_POST['phone']);
        $res = User::find()->where(['phone'=>$phone])->one();
        if($res&&($res->id!=$id)){
            return json_encode([
                'info'=>'该手机号码已存在,请重新输入',
                'errorcode'=>1002,
            ]);
        }else{
            $msg = rand(1000,9999);
            $sms = new SendSms();
            $res = $sms->send($msg,$phone);
            if($res){
                yii::$app->session['msgphone'] = $msg;
                return json_encode([
                    'info'=>'验证码已发送到手机',
                    'errorcode'=>0,
                ]);
            }else{
                return json_encode([
                    'info'=>'验证码发送失败,请稍后再试',
                    'errorcode'=>1003,
                ]);
            }
        }
    }
    
    //编辑手机号码
    public function actionEditphone(){
        $phone = yii::$app->session['user']->phone;
        return $this->renderPartial('editphone',['phone'=>$phone]);
    }

    //修改手机号码
    public function actionSmscode(){
        if(!yii::$app->request->isAjax||!isset($_POST['codenum'])){
            return json_encode([
                'info'=>'数据错误，请稍后再试',
                'errorcode'=>1001,
            ]);
        }
        $code = Helper::filtdata($_POST['codenum']);
        if(yii::$app->session['msgphone']==$code){
            unset(yii::$app->session['msgphone']);
            return json_encode([
                'info'=>'正确',
                'errorcode'=>0
            ]);
        }else{
            return json_encode([
                'info'=>'验证码输入错误',
                'errorcode'=>1000
            ]);
        }
    }

    /**
     * 完善信息
     * @return [type] [description]
     */
    public function actionInformation(){
        $user = yii::$app->session['user'];
        if(yii::$app->session['playgame']){ 
            $playgame = array_slice(yii::$app->session['playgame'],0,5);
        }else{
            $this->newplay(5); //获取最近在玩游戏
            $playgame = array_slice(yii::$app->session['playgame'],0,5);
        }
        //获取游戏信息
        if(yii::$app->cache->get('hotgame')|| yii::$app->cache->get('gamearr')){//不存在热门游戏或者游戏的缓存时则从数据库读取
            $gamearr = array_slice(yii::$app->cache->get('gamearr'),0,50);
            $hotgame = array_slice(yii::$app->cache->get('hotgame'),0,20);
            $allgame =  array_slice(array_merge(yii::$app->cache->get('gamearr'),yii::$app->cache->get('hotgame')),0,200);
        }else{
            $this->getGame();
            $gamearr = array_slice(yii::$app->cache->get('gamearr'),0,50);
            $hotgame = array_slice(yii::$app->cache->get('hotgame'),0,20);
            $allgame = array_slice(array_merge(yii::$app->cache->get('gamearr'),yii::$app->cache->get('hotgame')),0,200);
        }
        return $this->renderPartial('information',[
            'user'=>$user,
            'allgame'=>$allgame,
            'playgame'=>$playgame,
            'hotgame'=>$hotgame,
        ]);
    }

    /**
     * 邮箱
     */
    public function actionMyemail(){
        $user = yii::$app->session['user'];
        $uid = $user->id;
        $order = yii::$app->db->createCommand("SELECT createtime,SUM(price) as price from g_order where state=2 and uid=:uid order By createtime",[':uid'=>$uid])->queryOne();
        $state = $order['createtime']?false:true;
        $vip['num'] = yii::$app->params['vip'][$user->vip];
        $vip['price'] = $order['price'];
        //查询该用户邮件信息
        //type阅读状态0未读1已读 state是否有礼包1是2否 
        //uid=1 发给所有的用户
        $email = Email::find()->where(['or',['uid'=>$user->Unique_ID],'uid=1'])->select('id,title,createtime,type,state')->orderBy('createtime desc')->asArray()->all();
        $type0 = 0;//未读邮件的数量
        $type1 = 0;//已读邮件的数量
        foreach ($email as $ve) {
            if($ve['type']==0){
                $type0++;
            }
            $type1++;
        }
        return $this->renderPartial('myemail',[
            'user'=>$user,
            'vip'=>$vip,
            'email'=>$email,
            'type0'=>$type0,
            'type1'=>$type1,
        ]);
    }

    /**
     * 点击/打开邮箱
     */
    public function actionReademail(){
        if(!yii::$app->request->isAjax||!isset($_POST['id'])){
            return json_encode([
                'info'=>'数据错误，请稍后再试',
                'errorcode'=>1001,
            ]);
        }
        $id = Helper::filtdata($_POST['id']);
        $model = Email::findOne($id);
        if($model){
            $res = array();
            $res['title'] = $model->title;
            $res['type'] = $model->type;
            $res['content'] = $model->gift_content?$model->gift_content.'<br/>'.$model->content:$model->content;
            if($model->type==0){//改变阅读状态 1已读
                $model->type = 1;
                $model->save(); 
            }
            unset(yii::$app->session['emailnotice']);
            return json_encode([
                'info'=>$res,
                'errorcode'=>0
            ]);
        }else{
            return json_encode([
                'info'=>'该邮件已被屏蔽',
                'errorcode'=>1000
            ]);
        }
    }

    //个人信息完善
    public function actionCreateinformation(){
        if(!yii::$app->request->isAjax||!isset($_POST['realname'])||!isset($_POST['qq'])||!isset($_POST['birthday'])||!isset($_POST['phone'])||!isset($_POST['wxnumber'])){
            return json_encode([
                'errorcode'=>'1001',
                'info'=>'数据错误'
            ]);
        }
        $user = yii::$app->session['user'];
        $user->realname = Helper::filtdata($_POST['realname']);
        $user->qq = Helper::filtdata($_POST['qq']);
        $user->phone = Helper::filtdata($_POST['phone']);
        $user->wxnumber = Helper::filtdata($_POST['wxnumber']);
        $user->birthday = Helper::filtdata($_POST['birthday']);
        if($user->save()){
            $starttime = 1510243200;//2017.11.10
            $endtime = 1511280000;//2017.11.22 00:00:00
            $day = strtotime(date('Y-m-d'));
            if($starttime<=$day && ($endtime>=$day)){
                $acquire = new Acquire();
                $re_a = $acquire::findOne(['uid'=>$user->id,'type'=>2]);
                if(!$re_a){
                    $acquire->uid = $user->id;
                    $acquire->type = 2;
                    $acquire->num = 100;
                    $acquire->createtime = time();
                    $acquire->save();
                }
            }
            yii::$app->session['user'] = $user;
            return json_encode([
                'errorcode'=>0,
                'info'=>'修改成功',
            ]);
        }else{
            return json_encode([
                'errorcode'=>1002,
                'info'=>'修改失败',
            ]);
        }
    }

        /**
     * 获取热门游戏和其他游戏
     */
    private function getGame(){
        $gamearr = GameRedis::find()->where(['state'=>1])->asArray()->all();
        $gamearr && $gamearr = Helper::quick_sort($gamearr,'sort');//按某个字段排序
        if(!$gamearr){//redis不存在时，则去数据库查
            $gamearr = Game::find()->where(['state'=>1])->orderBy('sort desc')->asArray()->all();//游戏
            if($gamearr){//存在保存数据库
                $this->savegameredis($gamearr,2);
            }
        }
        $hotgame = array();
        if($gamearr){
            $index = 1;
            foreach ($gamearr as $k=>$game){
                if($index<=20 && $game['type']==1){ //存前20条热门游戏
                    $hotgame[] = $game;
                    unset($gamearr[$k]);  //删除已存入到热门的游戏
                    $index++;
                }
            }
        }
        yii::$app->session->set('hotgame',$hotgame); //热门游戏
        yii::$app->session->set('gamearr',$gamearr);
        $resarra = array();
        $resarra['hotgame'] = $hotgame;
        $resarra['gamearr'] = $gamearr;
        return $resarra;
    }

        /**
     * 最近在玩
     */
    private function newplay($limit){
        $user =  yii::$app->session['user'];
        $openid = $user->openid;
        //获取最近玩的游戏
        //$userarr = $user->gid;// User::find()->where(['openid'=>$openid])->asArray()->select('gid')->one();
        $temparr = array();
        if($user){
            $gid_arr = ( $user->gid)?json_decode($user->gid,true):array();
            if(!empty($gid_arr)){
                arsort($gid_arr);//以降序排序
                $gidarr = array_keys($gid_arr);
                //获取已启用的最近在玩游戏      
                $playgame = GameRedis::find()->where(['id'=>$gidarr,'state'=>1])->limit($limit)->asArray()->all();
                if(!$playgame){
                    //$playgame = Game::find()->where(['id'=>$gidarr,'state'=>1])->limit(50)->asArray()->select('id,name,descript,label,head_img,game_url')->all();
                    $playgame = Game::find()->where(['id'=>$gidarr,'state'=>1])->limit($limit)->asArray()->all();
                    $playgame = $this->savegameredis($playgame,2);
                }
                if($playgame){
                    foreach ($gidarr as $g){//获取对应游戏内容
                        foreach ($playgame as $k=>$play){
                            if($g==$play['id']){
                                $play['playtime'] = $gid_arr[$play['id']];
                                $temparr[] = $play;
                            }
                        }
                    }
                }
            }
        }
            yii::$app->session['playgame'] = $temparr;
    }
}