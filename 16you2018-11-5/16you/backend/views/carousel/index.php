<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<style>
	tr>td>img {width: 140px;}
</style>
<div class="page-heading">
    <h3>
        轮播管理
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">轮播管理</a>
        </li>
        <li class="active">轮播列表 </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	轮播管理
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">
	            	 <?php if(YII::$app->session['role']!='-1'):?> 
			               <?php foreach (yii::$app->session['mdata'] as $mdata):?>
					          <?php if($mdata['child']=='carousel/add'):?>
				                <button class="btn btn-primary" onclick="window.location.href='/carousel/add.html'"><i class="fa  fa-plus"></i> 添加轮播 </button>&nbsp;
				                <?php endif;?>
	                       <?php endforeach;?>
	                 <?php else:?>
	                  			<button class="btn btn-primary" onclick="window.location.href='/carousel/add.html'"><i class="fa  fa-plus"></i> 添加轮播 </button>&nbsp;
	                 <?php endif;?>        
	                    <div class="btn-group pull-right">
	                    	<div class="form-group">
	                    		<form action="<?=Url::to(['index'])?>">
		                            <button class="btn btn-primary" type="submit">搜索</button>
		                            <div class="col-sm-9">
		                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入轮播链接..." data-original-title="请输入轮播链接" name="value" maxlength="50" value='<?php echo $value; ?>'/>
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
	                            <th>轮播分类</th>
	                            <th>图片</th>
	                            <th>链接</th>
	                            <th>排序</th>
	                            <th class="numeric">最后修改时间</th>
	                            <th>备注</th>
	                            <th class="numeric">操作</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
		                            <td data-title="Code"><?= Html::encode($v['id']) ?></td>
		                            <td>
		                            	<?php switch ($v['state']) {
		                            	case '1':
		                            		echo '<label class="label label-info"><i class="fa fa-book"></i> 首页轮播</label>';
		                            		break;
		                            	case '2':
		                            		echo '<label class="label label-warning"><i class="fa fa-shopping-cart"></i> 商城轮播</label>';
		                            		break;
		                            	case '3':
		                            		echo '<label class="label label-danger"><i class="fa fa-key"></i> 已弃用</label>';
		                            		break;
		                            	}?>
		                            </td>
		                            <td><img src="/media/images/carousel/<?php echo $v['image']; ?>" alt=""></td>
		                            <td><?= Html::encode($v['url']) ?></td>
		                            <td><?= Html::encode($v['sort']) ?></td>
		                            <td class="numeric" data-title="High"><?= Html::encode(date('Y-m-d H:i:s',$v['createtime'])) ?></td>
		                            <td class="numeric" data-title="High"><?= Html::encode($v['remark']) ?></td>
		                            <td class="numeric" data-title="Low">
		                             <?php if(YII::$app->session['role']!='-1'):?> 
		                         	   <?php foreach (yii::$app->session['mdata'] as $mdata):?>
										     <?php if($mdata['child']=='carousel/edit'):?>
			                                    <button class="btn btn-info btn-xs" onclick="window.location.href='/carousel/edit/<?php echo $v['id']; ?>.html'"><i class="fa fa-eye"></i> 编辑</button>
			                               <?php endif;?>
			                               <?php if($mdata['child']=='carousel/delete'):?>
			                                    <button class="btn btn-danger btn-xs" onclick="del('<?php echo $v['id']; ?>')"><i class="fa fa-trash-o"></i> 删 除</button>
			                               <?php endif;?>
			                            <?php endforeach;?>
	                                  <?php else:?>
		                                    <button class="btn btn-info btn-xs" onclick="window.location.href='/carousel/edit/<?php echo $v['id']; ?>.html'"><i class="fa fa-eye"></i> 编辑</button>
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
	//删除轮播
	function del(id){
	    layer.confirm('确认要删除吗？',function(){
	    	layer.msg('正在删除中。。。');
	    	$.ajax({ 
	          	url:"/carousel/delete.html",
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