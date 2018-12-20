<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<script type="text/javascript" src="/media/js/My97DatePicker/WdatePicker.js"></script>
<div class="page-heading">
    <h3>
        商品管理
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">商品管理</a>
        </li>
        <li class="active">积分排行榜 </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	积分排行榜 	
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                </span>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">      
                		<form action="">
                			<label class="col-md-2"><input type="text" onfocus="WdatePicker({ maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}' })" id="logmin" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="开始时间" value="<?php echo $starttime; ?>" name="start_time">							
							</label><span class="col-md-1" style="padding: 0;width: 40px;display: inline-block;height: 34px;line-height: 34px;text-align: center;">--- </span>
                           	<label class="col-md-2" style="padding:0 2px 0 0"><input type="text" onfocus="WdatePicker({ minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d' })" id="logmax" class="form-control tooltips input-text Wdate" placeholder="结束时间"value="<?php echo $endtime; ?>" name="end_time"></label>
                			<div class="col-md-2" style="padding:0">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入用户ID..." data-original-title="请输入用户ID" name="Unique_ID" maxlength="8" value="<?php echo $Unique_ID ;?>">
                            </div>
                            <div class="col-md-2" style="padding:0px 1px">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入用户名称..." data-original-title="请输入用户名称" name="value" maxlength="16" value="<?php echo $value;?>">
                            </div>
                            <div class="col-md-2" style="padding:0px 1px">
                                        <select class="form-control" name="integraltype" id="integraltype">
                                        	<option value=" ">选择积分类型</option>
                                            <?php foreach (yii::$app->params['integral_type'] as $k=>$v):?>
                                        	<option value="<?php echo $k;?>"><?php echo $v;?></option>
                                        	<?php endforeach;?>
                                        </select>
                          </div>
                            <button class="btn btn-primary" type="submit">搜索</button>
                        </form>
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
	                            <th>序号</th>
	                            <th>头像</th>
	                            <th>用户ID</th>
	                            <th>用户名称</th>
	                            <th>总积分 </th>
	                            <th>剩余积分 </th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                         	<?php if($data['data']): ?>
	                            <?php foreach ($data['data'] as $k=>$v):?>
	                            <tr>
	                               	<td><?php echo $k+1;?></td>
	                               	<td>
		                            	<?php if($v['head_url']): ?>
		                            	<img src="<?= Html::encode($v['head_url']); ?>" style="width:40px">
										<?php else: ?>
										<img src="/media/images/noimg.jpg" style="width:40px">
										<?php endif; ?>
									</td>
	                               	<td><?php echo $v['Unique_ID']; ?></td>
	                               	<td><?php echo $v['username']; ?></td>
	                                <td><?php echo $v['totalintegral'];?></td>
	                                <td><?php echo $v['integral'];?></td>
	                            </tr>
	                            <?php endforeach;?>
	                       		<?php else: ?>
	                       		<tr><td colspan="20" align="center">暂时没有数据！</td></tr>
	                         	<?php endif;?>
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
<script>
//导出弹框，iframe层-禁滚动条
function download(){
	var starttime = $("input[name='starttime']").val();
	var endtime = $("input[name='endtime']").val();
	var Unique_ID = $("input[name='Unique_ID']").val();
	var pid = $("select[name='pid'] option:selected").val();
	var value = $("input[name='value']").val();
	 window.location.href="/user/rankingoutput.html?starttime="+starttime+'&endtime='+endtime+'&Unique_ID='+Unique_ID+'&pid='+pid+'&value='+value;
}
var integraltype = "<?php echo $integraltype; ?>";
$("#integraltype option[value='"+integraltype+"']").attr('selected',true);
</script>