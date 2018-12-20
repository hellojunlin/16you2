<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<style>
td {
    word-wrap: break-word;
    word-break: break-all;
}
</style>
<div class="page-heading">
    <h3>
              平台管理
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">平台记录</a>
        </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	平台记录
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">
	                    <div class="btn-group">
	                     <?php if(YII::$app->session['role']!='-1'):?> 
			               <?php foreach (yii::$app->session['mdata'] as $mdata):?>
					          <?php if($mdata['child']=='platform/toadd'):?>
		                        <button class="btn btn-primary" onclick="window.location.href='<?=Url::to(['toadd'])?>'"><i class="fa  fa-plus"></i> 添加平台 </button>&nbsp;
		                  	  <?php endif;?>
						    <?php endforeach;?>
						   <?php else :?>
					            <button class="btn btn-primary" onclick="window.location.href='<?=Url::to(['toadd'])?>'"><i class="fa  fa-plus"></i> 添加平台</button>&nbsp;
		                 <?php endif;?>
	                    </div>
	                    <?php if($managemodel->role==-1):?>
	                    <div class="btn-group pull-right">
	                    	<div class="form-group">
	                    		<form action="">
                                    <div class="col-lg-4" style="padding:0px">
                                        <select class="form-control m-bot15" name="selectval" id="selectval">
                                            <option value="gp.pname" <?php echo ($select=='gp.pname')?'selected':'';?>>平台名称</option>
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
	                        	<th style="display: none;">id</th>
	                            <th>平台名称</th>
	                            <th>标识id</th>
	                            <th class="numeric">所属公司</th>
	                            <th class="numeric">状态</th>
	                            <th>平台链接</th>
	                            <th>备注</th>
	                            <th>广告图片</th>
	                            <th style="display: none;">公众号图片</th>
	                            <th class="numeric">创建时间</th>
	                            <th class="numeric">操作</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
									<td style="display: none;"><?= Html::encode($v['id']);?></td>
		                            <td><?= Html::encode($v['pname']);?></td>
		                            <td><?= Html::encode($v['punid']);?></td>
		                            <td>
		                             <?php if(YII::$app->session['role']=='-1'):?> 
		                            	<a href="<?= url::to(['/company/index','value'=>$v['compname']]);?>"><?= Html::encode($v['compname']);?></a>
		                            <?php else:?>
		                            	<?= Html::encode($v['compname']);?>
		                            <?php endif;?>
		                            </td>
		                            <td class="statval">
		                            <?php if($v['state']==0):?>
		                              <label class="label label-warning">禁用</label>
		                            <?php else:?>
		                              <label class="label label-success">启用</label>
		                            <?php endif;?> 
		                            </td>
		                            <td>
		                               <a  data-placement="top" data-toggle="tooltip" class="tooltips" href="####" title="<?php echo yii::$app->params['frontend'];?>/index/index!<?php echo $v['punid'];?>.html">
	                            			 <button class="btn btn-info btn-sm" type="button"data-clipboard-text="<?php echo yii::$app->params['frontend'];?>/index/index!<?php echo $v['punid'];?>.html">复制链接</button>
	                            		</a>
		                           </td>
		                            <td><?= Html::encode($v['remark']);?></td>
		                            <td>
		                            	<?php if($v['start_img']): ?>
		                            	<img src="/media/images/plateform/<?php echo $v['start_img']; ?>" alt="图片" width="50"/>
		                            	<?php else: ?>
		                            		未上传
		                            	<?php endif; ?>
		                            </td>
		                            <td style="display: none;"><?php if($v['code_img']): ?>
		                            	<img src="/media/images/plateform/<?php echo $v['code_img']; ?>" alt="图片" width="50"/>
		                            	<?php else: ?>
		                            		未上传
		                            	<?php endif; ?></td>
		                            <td><?= Html::encode(date('Y-m-d',$v['createtime']));?></td>
		                            <td>
		                                <?php if(YII::$app->session['role']!='-1'):?> 
			                         	    <?php foreach (yii::$app->session['mdata'] as $mdata):?>
						         			<?php if($mdata['child']=='platform/changestate'):?>
			                                <?php if($v['state']==0):?>
			                                     <button class="btn btn-success btn-xs statebtn" id="<?php echo $v['id']?>" name="1"><i class="fa fa-check"></i>&nbsp;启 用</button>
			                                <?php else:?>
			                                	 <button class="btn btn-warning btn-xs statebtn" id="<?php echo $v['id']?>" name="0"><i class="fa fa-times"></i>&nbsp;禁 用</button>
			                            	<?php endif;?>
			                            	<?php endif;?>
			                            	<?php if($mdata['child']=='platform/toedit'):?>
			                                <button class="btn btn-info btn-xs" onclick="window.location.href='/platform/toedit/<?php echo $v['id']?>.html'"><i class="fa fa-edit"></i>&nbsp;编 辑</button>
			                            	<?php endif;?>
			                            	<?php if($mdata['child']=='platform/del'):?>
		                                    <button class="btn btn-danger btn-xs del hide" id="<?php echo $v['id']?>"><i class="fa fa-trash-o"></i>&nbsp;删 除</button>
		                                    <?php endif;?>
		                                	<?php endforeach; ?>
	                                	<?php else: ?>
	                                		<?php if($v['state']==0):?>
		                                     <button class="btn btn-success btn-xs statebtn" id="<?php echo $v['id']?>" name="1"><i class="fa fa-check"></i>&nbsp;启 用</button>
			                                <?php else:?>
			                                	 <button class="btn btn-warning btn-xs statebtn" id="<?php echo $v['id']?>" name="0"><i class="fa fa-times"></i>&nbsp;禁 用</button>
			                            	<?php endif;?>
			                            	<button class="btn btn-info btn-xs" onclick="window.location.href='/platform/toedit/<?php echo $v['id']?>.html'"><i class="fa fa-edit"></i>&nbsp;编 辑</button>
			                            	<button class="btn btn-danger btn-xs del hide" id="<?php echo $v['id']?>"><i class="fa fa-trash-o"></i>&nbsp;删 除</button>
	                               		<?php endif; ?>
		                            </td>
		                        </tr>
	                        	<?php endforeach;?>
	                    	<?php else: ?>
	                    	<tr><td colspan="9">暂时没有数据！</td></tr>
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
    //启用  禁用
	$('body').on('click','.statebtn',function(){
		var _this = $(this);
		var id = _this.attr('id');
		var state = _this.attr('name');
		$.ajax({
	        url:"/platform/changestate.html",
	        type:'post',
	        dataType:'json',
	        data:{'state':state,'id':id},
	        success:function(data){
	            if(data.errorcode==0){
					if(state==0){//禁用
				    	_this.removeClass('btn-warning').addClass('btn-success').attr('name','1').html('<i class="fa fa-check"></i>&nbsp;启 用');
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

	//删除平台
	$('body').on('click','.del',function(){
		var athis = $(this);
		 var id = $(this).attr('id');
		 if(confirm('确认要删除吗？')){
			 $.ajax({
		          url:"/platform/del.html",
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
		layer.msg('复制链接成功');
	    e.clearSelection();
	}).on('error', function(e) {
		layer.msg('复制链接失败');
	});
</script>