<?php
namespace pc\controllers;

use yii;
use yii\web\Controller;
use common\common\Helper;
use common\models\UserSign;
use common\models\User;
use common\models\Exchange;
use common\models\Order;
use common\models\Plateform;
use common\models\Game;
use common\common\Wxinutil;
use common\alisms\SendSms;
use common\redismodel\UserRedis;
use common\redismodel\GameRedis;
use pc\controllers\BaseController;
use common\models\Email;

/**
 * @author He
 */
class PersonalController extends BaseController{
	//个人中心首页
    public function actionIndex() { 
    	$user = yii::$app->session['user'];
        yii::$app->session['typemenu'] = 4;
        if(!$user){
            return $this->render("index");
            exit;
        }
        $uid = $user->id;
       /*  $sign = UserSign::find()->where(['uid'=>$uid])->one();
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
       /*  $now = strtotime(date('Y-m-d'));
        $after = time();
        $where = "createtime BETWEEN $now AND $after";
        $exchange = Exchange::find()->where($where)->andWhere(['uid'=>$uid])->one();
        $order = yii::$app->db->createCommand("SELECT createtime,SUM(price) as price from g_order where state=2 and uid=:uid order By createtime",[':uid'=>$uid])->queryOne();
        $state = $order['createtime']?false:true;
        $vip['num'] = yii::$app->params['vip'][$user->vip];
        $vip['price'] = $order['price']; */
        $puid = yii::$app->session['puid']; //平台id
        $pname = Plateform::find()->where(['punid'=>$puid])->select('pname')->one(); 
        $usercurrency = User::find()->where(['id'=>$uid])->select('currencynum')->one(); //游币
        $email = Email::find()->where(['uid'=>$user->Unique_ID,'type'=>0])->select('id')->one();//查询是否有未读发邮件 type 0未读1已读
        $view = yii::$app->view;
        $view->params['emaillayout'] = $email;
        return $this->render("index",[
        	'user'=>$user,
            /* 'sign_type'=>$sign_type,
            'integral'=>$integral, */
           /*  'vip'=>$vip,
        	'state'=>$state,
            'exchange'=>$exchange, */
        	'pname'=>$pname,
        	'usercurrency'=>$usercurrency,
        	'email'=>$email,
        ]);
    }   

