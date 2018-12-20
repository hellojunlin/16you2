<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<script type="text/javascript" src="/media/js/My97DatePicker/WdatePicker.js"></script>
<div class="page-heading">
    <h3>
        数据统计
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">数据统计</a>
        </li>
        <li class="active aa">月汇总统计 </li>
    </ul>
</div>
<section style="margin:0 15px">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel panel-primary">
	            <header class="panel-heading">
	               	游戏总览
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
	                <section id="no-more-tables">
	                    <table class="table table-bordered table-striped table-condensed cf">
	                        <thead class="cf">
	                        <tr>
	                            <th>总用户数</th>
	                            <th>总付费用户数</th>
	                            <th>总付费金额(元)</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        	<tr>
	                                <td><?php echo $cplay;?></td>
	                                <td><?php echo $zcount['zuid']?$zcount['zuid']:0;?></td>
	                                <td><?php echo $zcount['zprice']?$zcount['zprice']:0;?></td>
	                            </tr>
	                        </tbody>
	                    </table>
	                </section>
	            </div>
	        </section>
        </div>
	</div>
</section>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	月汇总统计	
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
                		<span style="width:74px;float:right;margin:3px 23px 0 0"><?php echo $plate['0']['pname'];?></span>
	                <?php endif ?>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">      
	                    <div class="btn-group col-md-12" style="padding:5px 0px;">
	                    	<?php if(!yii::$app->session->get('pid')): ?>
	            			<button class="btn btn-warning" id="output" style="margin-right:10px"><i class="fa fa-cloud-download"></i> 导出Excel </button>
	            			<?php endif; ?>
	                    	<div class="form-group">
		                		<form action="">
		                			<span class="pull-left" style="padding:0px 10px; font-weight:bold;font-size:20px; ">选择年月份：</span>
		                           	<label class="pull-left">
		                           	 <input id="starttime" class="input-text Wdate" type="text" value="<?php echo $starttime?date("Y-m",$starttime):'';?>" name="yearmonth" style="height:34px;padding:6px 12px;border:1px solid #ccc;color: #555;border-radius: 4px;"
								      onfocus="WdatePicker({dateFmt:'yyyy-MM',maxDate:''})">
		                           	</label>
		                            <label style="padding-left:15px;">
		                            <input type="hidden" value="<?php echo $pid; ?>" name="pid">
		                            <button class="btn btn-primary" type="submit">搜索</button>
		                            </label>
		                        </form>
		                    </div>
		                </div>
	                </div> 
	                <section id="no-more-tables">
	                	<?php if ($data['data']): ?> 
	                		<div class="span6">
	                			<div class="dataTables_info" id="hidden-table-info_info" style="margin-bottom:10px">显示 <?= Html::encode($data['start'])?> 到 <?= Html::encode($data['end'])?> ，共 <?= Html::encode($data['count'])?>  条</div>
	                		</div>
	                	<?php endif; ?>
	                       <table class="table table-bordered table-striped table-condensed cf">
	                        <thead class="cf">
	                        <tr>
	                          	<th width="100px">日期</th>
	                            <th>月DAU
	                            	<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="当月玩游戏的用户数量">
	                            		<i class="fa fa-question-circle"></i>
	                            	</a>
	                            </th>
	                            <th>新增用户数
	                                <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="当月新增的注册用户数">
	                            		<i class="fa fa-question-circle"></i>
	                            	</a>
	                            </th>
	                            <th>老用户活跃数
	                                <a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="当月老用户激活数">
	                            		<i class="fa fa-question-circle"></i>
	                            	</a>
	                            </th>
	                            <th>付费人数
	                           		<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="当月充值的总用户数">
	                            		<i class="fa fa-question-circle"></i>
	                            	</a>
	                            </th>
	                            <th>总付费金额(元)
	                            	<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="当月所有用户充值的总金额">
	                            		<i class="fa fa-question-circle"></i>
	                            	</a>
	                            </th>
	                            <th>新增付费人数
	                            	<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="当月新增用户充值的人数">
	                            		<i class="fa fa-question-circle"></i>
	                            	</a>
	                            </th>
	                            <th>新增付费金额
	                            	<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="当月新增用户充值的总金额">
	                            		<i class="fa fa-question-circle"></i>
	                            	</a>
	                            </th>
	                            <th>付费次数
	                            	<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="当月用户总付费次数">
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
	                            	<a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="当月付费用户占活跃用户的比率">
	                            		<i class="fa fa-question-circle"></i>
	                            	</a>
	                            </th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                           <?php foreach ($data['data'] as $d):?> 
	                             <tr>
	                               	<td><?php echo isset($d['count_time'])?date('Y-m',$d['count_time']):0;?></td>
	                                <td><?php echo isset($d['play_user'])?$d['play_user']:0;?></td>
	                                <td><?php echo isset($d['new_user'])?$d['new_user']:0;?></td>
	                                <td><?php echo isset($d['old_user'])?$d['old_user']:0;?></td>
	                                <td><?php echo isset($d['pay_user'])?$d['pay_user']:0;?></td>
	                                <td><?php echo isset($d['pay_sum'])?$d['pay_sum']:0;?></td>
	                                <td><?php echo isset($d['cuser'])?$d['cuser']:0;?></td>
	                                <td><?php echo isset($d['cprice'])?$d['cprice']:0;?></td>
	                                <td><?php echo isset($d['pay_num'])?$d['pay_num']:0;?></td>
	                                <td><?php echo isset($d['ARPU'])?$d['ARPU']:0;?></td>
	                                <td><?php echo isset($d['ARPPU'])?$d['ARPPU']:0;?></td>
	                                <td><?php echo isset($d['pay_probability'])?$d['pay_probability']:0;?></td>
	                             </tr>
	                           <?php endforeach;?>
	                          <?php else: ?>
	                    	<tr><td colspan="13" align="center">暂时没有数据！</td></tr>
	                    	<?php endif; ?> 
	                        </tbody>
	                    </table>
	                </section>
	                <?php if($data['data']): ?>  
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
$(function() {
	$('#pid').comboSelect();
});
	$('#pid').change(function(){
		pid = $(this).find("option:selected").val();
		if(pid!=-1){
			layer.load();
			window.location.href='/count/monthcount.html?pid='+pid;
		}
	});
	var ppid = "<?php echo $pid ?>";
	$("#pid option[value='"+ppid+"']").attr('selected',true);

	//导出弹框，iframe层-禁滚动条
	function download(){
		layer.open({
		    type: 2,
		    title:'导出Excel',
		    area: ['450px', '70%'],
		    skin: 'layui-layer-rim', //加上边框
		    content: ['/count/download.html?pid='+ppid, 'no']
		});
	}

	$('#output').click(function(){
		var pid = $('#pid').val();
		var starttime = $('#starttime').val();
		window.location.href='/count/mdataexport.html?pid='+pid+'&yearmonth='+starttime;
	});
</script>