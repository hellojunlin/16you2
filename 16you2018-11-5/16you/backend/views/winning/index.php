<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<script type="text/javascript" src="/media/js/My97DatePicker/WdatePicker.js"></script>
<div class="page-heading">
    <h3>大转盘中奖管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">大转盘中奖记录</a>
        </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	大转盘中奖记录
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">
	                    <div class="btn-group pull-right">
	                    	<div class="form-group">
	                    		<form action="<?=Url::to(['index'])?>">
		                    			<label class="pull-left"><input type="text" onfocus="WdatePicker({ maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}' })" id="logmin" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="开始时间" value="<?php echo $starttime; ?>" name="starttime">
										</label>
			                           	<label class="pull-left"><input type="text" onfocus="WdatePicker({ minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d' })" id="logmax" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="结束时间"value="<?php echo $endtime; ?>" name="endtime"></label>
                                        <select class="col-md-2 form-control m-bot15" name="selectval" id="selectval" style="width: auto;">
                                            <option value="Unique_ID" <?php echo ($select=='Unique_ID')?'selected':'';?>>用户ID</option>
                                            <option value="username" <?php echo ($select=='username')?'selected':'';?>>用户名称</option>
                                            <option value="content" <?php echo ($select=='content')?'selected':'';?>>中奖内容</option>
                                        </select>
		                            <button class="btn btn-primary" type="submit">搜索</button>
                                    <div class="col-md-3" style="padding:0px 1px">
                                        <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入关键字..." data-original-title="请输入关键字" name="keyword" maxlength="16" value="<?php echo $value;?>">
                                    </div>
	                            </form>
	                        </div>
	                    </div>
	                </div>
	                <section id="no-more-tables">
	                	<div style="color:red;font-weight:bold">中奖红包总金额：<?php echo $price;?> 元</div>
	                	<?php if ($data['data']):$data['data'] ?> 
	                		<div class="span6">
	                			<div class="dataTables_info" id="hidden-table-info_info" style="margin-bottom:10px">显示 <?=Html::encode($data['start'])?> 到 <?= Html::encode($data['end'])?> ，共 <?= Html::encode($data['count'])?>  条</div>
	                		</div>
	                	<?php endif; ?>
	                    <table class="table table-bordered table-striped table-condensed cf">
	                        <thead class="cf">
	                        <tr>
	                            <th>编号</th>
	                            <th>中将用户名称</th>
	                            <th>中将内容</th>
	                            <th>奖品状态</th>
	                            <th>创建时间</th>
	                            <th>操作</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
		                            <td><?= Html::encode($v['id'])?></td>
		                            <td><?= Html::encode($v['username'])?></td>
		                            <td>
										<?php 
		                            		if($v['content']=='0'){
			                            		echo '恭';
			                            	}elseif($v['content']=='1'){
			                            		echo '喜';
			                            	}elseif($v['content']=='2'){
			                            		echo '发';
			                            	}elseif($v['content']=='3'){
			                            		echo '财';
			                            	}else{
			                            		echo $v['content'];
			                            	}
		                            	?></td>
		                            <td>
		                            	<?php 
		                            		if($v['type']==1){
			                            		echo '<span class="label label-warning">未兑换</span>';
			                            	}elseif($v['type']==2){
			                            		echo '<span class="label label-success">红包发送成功</span>';
			                            	}elseif($v['type']==3){
			                            		echo '<span class="label label-danger">红包发送失败</span>';
			                            	}elseif($v['type']==5){
			                            		echo '<span class="label label-warning">补发失败</span>';
			                            	}
		                            	?></td>
		                            <td><?= Html::encode(date('Y-m-d H:i:s',$v['createtime']))?></td>
		                            <td> 
		                            	<?php if($v['type']==3):?>
		                            	<button class="btn btn-info btn-xs rinfo" id="<?php echo $v['id'];?>"><i class="fa fa-asterisk"></i>&nbsp;补发红包</button>
		                            	<?php endif; ?>
	                                    <button class="btn btn-danger btn-xs del" id="<?php echo $v['id'];?>"><i class="fa fa-trash-o"></i>&nbsp;删  除</button>
		                            </td>
		                        </tr>
	                        	<?php endforeach;?>
	                        
	                    	<?php else: ?>
	                    	<tr><td colspan="7"  align="center">暂时没有数据！</td></tr>
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
<script>
   //删除中奖
	$('.del').click(function(){
		var uindex = layer.load();
		var athis = $(this);
		var id = $(this).attr('id');
		if(confirm('确认要删除吗？')){
			$.ajax({
	          	url:"/winning/del.html",
	          	type:'post',
	          	dataType:'json',
	          	data:{'id':id},
	          	success:function(data){
		          	if(data.errorcode==0){
		        	  	window.location.reload();
			      	}else{
					  	layer.msg(data.info, {icon: 1,time:2000});
				  	}
	        	 	layer.close(uindex);
	          	}
		    }) 
		}else{
			layer.close(uindex);
		}
	})

	//删除中奖
	$('.rinfo').click(function(){
		var uindex = layer.load();
		var athis = $(this);
		var id = $(this).attr('id');
		if(confirm('确认要补发吗？')){
			$.ajax({
	          	url:"/winning/reissuered.html",
	          	type:'post',
	          	dataType:'json',
	          	data:{'id':id},
	          	success:function(data){
		          	if(data.errorcode==0){
						layer.msg(data.info, {icon: 1,time:2000});
		        	  	athis.parents('tr').children().eq(3).html('<span class="label label-success">红包发送成功</span>');
						athis.remove();
			      	}else{
			      		athis.parents('tr').children().eq(3).html('<span class="label label-warning">补发失败</span>');
						athis.remove();
					  	layer.msg(data.info, {icon: 2,time:2000});
				  	}
	          	}
		    }) 
		}
		layer.close(uindex);
	})
</script>