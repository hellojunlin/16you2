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
        <li class="active">留存统计 </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	留存统计 	
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                </span>
	                <?php if (is_array($plate)): ?>
                	<form action="" style="width:150px;float:right;margin:-7px 5px 0 0" >
                        <select class="form-control m-bot15" name="pid" id="pid">
                        	<option value="">选择平台</option>
                        	<?php foreach ($plate as $vp) {
                        		echo '<option value="'.$vp['id'].'">'.$vp['pname'].'</option>';
                        	} ?>
                        </select>
                	</form>
                	<?php else:?>
                		<span style="width:74px;float:right;margin:3px 23px 0 0">	<?php echo $plate;?></span>
	                <?php endif ?>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">      
	                    		<form action="">
	                    			<input type="hidden" name="pid" value="<?php echo $pid; ?>">
	                    			<label class="pull-left"><input type="text" onfocus="WdatePicker({ maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}' })" id="logmin" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="开始时间" value="<?php echo $starttime; ?>" name="starttime">
									</label><span class="pull-left" style="padding:0px 10px"> ----</span>
		                           	<label class="pull-left"><input type="text" onfocus="WdatePicker({ minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d' })" id="logmax" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="结束时间"value="<?php echo $endtime; ?>" name="endtime"></label>
                                    <label style="padding-left:15px;">
		                            <button class="btn btn-primary" type="submit">搜索</button>
		                            </label>
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
	                          	<th width="100px">日期</th>
	                            <th>激活数</th>
	                            <th>新增用户数</th>
	                            <th>次日留存（%）</th>
	                            <th>3日留存（%）</th>
	                            <th>4日留存（%）</th>
	                            <th>5日留存（%）</th>
	                            <th>6日留存（%）</th>
	                            <th>7日留存（%）</th>
	                            <!-- <th>操作</th> -->
	                        </tr>
	                        </thead>
	                        <tbody>
	                         <?php if($data['data']): ?>
	                         	<?php foreach ($data['data'] as $v):?>
	                          	<tr>
	                          		<td><?=Html::encode($v['count_time']);?></td>
	                          		<td><?=Html::encode($v['play_user']);?></td>
	                          		<td><?=Html::encode($v['new_user']);?></td>
	                          		<?php foreach ($v['retain'] as $v1) {
	                          			echo '<td>'.$v1.'</td>';
	                          		} ?>
	                          	</tr>
	                          <?php endforeach; ?>
	                          <?php else: ?>
	                    	<tr><td colspan="9" align="center">暂时没有数据！</td></tr>
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
<script src="/media/js/layer.js"></script>
<script>
$(function() {
	$('#pid').comboSelect();
});
	$('#pid').change(function(){
		var pid = $(this).find("option:selected").val();
		if(pid!=-1){
			layer.load();
			window.location.href='/retain/index.html?pid='+pid;
		}
	});
	var ppid = "<?php echo $pid ?>";
	$("#pid option[value='"+ppid+"']").attr('selected',true);
</script>