<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="page-heading">
    <h3>
        公司管理
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">公司管理</a>
        </li>
        <li class="active">公司列表 </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	公司管理
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">
	            	 <?php if(YII::$app->session['role']!='-1'):?> 
			               <?php foreach (yii::$app->session['mdata'] as $mdata):?>
					          <?php if($mdata['child']=='company/add'):?>
				                <button class="btn btn-primary" onclick="window.location.href='/company/add.html'"><i class="fa  fa-plus"></i> 添加公司 </button>&nbsp;
				                <?php endif;?>
	                       <?php endforeach;?>
	                 <?php else:?>
	                  			<button class="btn btn-primary" onclick="window.location.href='/company/add.html'"><i class="fa  fa-plus"></i> 添加公司 </button>&nbsp;
	                 <?php endif;?>    
	                  <?php if(YII::$app->session['role']=='-1'):?>     
	                    <div class="btn-group pull-right">
	                    	<div class="form-group">
	                    		<form action="<?=Url::to(['index'])?>">
		                            <button class="btn btn-primary" type="submit">搜索</button>
		                            <div class="col-sm-9">
		                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入公司名..." data-original-title="请输入公司名" name="value" maxlength="50" value='<?php echo $value; ?>'/>
		                            </div>
	                            </form>
	                        </div>
	                    </div>
	                    <?php endif;?>
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
	                            <th>公司名</th>
	                            <th>联系人</th>
	                            <th class="numeric">手机号码</th>
	                            <th class="numeric">角色</th>
	                            <th class="numeric">最后修改时间</th>
	                            <th class="numeric">操作</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
		                            <td data-title="Code"><?= Html::encode($v['id']) ?></td>
		                            <td><?= Html::encode($v['compname']) ?></td>
		                            <td><?= Html::encode($v['linkman']) ?></td>
		                            <td class="numeric" data-title="Open"><?= Html::encode($v['phone']) ?></td>
		                            <td class="numeric" data-title="Open"><?php if($v['role']!=-1){echo $v['role'];}else{echo '超级管理员';} ?></td>
		                            <td class="numeric" data-title="High"><?= Html::encode(date('Y-m-d H:i:s',$v['createtime'])) ?></td>
		                            <td class="numeric" data-title="Low">
		                             <?php if(YII::$app->session['role']!='-1'):?> 
		                         	   <?php foreach (yii::$app->session['mdata'] as $mdata):?>
										     <?php if($mdata['child']=='company/edit'):?>
			                                    <button class="btn btn-info btn-xs" onclick="window.location.href='/company/edit/<?php echo $v['id']; ?>.html'"><i class="fa fa-eye"></i> 查看资料</button>
			                               <?php endif;?>
			                               <?php if($mdata['child']=='company/delete'):?>
			                                    <button class="btn btn-danger btn-xs" onclick="del('<?php echo $v['id']; ?>')"><i class="fa fa-trash-o"></i> 删 除</button>
			                               <?php endif;?>
			                            <?php endforeach;?>
	                                  <?php else:?>
		                                    <button class="btn btn-info btn-xs" onclick="window.location.href='/company/edit/<?php echo $v['id']; ?>.html'"><i class="fa fa-eye"></i> 查看资料</button>
		                                    <button class="btn btn-danger btn-xs" onclick="del('<?php echo $v['id']; ?>')"><i class="fa fa-trash-o"></i> 删 除</button>
	                                  <?php endif;?>
		                            </td>
		                        </tr>
	                        	<?php endforeach;?>
	                    	<?php else: ?>
	                    	<tr><td colspan="11">暂时没有数据！</td></tr>
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
	//删除公司
	function del(id){
	    layer.confirm('确认要删除吗？',function(){
	    	layer.msg('正在删除中。。。');
	    	$.ajax({ 
	          	url:"/company/delete.html",
	          	type:'post',
	          	data:{'id':id},
	          	success:function(data){
	          		if(data==1){
	          			layer.msg('删除成功',{icon:6,time:1000});
	              		setInterval(window.location.reload(),900);
	          		}else{
	          			alert('删除失败');
	          		}
	          	}
	     	}) 
	    });
	}

</script>