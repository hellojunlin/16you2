<?php

namespace common\common;
use yii\db\ActiveRecord;

/**
 * 系统自动退款
 * @author junlin 
 *
 */
class Autorefund extends ActiveRecord{
   public function autorefund($mid){
   
   	$nowtime = time();
   	$actarr = \Yii::$app->db->createCommand("SELECT id,num FROM eat_active_info WHERE m_id=:mid AND sign_end_time>$nowtime",[':mid'=>$mid])->queryAll();  //查询该商家所有报名结束的活动的id
   	$actlistarr = array();
   	foreach($actarr as $act){
   		$actlistarr[$act['id']] = $act['num'];  //以数组形式记录活动id和规定人数值 key=id value=num
   	}
   	$signnum = \Yii::$app->db->createCommand("SELECT a_id,out_trade_no,pay_money FROM eat_sign_record WHERE a_id IN (SELECT id FROM eat_active_info WHERE m_id=:mid AND state=2)",[':mid'=>$mid])->queryAll();  //查询该商家所有活动的实选人数（付完款）
   	$signarr = array();
   	$sarr = array();//存取数据
   	$temparr = array();
   	foreach ($signnum as $sign){
   		$signarr[] = $sign['a_id'];  //以数组形式记录活动id
   		$temparr['a_id'] = $sign['a_id'];
   		$temparr['out_trade_no'] = $sign['out_trade_no'];
   		$temparr['pay_money'] = $sign['pay_money'];
   		$sarr[] =$temparr;
   	}
   	$resnumarr = array_count_values($signarr);//统计参加人数
   	foreach ($actarr as $act){
   		if(isset($resnumarr[$act['id']])){
   			if($resnumarr[$act['id']]<($act['num']/2)){//参加人数比规定人数少
   				$transaction = yii::$app->db->beginTransaction();//开始事务
   				$wxpay = new Wxpayutil();
   				$signrecord = SignRecord::findOne(['a_id'=>$act['id']]);
   				foreach ($sarr as $sign){
   					$res = $wxpay->refund($sign['out_trade_no'],$sign['pay_money'],'系统自动退款');
   					if($res['errorcode'] == 0){//退款成功
   						$signrecord->state = 5;   //同意退款
   						$signrecord->pay_type = 2;  //支付类型 收入（退款）
   						$saveres = $signrecord->save();
   						if(!$saveres){
   							echo '网络异常，删除失败';exit();
   							$transaction->rollBack();// 事务回滚
   							return json_encode ( [//退款失败
   									'info' => '网络异常，删除失败',
   									'errorcode' => '1001'
   									] );
   							exit ();
   						}
   					}else{
   						echo $res['msg'];exit();
   						$transaction->rollBack();// 事务回滚
   						return json_encode ( [//退款失败
   								'info' => $res['msg'].',活动取消失败',
   								'errorcode' => '1001'
   								] );
   						exit ();
   					}
   				}
   				$transaction->commit();//提交事务
   			}
   		}
   	}
    }	

}

?>