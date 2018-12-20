<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<script type="text/javascript" src="/media/js/My97DatePicker/WdatePicker.js"></script>
<div class="page-heading">
    <h3>订单管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">退款记录</a>
        </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">退款记录
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">
	                    <div class="btn-group pull-right">
	                    	<div class="form-group">
	                    		<form action="">
	                    			<label><input type="text" onfocus="WdatePicker({ maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}' })" id="logmin" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="开始时间" value="<?php echo $start_time; ?>" name="start_time">
									</label>
		                           	<label><input type="text" onfocus="WdatePicker({ minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d' })" id="logmax" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="结束时间"value="<?php echo $end_time; ?>" name="end_time"></label>
                                    <label class="pull-right">
                                    <button class="btn btn-primary" type="submit">搜索</button>
                                    <div class="col-sm-4" style="padding:0px 1px">
                                        <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入用户ID..." data-original-title="请输入用户ID" name="uniqueid" maxlength="16" value="<?php echo $uniqueid; ?>">
                                    </div>
                                    <div class="col-sm-4" style="padding:0px 1px">
                                        <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入用户名称..." data-original-title="请输入用户名称" name="value" maxlength="16" value="<?php echo $value; ?>">
                                    </div>
                                    </label>
                                </form>
	                        </div>
	                    </div>
	                </div>
	                <section id="no-more-tables">
	                	<?php if ($data['data']): ?> 
	                		<div class="span6">
	                			<div class="dataTables_info" id="hidden-table-info_info" style="margin-bottom:10px">显示 <?=Html::encode($data['start'])?> 到 <?= Html::encode($data['end'])?> ，共 <?= Html::encode($data['count'])?>  条</div>
	                		</div>
	                	<?php endif; ?>
	                    <table class="table table-bordered table-striped table-condensed cf">
	                        <thead class="cf">
	                        <tr>
	                        	<th>游戏名称</th>
	                            <th>用户ID</th>
	                            <th>用户名称</th>
	                            <th>订单号</th>
	                            <th>退款金额</th>
	                            <th>退款时间</th>
	                            <th>操作</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
		                            <td><?= Html::encode($v['gamename'])?></td>
		                            <td><?= Html::encode($v['Unique_ID'])?></td>
		                            <td><?= Html::encode($v['username'])?></td>
		                            <td class="statval"><?php echo $v['transaction_id']; ?></td>
		                            <td class="statval"><?php echo $v['price']; ?></td>
		                            <td><?php echo date('Y-m-d H:i:s',$v['refund_time']); ?></td>
		                            <td>
		                            	<?php if(YII::$app->session['role']!='-1'):?> 
		                         	    <?php foreach (yii::$app->session['mdata'] as $mdata):?>
					         			<?php if($mdata['child']=='order/toedit'):?>
					         				<button class="btn btn-info btn-xs" onclick="window.location.href='/order/toedit.html?id=<?php echo $v['id']; ?>'"><i class="fa fa-edit"></i>&nbsp;详 细</button>
					         			<?php endif; ?>
					         			<?php endforeach; ?>
					         			<?php else: ?>
			                                <button class="btn btn-info btn-xs" onclick="window.location.href='/order/refunddetail.html?id=<?php echo $v['id']; ?>'"><i class="fa fa-edit"></i>&nbsp;详 细</button>
					         			<?php endif; ?>
		                            </td>
		                        </tr>
	                        	<?php endforeach;?>
	                        
	                    	<?php else: ?>
	                    	<tr><td colspan="7" align="center">暂时没有数据</td></tr>
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
<script>
	var keyword = "<?php echo $keyword; ?>";
	$("#keyword option[value='"+keyword+"']").attr('selected',true);
	//删除管理员
	function del(id){
	    layer.confirm('确认要删除吗？',function(){
	    	layer.msg('正在删除中。。。');
	    	$.ajax({ 
	          	url:"/order/delete.html",
	          	type:'post',
	          	data:{'id':id},
	          	success:function(data){
	          		if(data==1){
	          			layer.msg('删除成功',{icon:6,time:2000});
	              		setInterval(window.location.reload(),900);
	          		}else{
	          			layer.msg('删除失败',{icon:5,time:2000});
	          		}
	          	}
	     	}) 
	    });
	}
</script>