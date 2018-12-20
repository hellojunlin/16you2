<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="page-heading">
    <h3>
        分成比例设置
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">分成比例设置</a>
        </li>
        <li class="active">重置分成 </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	分成比例设置
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
		            <div class="clearfix">
		            	<div class="btn-group">
		            		<button class="btn btn-primary" onclick="window.location.href='<?=Url::to(['toadd'])?>'"><i class="fa fa-plus"></i> 添加游戏分成 </button>&nbsp;
		            	</div>
	                    <div class="btn-group pull-right">
	                    	<div class="form-group">
	                    		<form action="">
                                    <div class="col-lg-4" style="padding:0px">
                                        <select class="form-control m-bot15" name="keyword" id="keyword">
                                            <!-- <option value="name">游戏名称</option> -->
                                            <option value="C.compname">公司名称</option>
                                        </select>
                                    </div>
                                    <button class="btn btn-primary" type="submit">搜索</button>
                                    <div class="col-sm-6" style="padding:0px 1px">
                                        <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入关键字..." data-original-title="请输入关键字" name="value" maxlength="16" value="<?php echo $value; ?>">
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
	                            <th>游戏名称</th>
	                            <th>所属公司</th>
	                            <th>游戏方分成比例（%）</th>
	                            <th width='8%'>有效时间</th>
	                            <th>创建时间</th>
	                            <th>操作</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
		                            <td>
		                            	<?php if($v['gname']): ?>
		                            		<?php foreach ($v['gname'] as $key => $val) {
		                            			echo ' '.$val.'、 ';
		                            		} ?>
		                            	<?php else: ?>
		                            		暂时没有游戏
		                            	<?php endif; ?>
		                            </td>
		                            <td><?= Html::encode($v['compname']) ?></td>
		                            <td><?= Html::encode($v['proportion']) ?></td>
		                            <td><?= Html::encode(date('Y-m-d',$v['effective_time'])) ?></td>
		                            <td class="numeric" data-title="High"><?= Html::encode(date('Y-m-d H:i:s',$v['createtime'])) ?></td>
		                        	<td><button class="btn btn-info btn-xs" onclick="window.location.href='/setting/toedit.html?cid=<?php echo $v['cid']; ?>'"><i class="fa fa-edit"></i>&nbsp;编 辑</button>
		                                    <button class="btn btn-danger btn-xs" onclick="del(<?php echo $v['id']; ?> )"><i class="fa fa-trash-o"></i>&nbsp;删 除</button>
		                            </td>
		                        </tr>
	                        	<?php endforeach;?>
	                    	<?php else: ?>
	                    	<tr><td colspan="4">暂时没有数据！</td></tr>
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
	//删除
	function del(id){
	    layer.confirm('确认要删除吗？',function(){
	    	layer.msg('正在删除中。。。');
	    	$.ajax({ 
	          	url:"/setting/delete.html",
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