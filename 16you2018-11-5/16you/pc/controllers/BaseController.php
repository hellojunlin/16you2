<?php 
namespace pc\controllers;

use yii;
use yii\web\Controller;
use common\models\Game;
use common\common\Shareutil;

/**
 *基类
 * @author lin
 */
class BaseController extends Controller{
	public function init() { 
		if(!yii::$app->session['puid']){//判断平台id是否已经拿到
			if(isset($_GET['puid'])){//判断平台id是否正常传输 
				yii::$app->session['puid'] = htmlspecialchars(trim($_GET['puid']));
			}else{
				yii::$app->session['puid'] = '16you';
			} 
		}
	}
}