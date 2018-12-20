<?php
namespace backend\controllers;
use yii;
use yii\log\Logger;
use yii\web\Controller;
use common\common\Helper;
use common\common\Sftpayutil;
use common\models\Refund;



/**
 * 盛付通退款回调地址
 *
 */
class SftrefundnotifyController extends Controller{
	public function actionSftrefund(){
			$Version = Helper::filtdata($_POST["Version"]);       //版本号
			$Charset = Helper::filtdata($_POST["Charset"]);       //字符集
			$ServiceCode = Helper::filtdata($_POST["ServiceCode"]);       //通知的类型
			$SenderId = Helper::filtdata($_POST["SenderId"]);       //发送方标识
			$TraceNo = Helper::filtdata($_POST["TraceNo"]);       //请求序列号
			$SendTime = Helper::filtdata($_POST["SendTime"]);       //商户提交订单申请支付的时间
			$OriginalOrderNo = Helper::filtdata($_POST["OriginalOrderNo"]);       //商户原支付订单号
			$RefundOrderNo = Helper::filtdata($_POST["RefundOrderNo"]);       //商户退款请求单号
			$RefundAmount = Helper::filtdata($_POST["RefundAmount"]);       //盛付通交易号
			$RefundTransNo = Helper::filtdata($_POST["RefundTransNo"]);       //盛付通实际支付金额
			$Status = Helper::filtdata($_POST["Status"]);       //支付状态
			$ErrorCode = Helper::filtdata($_POST["ErrorCode"]);       //版盛付通交易类型
			$ErrorMsg = Helper::filtdata($_POST["ErrorMsg"]);       //盛付通交易时间
			$Ext1 = stripslashes($_POST["Ext1"]);       //扩展1
			$Ext2 = $_POST["Ext2"];       //扩展2
			$SignType = Helper::filtdata($_POST["SignType"]);       //签名串
			$SignMsg = Helper::filtdata($_POST["SignMsg"]);
			if($Status!="01"){//状态不为01 则为付款不成功
				$this->sftlogerrcode($Status, '盛付通H5快捷支付');
				exit;
			}
			
			//第一步进行相关的验签操作
			$encryptCode =$this->isEmpty($ServiceCode)?"":$ServiceCode;
			$encryptCode.=$this->isEmpty($Version)?"":$Version;
			$encryptCode.=$this->isEmpty($Charset)?"":$Charset;
			$encryptCode.=$this->isEmpty($TraceNo)?"":$TraceNo;
			$encryptCode.=$this->isEmpty($SenderId)?"":$SenderId;
			$encryptCode.=$this->isEmpty($SendTime)?"":$SendTime;
			$encryptCode.=$this->isEmpty($RefundOrderNo)?"":$RefundOrderNo;
			$encryptCode.=$this->isEmpty($OriginalOrderNo)?"":$OriginalOrderNo;
			$encryptCode.=$this->isEmpty($Status)?"":$Status;
			$encryptCode.=$this->isEmpty($RefundAmount)?"":$RefundAmount;
			$encryptCode.=$this->isEmpty($RefundTransNo)?"":$RefundTransNo;
			$encryptCode.=$this->isEmpty($SignType)?"":$SignType;
			$sftpayutil = new Sftpayutil();
			
			$encryptCode=$encryptCode.yii::$app->params['sftpay']['key'];
			$mysignMsg= strtoupper(md5($encryptCode));
			if($mysignMsg==$SignMsg){//验证成功
				$refund = Refund::findOne(['refundorderno'=>$RefundOrderNo]);
				if($refund){
					$refund->state = 2; //退款回调成功
					$refund->save();
				}
			}
	}
}