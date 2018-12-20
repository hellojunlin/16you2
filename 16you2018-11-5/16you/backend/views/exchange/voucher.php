<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<script type="text/javascript" src="/media/js/My97DatePicker/WdatePicker.js"></script>
<div class="page-heading">
    <h3>商品管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['voucher']);?>">代金券记录</a>
        </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">代金券记录
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">
	                    <div class="btn-group pull-right">
	                    	<div class="form-group">
	                    		<form action="">
                                    <button class="btn btn-primary" type="submit">搜索</button>
                                    <div class="col-md-2 box-r-margin" style="width:210px;padding:5;"><input class="inputstylebox form-control" placeholder="用户唯一ID..." data-original-title="请输入用户唯一ID"  name="value" value="<?php echo $value ?>"></div>
	                    		    <div class="col-md-2 box-r-margin" style="width:210px;padding:5;"><input class="inputstylebox form-control" placeholder="订单号..." data-original-title="订单号" name="transaction_id" value="<?php echo $transaction_id ?>"></div>
	                    			<div class="col-md-2 box-r-margin" style="padding:0px;width:150px;">
                                        <select class="form-control" name="state" id="state">
                                        	<option value=" ">选择订单状态</option>
                                        	<option value="1">待付款</option>
                                        	<option value="2">已付款</option>
                                        	<option value="4">已退款</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
	                    			<label style="padding:0px;width:150px;"><input type="text" onfocus="WdatePicker({ maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}' })" id="logmin" class="form-control tooltips input-text Wdate" style="width:150px;"placeholder="开始时间" value="<?php echo $start_time; ?>" name="start_time">
									</label>
									</div>
									<div class="col-md-2">
		                           	<label style="padding:0px;width:150px;"><input type="text" onfocus="WdatePicker({ minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d' })" id="logmax" class="form-control tooltips input-text Wdate" style="width:150px;"placeholder="结束时间"value="<?php echo $end_time; ?>" name="end_time"></label>
                                    </div>
                                </form>
	                        </div>
	                    </div>
	                </div>
	                <section id="no-more-tables">
	                    <div style="color:red;font-weight:bold">成功付款总额：<?php if($order){echo ($order['count_p'])? $order['count_p']:0;?>元&nbsp;&nbsp;&nbsp;&nbsp;充值人数：<?php echo $order['count_u'];} ?></div>
	                	<?php if ($data['data']): ?> 
	                		<div class="span6">
	                			<div class="dataTables_info" id="hidden-table-info_info" style="margin-bottom:10px">显示 <?=Html::encode($data['start'])?> 到 <?= Html::encode($data['end'])?> ，共 <?= Html::encode($data['count'])?>  条</div>
	                		</div>
	                	<?php endif; ?>
	                    <table class="table table-bordered table-striped table-condensed cf">
	                        <thead class="cf">
	                        <tr>
	                            <th>ID</th>
	                            <th>用户头像</th>
	                            <th>用户名称</th>
	                            <th>用户唯一ID</th>
	                            <th>支付金额</th>
	                            <th>游币数量</th>
	                            <th>代金券类型</th>
	                            <th>订单号</th>
	                            <th>支付方式</th>
	                            <th>状态</th>
	                            <th>创建时间</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
		                            <td><?= $k+1;?></td>
		                            <td>
		                            	<?php if($v['head_url']): ?>
		                            	<img src="<?= Html::encode($v['head_url']); ?>" style="width:40px">
										<?php else: ?>
										<img src="/media/images/noimg.jpg" style="width:40px">
										<?php endif; ?>
									</td>
		                            <td><?= Html::encode($v['username']);?></td>
		                            <td><?= Html::encode($v['Unique_ID'])?></td>
		                            <td><?= Html::encode($v['price']);?></td>
		                            <td><?= Html::encode($v['currencynum']);?></td>
		                            <td><?= Html::encode($v['currencynum']);?>元</td>
		                            <td><?= Html::encode($v['transaction_id']);?></td>
		                            <td>
		                             <?php switch ($v['ptype']) {
			                            	case '1':echo '微信支付';break;
			                            	case '2':echo '盛-微信';break;
			                            	case '3':echo '盛-支付宝';break;
		                            		case '4':echo '盛-网银';break;
	                            			case '5':echo '盛-H5快捷';break;
	                            			case '6':echo '微信扫码';break;
	                            			case '7':echo '盛_微信扫码';break;
	                            		    case '8':echo '游币支付';break;
	                            			case '9':echo '优_微信H5支付';break;
	                            			case '10':echo '优_微信扫码支付';break;
	                            			case '11':echo '优_支付宝扫码';break;
	                            			case '12':echo '优_支付宝app';break;
		                            }?>
		                            </td>
		                            <td>
		                            <?php switch ($v['state']){
		                            	case '1':
		                            		echo '<label class="label label-info">待付款</label>';
		                            		break;
		                            	case '2':
		                            		echo '<label class="label label-success">支付成功</label>';
		                            		break;
		                            	case '3':
		                            		echo '<label class="label label-warning">退款中</label>';
		                            		break;
		                            	case '4':
		                            		echo '<label class="label label-danger">已退款</label>';
		                            		break;
		                            	case '5':
		                            		echo '<label class="label label-primary">支付失败</label>';
		                            		break;
		                            }?>
		                            </td>
		                            <td><?php echo date('Y-m-d H:i:s',$v['createtime']); ?></td>
		                        </tr>
	                        	<?php endforeach;?>
	                        
	                    	<?php else: ?>
	                    	<tr><td colspan="30" align="center">暂时没有数据</td></tr>
	                    	<?php endif; ?>
	                        </tbody>
	                    </table>
	                </section>
	                <?php if ($data['data']):?>  
	                	<div class="span6">
	                		<div class="dataTables_paginate paging_bootstrap pagination" style="margin:0;padding:0;">
	                    		<?php echo LinkPager::widget(['pagination' => $pages]);?>
	                    	</div>
	                    </div>
	                </div>
	                <?php endif; ?>
	            </div>
	        </section>
        </div>
	</div>
</section>
<script type="text/javascript">
var state = "<?php echo $state; ?>";
$("#state option[value='"+state+"']").attr('selected',true);
</script>
