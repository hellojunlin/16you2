<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="page-heading">
    <h3>
                 权限管理
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">权限管理</a>
        </li>
        <li class="active">管理员管理</li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	<?php echo yii::$app->session['title']; ?>
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">
	                    <div class="btn-group">
	                     <?php if(YII::$app->session['role']!='-1'):?> 
			               <?php foreach (yii::$app->session['mdata'] as $mdata):?>
					          <?php if($mdata['child']=='manage/add'):?>
		                        <button class="btn btn-primary" onclick="window.location.href='<?=Url::to(['add'])?>'"><i class="fa  fa-plus"></i> 添加管理员 </button>&nbsp;
		                  	  <?php endif;?>
						    <?php endforeach;?>
						   <?php else :?>
					             <button class="btn btn-primary" onclick="window.location.href='<?=Url::to(['add'])?>'"><i class="fa  fa-plus"></i> 添加管理员 </button>&nbsp;
		                 <?php endif;?>
	                    </div>
	                    <div class="btn-group pull-right">
	                    	<div class="form-group">
	                    		<form action="">
		                            <button class="btn btn-primary" type="submit">搜索</button>
		                            <div class="col-sm-9" style="padding:0px 1px">
		                                <input type="text" class="form-control tooltips"   title="" placeholder="请输入管理员账号..."  name="value" maxlength="16" value='<?php echo $search;?>' />
		                            </div>
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
	                            <th>ID</th>
	                            <th>用户名</th>
	                            <th>头像</th>
	                            <th class="numeric">状态</th>
	                            <th class="numeric">角色</th>
	                            <th class="numeric">更新时间</th>
	                            <th class="numeric">操作</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
		                            <td data-title="Code"><?= Html::encode($v['id']) ?></td>
		                            <td data-title="Company"><?= Html::encode($v['username']) ?></td>
		                            <td class="center "><img src="/media/images/head_img/<?= Html::encode($v['head_img']); ?>" width='80px'></td>
		                            <td class="numeric" data-title="Open">
		                            	<?php if($v['state']==1): ?>
										<span class="label label-sm label-success">启用</span>
										<?php else: ?>
										<span class="label label-warning label-mini">禁用</span>
										<?php endif; ?>
		                            </td>
		                            <td class="numeric" data-title="Open"><?= ($v['role']=='-1')?'超级管理员':$v['role']; ?></td>
		                            <td class="numeric" data-title="High"><?= Html::encode(date('Y-m-d H:i:s',$v['updated_at'])) ?></td>
		                            <td class="numeric" data-title="Low">
		                            <?php if(YII::$app->session['role']!='-1'):?> 
		                         	   <?php foreach (yii::$app->session['mdata'] as $mdata):?>
					         			 <?php if($mdata['child']=='manage/state'):?>
			                            	<?php if($v['state']==1): ?>
												<button class="btn btn-xs btn-warning" name="<?php echo $v['state']; ?>" value="<?php echo $v['id']; ?>" id="button_state"><i class="fa fa-times"></i>&nbsp;禁 用
												</button>
											<?php else: ?>
												<button class="btn btn-success btn-xs" type="button" name="<?php echo $v['state']; ?>" value="<?php echo $v['id']; ?>" id="button_state"><i class="fa fa-check"></i>&nbsp;启 用</button>
											<?php endif; ?>
										  <?php endif;?>
										<?php if($mdata['child']=='manage/edit'):?>
	                                    	<button class="btn btn-info btn-xs" onclick="window.location.href='/manage/edit.html?id=<?php echo $v['id']; ?>'"><i class="fa fa-edit"></i> 编 辑</button>
	                                    <?php endif; ?>
	                                    <?php if($mdata['child']=='manage/delete'):?>
	                                    	<button class="btn btn-danger btn-xs" onclick="del(<?php echo $v['id']; ?> )"><i class="fa fa-trash-o"></i> 删 除</button>
		                                <?php endif; ?>
		                              <?php endforeach;?> 
		                            <?php else:?>
		                                <?php if($v['state']==1): ?>
											<button class="btn btn-xs btn-warning" name="<?php echo $v['state']; ?>" value="<?php echo $v['id']; ?>" id="button_state"><i class="fa fa-times"></i>&nbsp;禁 用
											</button>
										<?php else: ?>
											<button class="btn btn-success btn-xs" type="button" name="<?php echo $v['state']; ?>" value="<?php echo $v['id']; ?>" id="button_state"><i class="fa fa-check"></i>&nbsp;启 用</button>
										<?php endif; ?>
	                                    <button class="btn btn-info btn-xs" onclick="window.location.href='/manage/edit.html?id=<?php echo $v['id']; ?>'"><i class="fa fa-edit"></i> 编 辑</button>
	                                    <button class="btn btn-danger btn-xs" onclick="del(<?php echo $v['id']; ?> )"><i class="fa fa-trash-o"></i> 删 除</button>
		                            <?php endif;?>
		                            </td>
		                        </tr>
	                        	<?php endforeach;?>
	                        
	                    	<?php else: ?>
	                    	暂时没有数据
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
	$('body').on('click','#button_state',function(){
		var state = $(this).attr('name');
		var id = $(this).val();
		$.ajax({
	        url:"/manage/state.html",
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
	            	alert(data.info);
	            }
	        }
	    })
	})

	//删除管理员
	function del(id){
	    if(confirm('确认要删除吗？')){
	      	$.ajax({
	          	url:"/manage/delete.html",
	          	type:'post',
	          	data:{'id':id},
	          	success:function(data){
	          		if(data==1){
	              		window.location.reload();
	          		}else{
	          			alert('删除失败');
	          		}
	          	}
	     	}) 
	    }
	}

</script>