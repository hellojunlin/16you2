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
              小游戏管理
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
	               	小游戏记录
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">
	                    <div class="btn-group">
	                     <?php if(YII::$app->session['role']!='-1'):?> 
			               <?php foreach (yii::$app->session['mdata'] as $mdata):?>
					          <?php if($mdata['child']=='game/toadd'):?>
		                        <button class="btn btn-primary" onclick="window.location.href='<?=Url::to(['toadd'])?>'"><i class="fa  fa-plus"></i> 添加游戏 </button>&nbsp;
		                  	  <?php endif;?>
						    <?php endforeach;?>
						   <?php else :?>
					            <button class="btn btn-primary" onclick="window.location.href='<?=Url::to(['toadd'])?>'"><i class="fa  fa-plus"></i> 添加游戏</button>&nbsp;
		                 <?php endif;?>
	                    </div>
	                    <div class="btn-group pull-right">
	                    	<div class="form-group">
	                    		<form action="">
                                    <div class="col-lg-4" style="padding:0px">
                                        <select class="form-control m-bot15" name="selectval" id="selectval">
                                            <option value="gg.name" <?php echo ($select=='gg.name')?'selected':'';?>>小游戏名称</option>
                                            <option value="gg.unique" <?php echo ($select=='gg.unique')?'selected':'';?>>唯一标识</option>
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
	                            <th>小游戏名称</th>
	                            <th>唯一标识</th>
	                            <th>游戏logo</th>
	                            <th>排序</th>
	                             <th>游戏人数</th>
	                            <th>开始链接</th>
	                            <th>状态</th>
	                            <th>创建时间</th>
	                            <th>操作</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
		                            <td><?= $k+1;?></td>
		                            <td><?= Html::encode($v['name'])?></td>
		                            <td><a title="" data-placement="top" data-toggle="tooltip" class="tooltips" href="" data-original-title="<?= Html::encode($v['unique'])?>">查看</a></td>
		                            <td><img src="/media/images/sgame/<?php echo ($v['head_img'])?$v['head_img']:'notset.png'?>" style="width:50px;height:50px;"></td>
		                            <td><?= $v['sort']?></td>
		                            <td><?= $v['gamenum']?></td>
		                            <td>
		                             <a  data-placement="top" data-toggle="tooltip" class="tooltips" href="####" title="<?php echo $v['game_url']?>">
	                            		 <button class="btn btn-info btn-sm" type="button"data-clipboard-text="<?php echo $v['game_url']?>">复制链接</button>
	                            	</a>
		                            <?php if($v['state']==0):?>
		                            <td class="statval"><label class="label label-warning">禁用</label></td>
		                            <?php else:?>
		                            <td class="statval"><label class="label label-success">启用</label></td>
		                            <?php endif;?>
		                            <td><?php echo date('Y-m-d',$v['createtime']); ?></td>
		                            <td>
			                             <?php if(YII::$app->session['role']!='-1'):?> 
			                         	   <?php foreach (yii::$app->session['mdata'] as $mdata):?>
			                         	    <?php if($mdata['child']=='sgame/changestate'):?>
					                            <?php if($v['state']==0):?>
					                            <button class="btn btn-success btn-xs statebtn" id="<?php echo $v['id']?>" name="1"><i class="fa fa-check"></i>&nbsp;启 用</button>
					                            <?php else:?>
					                             <button class="btn btn-warning btn-xs statebtn" id="<?php echo $v['id']?>" name="0"><i class="fa fa-times"></i>&nbsp;禁 用</button>
					                            <?php endif;?>
					                         <?php endif;?>
					                            <?php if($mdata['child']=='game/toedit'):?>
				                                <button class="btn btn-info btn-xs" onclick="window.location.href='/sgame/toedit/<?php echo $v['id']; ?>.html'"><i class="fa fa-edit"></i>&nbsp;查看详细</button>
												<?php endif;?>
												 <?php if($mdata['child']=='game/toedit'):?>
				                                <button class="btn btn-danger btn-xs del" id="<?php echo $v['id'];?>"><i class="fa fa-trash-o"></i>&nbsp;删  除</button>
                                                <?php endif;?>
                                        <?php endforeach;?>
                                        <?php else:?>
                                            <?php if($v['state']==0):?>
				                            <button class="btn btn-success btn-xs statebtn" id="<?php echo $v['id']?>" name="1"><i class="fa fa-check"></i>&nbsp;启 用</button>
				                            <?php else:?>
				                             <button class="btn btn-warning btn-xs statebtn" id="<?php echo $v['id']?>" name="0"><i class="fa fa-times"></i>&nbsp;禁 用</button>
				                            <?php endif;?>
				                            <button class="btn btn-info btn-xs" onclick="window.location.href='/sgame/toedit/<?php echo $v['id']; ?>.html'"><i class="fa fa-edit"></i>&nbsp;查看详细</button>
			                                <button class="btn btn-danger btn-xs del" id="<?php echo $v['id'];?>"><i class="fa fa-trash-o"></i>&nbsp;删  除</button>
                                        <?php endif;?>
			                        </td>
		                        </tr>
	                        	<?php endforeach;?>
	                        
	                    	<?php else: ?>
	                    	<tr><td colspan="19" align="center">暂时没有数据！</td></tr>
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
		var btntext = $(this).html('<i class="fa fa-spinner fa-pulse" style="width:51.44px;"></i>');
		var _this = $(this);
		var id = _this.attr('id');
		var state = _this.attr('name');
		$.ajax({
	        url:"/sgame/changestate.html",
	        type:'post',
	        dataType:'json',
	        data:{'state':state,'id':id},
	        success:function(data){
	            if(data.errorcode==0){
					if(state==0){//禁用
				    	_this.removeClass('btn-warning').addClass('btn-success').attr('name','1').html('<i class="fa fa-check "></i>&nbsp;启 用');
						_this.parents('tr').find('.statval').html('<span class="label label-warning">禁用</span>');
					}else{
						_this.removeClass('btn-success').addClass('btn-warning').attr('name','0').html('<i class="fa fa-times"></i>&nbsp;禁 用');
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
		          url:"/sgame/del.html",
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
		layer.msg("复制链接成功", {icon: 1,time:1000});
	    e.clearSelection();
	}).on('error', function(e) {
		layer.msg("复制链接失败", {icon: 1,time:1000});
	});
</script>