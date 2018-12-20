<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Plateform;
use common\common\Helper;
use common\models\AuthMenu;
use common\models\Manage;
use common\models\User;
use common\common\Getuserinfoutil;
use common\common\Wxinutil;
use common\pay\Wxcommonutil;
use common\models\Integral;
/**
* 后台登陆
*/
class LoginController extends Controller
{
	public function actionAcindex() {
		if(isset($_POST['userinfo'])){
			$userinfo = $_POST['userinfo'];
			if(!$userinfo){
				return false;
			}
			$unionid = $userinfo['unionid'];
			$user = User::findOne(['unionid'=>$unionid]);
			$isboolean = true;
			if(!$user){//不存在
				$getuser = new Getuserinfoutil();
				$access_token = $getuser->getaccess_token();
				$user = new User();
				$maxid = $user->find()->select('max(Unique_ID) AS MAXID')->asArray()->one();
				$user->Unique_ID = $maxid?($maxid['MAXID']+1):'10000000';
				$user->pid = 14;
				$user->username = isset($userinfo['nickname'])?Helper::filterEmoji($userinfo['nickname']):"";
				$user->unionid = isset($userinfo['unionid'])?$userinfo['unionid']:'';
				$user->sex = isset($userinfo['sex'])?$userinfo['sex']:0;
				$user->head_url = isset($userinfo['headimgurl'])?$userinfo['headimgurl']:'';
				$user->province = isset($userinfo['province'])?$userinfo['province']:'';
				$user->city = isset($userinfo['city'])?$userinfo['city']:'';
				$user->integral = 0;
				$user->gid = '';
				$user->phone = '';
				$user->access_token = $access_token;
				$user->createtime = time();
				$user->password = rand(100,999).rand(100,999);
				$user->vip = 0;
				$user->consult_id = '';
				$user->is_subecribe = 0;
				$user->appopenid = $userinfo['openid'];
				$user->logintype = 2;  //app登录
				$sres = $user->save();
				if(!$sres){
					$isboolean = false;
				}
			}
			yii::$app->session->set('user',$user);
			return $isboolean;
		}else{
			return false;
		}
	}
	
	public function actionIndex()
	{
		if(!isset($_POST['phone'])||!isset($_POST['password'])){
			return '数据错误,请稍后再试'; 
		}		
		$phone = Helper::filtdata(Yii::$app->request->post('phone'));
        $password  = md5(Helper::filtdata(Yii::$app->request->post('password')));
        $model = Manage::findOne(['username'=>$phone]);
    	if($model){//判断账号是否已被屏蔽
    		if($model->state == 0)
                return '该账号已被屏蔽'; 
            $m_pa = substr($model->password, 4);
            if($password == $m_pa){//判断密码是否正确
                yii::$app->session['tomodel'] = $model;
                yii::$app->session['role'] = $model->role;
                //菜单
                if(!Yii::$app->session['menu']){//缓存文件不存在时
                    $data = \Yii::$app->db->createCommand("SELECT child.child FROM g_auth_item_child as child JOIN g_auth_assignment as assignment ON child.parent=assignment.item_name WHERE assignment.user_id=:u_id",[':u_id'=>$model->id])->queryAll();
                    $authmenu = new AuthMenu();
                    $menu = $authmenu->find()->asArray()->orderBy('weight')->all();
                    $menuarr = array();
                    foreach ($menu as $fmenu ){
                        if($fmenu['parent']==-1){//存储一级目录
                            foreach ($menu as $m) {//存储二级目录
                                if($fmenu['id']==$m['parent']){
                                    $fmenu['cmenu'][] = $m;
                                }
                            }
                            $menuarr[]=$fmenu;
                        }
                    }

                    if($model->role!='-1'){//当前账号不是超级管理员 则只能查看对应平台数据
                    	$menuarr = $this->checkmenu($menuarr, $data);//获取该账号的权限菜单
                    	$pidarr = array();
                    	$platepid = -1;  //平台商的ID
                    	if($model->type==0) {  //平台管理者
                    		$type = 0;//平台管理者
                    		$pidarr[] = ($model->g_p_id)?$model->g_p_id:-1;
                    	    $plateform = Plateform::findOne(['id'=>$pidarr[0]]);
                        	$plateform && $platepid = $plateform->cid; 
                    	}elseif($model->type==1){ //平台商
                    		$platepid = $model->g_p_id;
                    		$type = 1;//平台商
                    		$form = Plateform::find()->where(['cid'=>$model->g_p_id])->select('id')->asArray()->all();   //查找该公司对应的平台
                    		if($form){
                    			foreach ($form as $k=>$v) {
                    				$pidarr[] = $v['id'];
                    			}
                    		}
                    		empty($pidarr) && $pidarr[] =-1;
                    	}else if($model->type==4){
                    		$type = 4;//普通管理员
                    	}
                    	yii::$app->session->set('pid',$pidarr);
                    	yii::$app->session->set('platepid',$platepid);
                    }else{
                    	$type = 3;//超级管理员
                    	yii::$app->session->set('pid','');
                    }
                    yii::$app->session->set('managetype',$type); //管理员类型
                    Yii::$app->session['menu'] = $menuarr; //所有的菜单
                    yii::$app->session['mdata'] = $data;   //用户的所有权限
                }
                return 0;
            }else{
                return '密码错误，请重新输入！';
            }
    	}else{
    		return '不存在该账号';
    	}
	}
	 
	/**
	 * 数组排序
	 */
	private function sigcol_arrsort($data,$col,$type=SORT_DESC){
		$arr = array();
		if(is_array($data)){
			$i=0;
			foreach($data as $k=>$v){
					if(key_exists($col,$v)){
						$arr[$i] = $v[$col];
						$i++;
					}else{
						continue;
					}
			}
		}else{
			return false;
		}
		array_multisort($arr,$type,$data);
		return $data;
	}
	
	/**
	 *
	 * @param unknown $menuarr //所有菜单
	 * @param unknown $dataarr  //用户拥有的菜单
	 */
	public function checkmenu($menuarr,$dataarr){
		$temparr = array(); //临时数组 存储整个菜单
		foreach ($menuarr as $menu ){
			$arr = array();//临时数组 存储子菜单
			$bool_temp = false;
			foreach ($dataarr as $d ){
				if($menu['route']==$d['child']){  //一级跳转的菜单
					$temparr[] = $menu;
				}else{//存在子菜单的顶级菜单目录
					if(isset($menu['cmenu'])){ //存在子菜单
						foreach ($menu['cmenu'] as $cmenu){
							if($cmenu['route']==$d['child']){//该用户拥有该子菜单
								$arr[] = $cmenu;
								$bool_temp = true;
							}
						}
					}
				}
			}
			if($bool_temp){
				$arr = $this->sigcol_arrsort($arr,'weight',SORT_ASC);
				$menu['cmenu'] = $arr;
				$temparr[] = $menu;
			}
		}
		return $temparr;
	}

	public function actionLogout() {
		unset(Yii::$app->session['tomodel']);
		unset(Yii::$app->session['role']);
		unset(Yii::$app->session['mdata']);
		unset(Yii::$app->session['menu']);
		unset(Yii::$app->session['managetype']);
    	Yii::$app->session->destroy();//摧毁session
        return $this->actionLogin();
    }
    
    //进入登录页面
    public function actionLogin() {
    	return $this -> renderPartial('index');
    }
    
    //错误页面
    public function actionToerror() {
    	return $this ->render('/index/perror');
    }
}