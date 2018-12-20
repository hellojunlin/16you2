<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="page-heading">
    <h3>商品管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">商品记录</a>
        </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">商品记录
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">
	            	<button class="btn btn-primary" onclick="window.location.href='<?=Url::to(['toadd'])?>'"><i class="fa fa-plus"></i> 添加商品</button>&nbsp;
	                    <div class="btn-group pull-right">
	                    	<div class="form-group">
	                    		<form action="">
                                    <button class="btn btn-primary" type="submit">搜索</button>
		                            <div class="col-sm-9">
		                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入商品名称..." data-original-title="请输入商品名称" name="value" maxlength="100" value='<?php echo $value; ?>'/>
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
	                            <th>商品名称</th>
	                            <th>商品图</th>
	                            <th>兑换所需积分</th>
	                            <th>剩余数量</th>
	                            <th>排序</th>
	                            <th>状态</th>
	                            <th>创建时间</th>
	                            <th>操作</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
		                            <td><?= Html::encode($v['id'])?></td>
		                            <td><?= Html::encode($v['product_name'])?></td>
		                            <td><img src="/media/images/product/<?= Html::encode($v['image_url'])?>" alt="" style="width:60px"></td>
		                            <td><?= Html::encode($v['integral'])?></td>
		                            <td><?php echo $v['number']; ?></td>
		                            <td><?php echo $v['sort'];?></td>
		                            <?php if($v['state']==0):?>
		                            <td class="statval"><label class="label label-warning">禁用</label></td>
		                            <?php else:?>
		                            <td class="statval"><label class="label label-success">启用</label></td>
		                            <?php endif;?>
		                            <td><?php echo date('Y-m-d H:i:s',$v['createtime']); ?></td>
		                            <td>
		                            	<?php if($v['state']==0):?>
			                            <button class="btn btn-success btn-xs statebtn" id="<?php echo $v['id']?>" name="1"><i class="fa fa-check"></i>&nbsp;启 用</button>
			                            <?php else:?>
			                             <button class="btn btn-warning btn-xs statebtn" id="<?php echo $v['id']?>" name="0"><i class="fa fa-times"></i>&nbsp;禁 用</button>
			                         	<?php endif; ?>
		                            	<button class="btn btn-info btn-xs" onclick="window.location.href='/product/toedit.html?id=<?php echo $v['id']; ?>'"><i class="fa fa-edit"></i>&nbsp;编 辑</button>
		                                <button class="btn btn-danger btn-xs" onclick="del(<?php echo $v['id']; ?> )"><i class="fa fa-trash-o"></i>&nbsp;删 除</button>
		                            </td>
		                        </tr>
	                        	<?php endforeach;?>
	                        
	                    	<?php else: ?>
	                    	<tr><td colspan="20" align="center">暂时没有数据</td></tr>
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
		$('body').on('click','.statebtn',function(){
		var _this = $(this);
		var id = _this.attr('id');
		var state = _this.attr('name');
		$.ajax({
	        url:"/product/changestate.html",
	        type:'post',
	        dataType:'json',
	        data:{'state':state,'id':id},
	        success:function(data){
	            if(data.errorcode==0){
					if(state==0){//禁用
				    	_this.removeClass('btn-warning').addClass('btn-success').attr('name','1').html('<i class="fa fa-check"></i>&nbsp;启 用');
						_this.parents('tr').find('.statval').html('<span class="label label-warning">禁用</span>');
					}else{
						_this.removeClass('btn-success').addClass('btn-warning').attr('name','0').html('<i class="fa fa-times"></i>&nbsp; 禁 用');
						_this.parents('tr').find('.statval').html('<span class="label label-success">启用</span>');
					}
	            }else{
	            	alert(data.info);
	            }
	        }
	    }) 
	})
	//删除
	function del(id){
	    layer.confirm('确认要删除吗？',function(){
	    	layer.msg('正在删除中。。。');
	    	$.ajax({ 
	          	url:"/product/delete.html",
	          	type:'post',
	          	data:{'id':id},
	          	success:function(data){
	          		if(data==1){
	          			layer.msg('删除成功',{icon:6,time:2000});
	              		setInterval(window.location.reload(),1000);
	          		}else{
	          			layer.msg('删除失败',{icon:5,time:2000});
	          		}
	          	}
	     	}) 
	    });
	}
</script>