    //签到
    public function actionUsersign(){
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
            $uredis->vip = $user->vip;
            $uredis->alipayname = $user->alipayname;
            $uredis->alipayaccount = $user->alipayaccount;
            $uredis->realname = $user->realname;
            $uredis->IDcard = $user->IDcard;
            $uredis->qq = $user->qq;
    		$uredis->save();
    	}
    }

    //vip页面
    public function actionVip(){
        $user = yii::$app->session['user'];
        if(!$user){
            $this->redirect('/common/error.html');
        }
        $price = \Yii::$app->db->createCommand("SELECT SUM(price) price from g_order where uid=:uid and state=2",[':uid'=>$user->id])->queryOne();
        $price = $price['price'];
        $num = $user->vip;
        $num1 = yii::$app->params['vip'][$num];
        $headurl = $user->head_url;
        return $this->renderPartial('vip',['num'=>$num,'price'=>$price,'num1'=>$num1,'headurl'=>$headurl]);
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
            $redis = UserRedis::findOne($result->id);
            $redis->password = $pwd;
            $redis->save();
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
            return $this->redirect('tosms.html?phone='.$phone);
        }
    }
    
    public function actionTosms(){
        if(!isset($_GET['phone'])){
            return json_encode([
                'info'=>'数据错误，请稍后再试',
                'errorcode'=>1001,
            ]);
        }
        $phone = Helper::filtdata($_GET['phone']);
        $msg = '1234';//rand(1000,9999);
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

    //编辑手机号码
    public function actionEditphone(){
        $phone = yii::$app->session['user']->phone;
    	return $this->renderPartial('editphone',['phone'=>$phone]);
    }

    //验证输入的短信验证码
    public function actionSmscode(){
        if(!yii::$app->request->isAjax||!isset($_POST['codenum'])){
            return json_encode([
                'info'=>'数据错误，请稍后再试',
                'errorcode'=>1001,
            ]);
        }
        $code = Helper::filtdata($_POST['codenum']);
        if(yii::$app->session['msgphone']==$code){
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
    
    
    //获取微信二维码
    public function actionCode(){
        if(!yii::$app->request->isAjax){
            return json_encode([
                'info'=>'数据错误，请稍后再试',
                'errorcode'=>1001
            ]);
        }
        //获取微信二维码
        $wxutil = new Wxinutil();
        $appid = yii::$app->params['wxinfo']['appid'];
        $verify = '2017'.rand(100,999).rand(100,999);
        $filearr = $wxutil->gettempcode($appid,$verify,dirname(dirname(__FILE__)).'/web/media/images/code/');
        $filename = '';
        if($filearr){
        	$filename = isset($filearr['filename'])?$filearr['filename']:'';
        	$verify = isset($filearr['ticket'])?md5($filearr['ticket']):'' ;
        }
        if($filename){
            return json_encode([
                'info'=>$filename,
                'verify'=>$verify,
                'errorcode'=>0
            ]);
        }else{
            return json_encode([
                'info'=>'系统错误，请换另外登录方式登录',
                'errorcode'=>1000
            ]);
        }
    }

    //账号登录
    public function actionUserlogin(){
        if(!yii::$app->request->isAjax||!isset($_POST['id'])||!isset($_POST['pwd'])){
            return json_encode([
                'info'=>'数据错误，请稍后再试',
                'errorcode'=>1001,
            ]);
        }
        $Unique_ID = Helper::filtdata($_POST['id']);
        $pwd = Helper::filtdata($_POST['pwd']);
        $user = User::find()->where(['Unique_ID'=>$Unique_ID,'password'=>$pwd])->one();
        if(!$user){
            $user = User::find()->where(['phone'=>$Unique_ID,'password'=>$pwd])->one();
        }
        if($user){
            yii::$app->session['user'] = $user;
            return json_encode([
                'info'=>'登录成功',
                'errorcode'=>0,
                'server'=>yii::$app->session['server']
            ]);
        }else{
            return json_encode([
                'info'=>'账号或密码不正确',
                'errorcode'=>1001,
            ]);
        }
    }
    //手机号码登录
    public function actionPhoneinput(){
        if(yii::$app->request->isAjax&&isset($_POST['phone'])&&isset($_POST['codenum'])){
            $code = Helper::filtdata($_POST['codenum']);
            $phone = Helper::filtdata($_POST['phone']);
            if(yii::$app->session['msgphone']==$code){//验证短信验证码
                $model = new UserRedis();
                $user = $model->find()->where(['phone'=>$phone])->one();
                if(!$user){
                    $umodel = new User();                    
                    $user = $umodel->find()->where(['phone'=>$phone])->one();//从数据库查
                    if($user){
                        $res = $model->find()->where(['id'=>$user->id])->one();//判断redis中该用户id是否存在
                        $this->updateUserRedis($user,$res);//存进redis
                    }
                }
                //判断是否存在用户绑定该手机号码
                if(!$user){
                    return json_encode([
                        'info'=>'该手机号码未绑定用户账号,请扫描微信二维码登录',
                        'errorcode'=>1001
                    ]);
                }else{
                    yii::$app->session['user'] = $user;
                    return json_encode([
                        'info'=>'登录成功',
                        'errorcode'=>0
                    ]);
                }
            }else{
                return json_encode([
                    'info'=>'验证码输入错误',
                    'errorcode'=>1000
                ]);
            }
        }
    }

    /**
     * 我的积分
     * @return [type] [description]
     */
    public function actionMyintegral(){
        return $this->renderPartial('myintegral');
    }
    
    /**
     * 我的邮箱
     * @return [type] [description]
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

    //手机二维码登录提示页
    public function actionCodelogin(){
        if(!isset($_GET['code'])){
            return $this->redirect('index.html');
        }
        $code = explode('!*%@',Helper::filtdata($_GET['code']));
        return $this->renderPartial('codelogin',[
            'code'=>$code['1'],
            'verify'=>$code['0'],
        ]);
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

    //个人信息完善
    public function actionCreateinformation(){
        if(!yii::$app->request->isAjax||!isset($_POST['realname'])||!isset($_POST['birthday'])||!isset($_POST['qq'])||!isset($_POST['phone'])||!isset($_POST['wxnumber'])){
            return json_encode([
                'errorcode'=>'1001',
                'info'=>'数据错误'
            ]);
        }
        $user = yii::$app->session['user'];
        $user->realname = Helper::filtdata($_POST['realname']);
        $user->birthday = Helper::filtdata($_POST['birthday']);
        $user->qq = Helper::filtdata($_POST['qq']);
        $user->phone = Helper::filtdata($_POST['phone']);
        $user->wxnumber = Helper::filtdata($_POST['wxnumber']);
        if($user->save()){
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
        if(!$user){
            return $this->render("index");
            exit;
        }
        $openid = $user->openid;
        $temparr = array();
        if($user){
            $gid_arr = ( $user->gid)?json_decode($user->gid,true):array();
            if(!empty($gid_arr)){
                arsort($gid_arr);//以降序排序
                $gidarr = array_keys($gid_arr);
                //获取已启用的最近在玩游戏      
                $playgame = GameRedis::find()->where(['id'=>$gidarr,'state'=>1])->limit($limit)->asArray()->all();
                if(!$playgame){
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
}