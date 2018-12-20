<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<script type="text/javascript" src="/media/js/My97DatePicker/WdatePicker.js"></script>
<div class="page-heading">
    <h3>
       游客数据统计
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">游客数据统计</a>
        </li>
        <li class="active">游客汇总统计 </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	游客汇总统计 	
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                </span>
	                <?php if (is_array($plate) && $managertype!=0): ?>
                	<form action="" style="width:150px;float:right;margin:-7px 5px 0 0" >
                        <select class="form-control m-bot15" name="pid" id="pid">
                            <option value=''>选择平台</option>
                        	<?php foreach ($plate as $vp) {
                        		echo '<option value="'.$vp['id'].'">'.$vp['pname'].'</option>';
                        	} ?>
                        </select>
                	</form>
                	<?php else:?>
                		<span style="width:74px;float:right;margin:3px 23px 0 0">	<?php// echo $plate;?></span>
	                <?php endif ?>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">      
	                    <div class="btn-group col-md-12" style="padding:5px 0px;">
	                   	 	<?php if(!yii::$app->session->get('pid')): ?>
	            			<button class="btn btn-warning" onclick="download()" style="margin-right:10px"><i class="fa fa-cloud-download"></i> 导出Excel </button>
	            			<?php endif; ?>
	                    	<div class="form-group">
		                		<form action="">
		                			<label class="pull-left"><input type="text" onfocus="WdatePicker({ maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}' })" id="logmin" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="开始时间" value="<?php echo $starttime; ?>" name="starttime">
									</label><span class="pull-left" style="padding:0px 10px"> ----</span>
		                           	<label class="pull-left"><input type="text" onfocus="WdatePicker({ minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d' })" id="logmax" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="结束时间"value="<?php echo $endtime; ?>" name="endtime"></label>
		                            <label style="padding-left:15px;">
		                            <input type="hidden" value="<?php echo $pid; ?>" name="pid">
		                            <button class="btn btn-primary" type="submit">搜索</button>
		                            </label>
		                        </form>
		                    </div>
		                </div>
	                </div> 
	                <section id="no-more-tables">
	                	<div style="color:red;font-weight:bold"><?php if(isset($order)){echo '总付费用户数：'.$order['count_o'].'人&nbsp;&nbsp;&nbsp;&nbsp;总付费金额(元)：'.$order['count_p'].'元';} ?></div>
	                	<?php if ($data['data']): ?>
	                		<div class="span6">
	                			<div class="dataTables_info" id="hidden-table-info_info" style="margin-bottom:10px">显示 <?= Html::encode($data['start'])?> 到 <?= Html::encode($data['end'])?> ，共 <?= Html::encode($data['count'])?>  条</div>
	                		</div>
	                	<?php endif; ?>
	                    <table class="table table-bordered table-striped table-condensed cf">
	                        <thead class="cf">
	                        <tr>
	                          	<th width="100px">日期</th>
	                            <th>日DAU
	                            	<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="当日玩游戏的用户数量">
	                            		<i class="fa fa-question-circle"></i>
	                            	</a>
	                            </th>
	                            <th>付费人数
	                           		<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="当日充值的总用户数">
	                            		<i class="fa fa-question-circle"></i>
	                            	</a>
	                            </th>
	                            <th>总付费金额(元)
	                            	<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="当日所有用户充值的总金额">
	                            		<i class="fa fa-question-circle"></i>
	                            	</a>
	                            </th>
	                            <th>付费次数
	                            	<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="当日用户总付费次数">
	                            		<i class="fa fa-question-circle"></i>
	                            	</a>
	                            </th>
	                            <th>ARPU(元)
	                            	<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="充值总流水占活跃用户数的比例">
	                            		<i class="fa fa-question-circle"></i>
	                            	</a>
	                            </th>
	                            <th>ARPPU(元)
	                            	<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="充值流水占付费用户数的比例">
	                            		<i class="fa fa-question-circle"></i>
	                            	</a>
	                            </th>
	                            <th>付费率
	                            	<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="当日付费用户占活跃用户的比率">
	                            		<i class="fa fa-question-circle"></i>
	                            	</a>
	                            </th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        	<?php $u=0;$i=0; if($gameorder): ?>
	                        	<tr>
	                               	<td><?php echo date('Y-m-d',$gameorder['count_time']);?></td>
	                                <td><?php echo $gameorder['play_user'];?></td>
	                                <td><?php echo ($gameorder['pay_sum']>0)?$gameorder['pay_user']:0;  $u+=($gameorder['pay_sum']>0)?$gameorder['pay_user']:0;?></td>
	                                <td><?php if($gameorder['pay_sum']>0){$paysum = isset($hidearr[$gameorder['count_time']])?$gameorder['pay_sum']-$hidearr[$gameorder['count_time']]:$gameorder['pay_sum']; }else{$paysum = 0;} echo $paysum;  $i+= $paysum;?></td>
	                                <td><?php echo $gameorder['pay_num']?$gameorder['pay_num']:0;?></td>
	                                <td><?php echo $gameorder['ARPU'];?></td>
	                                <td><?php echo $gameorder['ARPPU'];?></td>
	                                <td><?php echo $gameorder['pay_probability'];?></td>
	                            </tr>
	                            <?php endif; ?>
	                         <?php if($data['data']): ?>
	                           <?php foreach ($data['data'] as $d):?>
	                             <tr>
	                               	<td><?php echo date('Y-m-d',$d['count_time']);?></td>
	                                <td><?php echo $d['play_user'];?></td>
	                                <td><?php echo ($d['pay_sum']>0)?$d['pay_user']:0;$u+= ($d['pay_sum']>0)?$d['pay_user']:0;?></td>
	                                <td><?php //echo ($d['pay_sum']>0)?$d['pay_sum']:0;$i+=($d['pay_sum']>0)?$d['pay_sum']:0;?>
	                               		<?php if($d['pay_sum']>0){$paysum = isset($hidearr[$d['count_time']])?$d['pay_sum']-$hidearr[$d['count_time']]:$d['pay_sum']; }else{$paysum = 0;} echo $paysum;  $i+= $paysum;?>
	                                </td>
	                                <td><?php echo $d['pay_num']?$d['pay_num']:0;?></td>
	                                <td><?php echo $d['ARPU'];?></td>
	                                <td><?php echo $d['ARPPU'];?></td>
	                                <td><?php echo $d['pay_probability'];?></td>
	                               </tr>
	                           <?php endforeach;?>
	                          <?php else: ?>
	                          	<?php if(!$gameorder){ ?>
	                    	<tr><td colspan="13" align="center">暂时没有数据！</td></tr><?php } ?>
	                    	<?php endif; ?> 
	                        </tbody>
	                    </table>
	                </section>
	                <?php if ($data['data']): ?>  
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
<script src="/media/js/layer/layer.js"></script>
<script>
	var user_num = "<?php echo $u; ?>";
	var price_num = "<?php echo $i; ?>";
	$("#no-more-tables>div").eq(0).html("总付费用户数："+user_num+"人&nbsp;&nbsp;&nbsp;&nbsp;总付费金额(元)："+price_num+"元");
	$('#pid').change(function(){
		pid = $(this).find("option:selected").val();
		if(pid!=-1){
			layer.load();
			window.location.href='/touristcount/tocount.html?pid='+pid;
		}
	});
	var ppid = "<?php echo $pid ?>";
	$("#pid option[value='"+ppid+"']").attr('selected',true);

	//导出弹框，iframe层-禁滚动条
	function download(){
		var start_time = $("input[name='start_time']").val();
        var end_time = $("input[name='end_time']").val();
        if(start_time=='undefined') {start_time = '';}
        if(end_time=='undefined') {end_time = '';}
        var pid = $("select[name='pid']").val();
		window.location.href="/touristcount/output.html?start_time="+start_time+'&end_time='+end_time+'&pid='+pid;
	}
</script>