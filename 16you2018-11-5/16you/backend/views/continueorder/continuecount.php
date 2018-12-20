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
        <li class="active">持续付费统计 </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	持续付费统计 	
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                </span>
	                <?php if (is_array($plate)): ?>
                	<form action="" style="width:150px;float:right;margin:-7px 5px 0 0" >
                        <select class="form-control m-bot15" name="pid" id="pid">
                        	<?php foreach ($plate as $vp) {
                        		echo '<option value="'.$vp['id'].'">'.$vp['pname'].'</option>';
                        	} ?>
                        </select>
                	</form>
                	<?php else:?>
                		<span style="width:74px;float:right;margin:3px 23px 0 0"><?php echo $plate;?></span>
	                <?php endif ?>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">      
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
	                            <th>当日新增付费人数</th>
	                            <th>持续2日付费人数</th>
	                            <th>持续3日付费人数</th>
	                            <th>持续4日付费人数</th>
	                            <th>持续5日付费人数</th>
	                            <th>持续6日付费人数</th>
	                            <th>持续7日付费人数</th>
	                            <th>当日新增付费金额</th>
	                            <th>持续2日付费金额</th>
	                            <th>持续3日付费金额</th>
	                            <th>持续4日付费金额</th>
	                            <th>持续5日付费金额</th>
	                            <th>持续6日付费金额</th>
	                            <th>持续7日付费金额</th>
	                            <!-- <th>操作</th> -->
	                        </tr>
	                        </thead>
	                        <tbody>
	                         <?php if($data['data']): ?>
	                         	<?php foreach ($data['data'] as $v):?>
	                          	<tr>
	                          		<td><?php echo isset($v['count_time'])?$v['count_time']:0;?></td>
	                          		<td><?php echo isset($v['play_user'])?$v['play_user']:0;?></td>
	                          		<td><?php echo isset($v['new_user'])?$v['new_user']:0;?></td>
	                          		<?php if(isset($v['retain'])):foreach ($v['retain'] as $r):?>
	                          		  <td><?= html::encode($r)?></td>
	                          		<?php endforeach;else:?>
	                          		<td>0</td>
	                          		<td>0</td>
	                          		<td>0</td>
	                          		<td>0</td>
	                          		<td>0</td>
	                          		<td>0</td>
	                          		<?php endif;?>
	                          		<td><?php echo isset($v['pay_price'])?$v['pay_price']:0;?></td>
	                          		<?php if(isset($v['price'])):foreach ($v['price'] as $p):?>
	                          		  <td><?= html::encode($p)?></td>
	                          		<?php endforeach;else:?>
	                          		<td>0</td>
	                          		<td>0</td>
	                          		<td>0</td>
	                          		<td>0</td>
	                          		<td>0</td>
	                          		<td>0</td>
	                          		<?php endif;?>
	                          	</tr>
	                          <?php endforeach; ?>
	                          <?php else: ?>
	                    	<tr><td colspan="15" align="center">暂时没有数据！</td></tr>
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
			window.location.href='/continueorder/index.html?pid='+pid;
		}
	});
	var ppid = "<?php echo $pid ?>";
	$("#pid option[value='"+ppid+"']").attr('selected',true);
</script>