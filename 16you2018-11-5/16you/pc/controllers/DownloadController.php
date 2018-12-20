<?php
namespace pc\controllers;

use yii;
use yii\web\Controller;
use common\models\User;
use common\models\Downloadrecord;
use common\common\Helper;
use common\common\Getuserinfoutil;


/**
 * @author He
 */
class DownloadController extends Controller{
	 //个人中心首页
	 public function actionIndex() { 
	 	if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
	 		return $this->redirect('https://www.pgyer.com/scwy');
	 	}
	     return $this->renderPartial('index');
	 }
	 
	 /**
	  * app下载
	  */
	 public function actionDownload(){
		 	$filePath = "media/app/";//此处给出你下载的文件在服务器的什么地方
		 	$fileName = "16you.apk";
		 	//此处给出你下载的文件名
		 	$file = fopen($filePath . $fileName, "r"); //   打开文件
		 	//输入文件标签
		 	Header("Content-type:application/octet-stream ");
		 	Header("Accept-Ranges:bytes ");
		 	Header("Accept-Length:   " . filesize($filePath . $fileName));
		 	Header("Content-Disposition:   attachment;   filename= " . $fileName);
		 	//   输出文件内容
		 	echo fread($file, filesize($filePath . $fileName));
		 	fclose($file);
		 	$download = new Downloadrecord();
		 	$download->createtime = time();
		 	$download->save();
		 	exit();
	}
}