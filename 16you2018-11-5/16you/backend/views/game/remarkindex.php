<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<style>
	.cf th{
		white-space:nowrap;
	}
	.tooltip-inner{
	   max-width: none !important;
	}
</style>
<div class="page-heading">
    <h3>
              游戏管理
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">游戏记录</a>
        </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel panel-success">
	            <header class="panel-heading">
	               	游戏记录
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">
	                    <div class="btn-group pull-right">
	                    	<div class="form-group">
	                    		<form action="">
                                    <div class="col-lg-4" style="padding:0px">
                                        <select class="form-control m-bot15" name="selectval" id="selectval">
                                            <option value="gg.name" <?php echo ($select=='gg.name')?'selected':'';?>>游戏名称</option>
                                            <option value="gc.compname" <?php echo ($select=='gc.compname')?'selected':'';?>>所属公司</option>
                                        </select>
                                    </div>
                                    <button class="btn btn-primary" type="submit">搜索</button>
                                    <div class="col-sm-6" style="padding:0px 1px">
                                        <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入关键字..." data-original-title="请输入关键字" name="keyword" maxlength="16" value="<?php echo $value;?>">
                                    </div>
                                  </form>
	                        </div>
	                    </div>
	                </div>
	                <section id="no-more-tables" class="table-responsive">
	                	<?php if ($data['data']): ?> 
	                		<div class="span6">
	                			<div class="dataTables_info" id="hidden-table-info_info" style="margin-bottom:10px">显示 <?=Html::encode($data['start'])?> 到 <?= Html::encode($data['end'])?> ，共 <?= Html::encode($data['count'])?>  条</div>
	                		</div>
	                	<?php endif; ?>
	                    <table class="table table-bordered table-striped table-condensed cf">
	                        <thead class="cf">
	                        <tr>
	                            <th>ID</th>
	                            <th>游戏logo</th>
	                            <th>游戏名称</th>
	                            <th>所属公司</th>
	                            <th>备注</th>
	                            <th>操作</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
		                            <td><?= $k+1;?></td>
		                            <td><img src="/media/images/game/<?php echo ($v['head_img'])?$v['head_img']:'notset.png'?>" style="width:50px;height:50px;"></td>
		                            <td><?= Html::encode($v['name'])?></td>
		                            <td><a href="<?= url::to(['/company/index','value'=>$v['compname']]);?>"><?= Html::encode($v['compname'])?></a></td>
		                             <td><?php echo $v['remark']; ?></td>
		                            <td>
				                        <button class="btn btn-info btn-xs" onclick="window.location.href='/game/toedit/<?php echo $v['id']; ?>.html'"><i class="fa fa-edit"></i>&nbsp;查看详细</button>
			                        </td>
		                        </tr>
	                        	<?php endforeach;?>
	                        
	                    	<?php else: ?>
	                    	<tr><td colspan="20" style="text-align:center;">暂时没有数据！</td></tr>
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
<script src="/media/js/clipboard.min.js"></script>
<script>
	$('body').on('click','.statebtn',function(){
		var btntext = $(this).parent().prev().html('<i class="fa fa-spinner fa-pulse" style="width:34.44px;"></i>');
		var _this = $(this);
		var id = _this.attr('id');
		var state = _this.attr('name');
		$.ajax({
	        url:"/game/changestate.html",
	        type:'post',
	        dataType:'json',
	        data:{'state':state,'id':id},
	        success:function(data){
	        	_this.parent().prev().html('状态<span class="caret"></span>');
	            if(data.errorcode==0){
					if(state==0){//禁用
				    	//_this.removeClass('btn-warning').addClass('btn-success').attr('name','1').html('<i class="fa fa-check "></i>&nbsp;启 用');
						_this.parents('tr').find('.statval').html('<span class="label label-warning">禁用</span>');
					}else if(state==2){
						_this.parents('tr').find('.statval').html('<span class="label label-danger">已下架</span>');
				    }else{
						//_this.removeClass('btn-success').addClass('btn-warning').attr('name','0').html('<i class="fa fa-times"></i>&nbsp;禁 用');
						_this.parents('tr').find('.statval').html('<span class="label label-success">启用</span>');
					}
	            }else{
	            	alert(data.info);
	            }
	        }
	    }) 
	})

   //删除游戏
	$('body').on('click','.del',function(){
		var athis = $(this);
		 var id = $(this).attr('id');
		 if(confirm('确认要删除吗？')){
			 $.ajax({
		          url:"/game/del.html",
		          type:'post',
		          dataType:'json',
		          data:{'id':id},
		          success:function(data){
			          if(data.errorcode=='0'){
			        	  layer.msg(data.info, {icon: 1,time:2000});
			           	  athis.parent().parent('tr').remove();
				      }else if(data.errorcode=='1001'){
				    	  layer.msg(data.info, {icon: 1,time:2000});
					  }else{
						  layer.msg(data.info, {icon: 1,time:2000});
					  }
		        	 
		          }
		      }) 
		     }
	})
	
	 //复制链接
    var clipboard = new Clipboard('.btn-sm');
	clipboard.on('success', function(e) {
		alert("复制链接成功");
	    e.clearSelection();
	}).on('error', function(e) {
		alert("复制失败");
	});
</script>