<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<style>
	th{
		white-space:nowrap;
	}
</style>
<div class="page-heading">
    <h3>资讯管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">资讯记录</a>
        </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">资讯记录
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">
	            		<div class="btn-group">
					        <button class="btn btn-primary" onclick="window.location.href='<?=Url::to(['add'])?>'"><i class="fa  fa-plus"></i> 添加资讯 </button>&nbsp;
	                    </div>
	                    <div class="btn-group pull-right">
	                    	<div class="form-group">
	                    		<form action="">
                                    <button class="btn btn-primary" type="submit">搜索</button>
                                    <div class="col-md-8" style="padding:0px 1px">
                                        <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入标题..." data-original-title="请输入标题" name="value" maxlength="16" value="<?php echo $value; ?>">
                                    </div>
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
	                            <th>ID</th>
	                            <th>所属游戏</th>
	                            <th>标题</th>
	                            <th>标签</th>
	                            <th>是否置顶</th>
	                            <th>排序</th>
	                            <th>状态</th>
	                            <th>最后修改时间</th>
	                            <th>操作</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
		                            <td><?= Html::encode($v['id'])?></td>
		                            <td><?= Html::encode($v['name'])?></td>
		                            <td><?= Html::encode($v['title'])?></td>
		                            <td><?= Html::encode($v['label'])?></td>
		                            <td><?php echo ($v['type']==0)?'否':'是';?></td>
		                            <td><?= Html::encode($v['sort'])?></td>
		                            <td class="numeric" data-title="Open">
		                            	<?php if($v['state']==1): ?>
										<span class="label label-sm label-success">启用</span>
										<?php else: ?>
										<span class="label label-warning label-mini">禁用</span>
										<?php endif; ?>
		                            </td>
		                            <td><?php echo date('Y-m-d H:i:s',$v['createtime']); ?></td>
		                            <td>
		                            	<?php if($v['state']==1): ?>
											<button class="btn btn-xs btn-warning" name="<?php echo $v['state']; ?>" value="<?php echo $v['id']; ?>" id="button_state"><i class="fa fa-times"></i>&nbsp;禁 用
											</button>
										<?php else: ?>
											<button class="btn btn-success btn-xs" type="button" name="<?php echo $v['state']; ?>" value="<?php echo $v['id']; ?>" id="button_state"><i class="fa fa-check"></i>&nbsp;启 用</button>
										<?php endif; ?>
		                                <button class="btn btn-info btn-xs" onclick="window.location.href='/consult/edit/<?php echo $v['id']; ?>.html'"><i class="fa fa-edit"></i>&nbsp;详 细</button>
	                                    <button class="btn btn-danger btn-xs" onclick="del(<?php echo $v['id']; ?> )"><i class="fa fa-trash-o"></i>&nbsp;删 除</button>
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
	//删除
	function del(id){
	    layer.confirm('确认要删除吗？',function(){
	    	layer.msg('正在删除中。。。');
	    	$.ajax({ 
	          	url:"/consult/delete.html",
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

	$('body').on('click','#button_state',function(){
		var state = $(this).attr('name');
		var id = $(this).val();
		$.ajax({
	        url:"/consult/state.html",
	        type:'post',
	        dataType:'json',
	        data:{'state':state,'id':id},
	        success:function(data){
	            if(data.errorcode==0){
	            	if(state==0){
	            		$("button[value="+id+"]").removeClass('btn-success').addClass('btn-warning').attr('name','1').html('<i class="fa fa-times"></i>&nbsp;禁 用');
	            		$("button[value="+id+"]").parents('tr').children().eq(3).html('<span class="label label-sm label-success">启用</span>');
	            	}else{
	            		$("button[value="+id+"]").removeClass('btn-warning').addClass('btn-success').attr('name','0').html('<i class="fa fa-check"></i>&nbsp;启 用');
	            		$("button[value="+id+"]").parents('tr').children().eq(3).html('<span class="label label-sm label-warning">禁用</span>');
	            	}
	            }else{
	            	layer.msg(data.info);
	            }
	        }
	    })
	})
</script>