<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="page-heading">
    <h3>邮件管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">邮件记录</a>
        </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	邮件记录
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">
	                    <div class="btn-group">
					        <button class="btn btn-primary" onclick="window.location.href='<?=Url::to(['toadd'])?>'"><i class="fa  fa-plus"></i> 添加邮件</button>&nbsp;
		                </div>
	                    <div class="btn-group pull-right">
	                    	<div class="form-group">
	                    		<form action="">
                                    <div class="col-lg-4" style="padding:0px">
                                        <select class="form-control m-bot15" name="selectval" id="selectval">
                                            <option value="title" <?php echo ($select=='title')?'selected':'';?>>邮件标题</option>
                                            <option value="content" <?php echo ($select=='content')?'selected':'';?>>邮件内容</option>
                                            <option value="uid" <?php echo ($select=='uid')?'selected':'';?>>用户ID</option>
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
	                <section id="no-more-tables">
	                	<?php if ($data['data']):$data['data'] ?> 
	                		<div class="span6">
	                			<div class="dataTables_info" id="hidden-table-info_info" style="margin-bottom:10px">显示 <?=Html::encode($data['start'])?> 到 <?= Html::encode($data['end'])?> ，共 <?= Html::encode($data['count'])?>  条</div>
	                		</div>
	                	<?php endif; ?>
	                    <table class="table table-bordered table-striped table-condensed cf">
	                        <thead class="cf">
	                        <tr>
	                            <th>编号</th>
	                            <th>邮件标题</th>
	                            <th>邮件内容</th>
	                            <th>属于用户ID</th>
	                            <th>是否有礼包</th>
	                            <th>操作</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
		                            <td><?= Html::encode($v['id'])?></td>
		                            <td><?= Html::encode($v['title'])?></td>
		                            <td><?= Html::encode($v['content'])?></td>
		                            <td><?= Html::encode($v['uid'])?></td>
		                            <td><?= $v['state']==1?'<span class="label label-success">是</span>':'<span class="label label-warning">否</span>'?></td>
		                            <td>
		                                <button class="btn btn-info btn-xs" onclick="window.location.href='/email/toedit/<?php echo $v['uniqid']; ?>.html'"><i class="fa fa-edit"></i>&nbsp;编辑</button>
	                                    <button class="btn btn-danger btn-xs del" id="<?php echo $v['uniqid'];?>"><i class="fa fa-trash-o"></i>&nbsp;删  除</button>
		                            </td>
		                        </tr>
	                        	<?php endforeach;?>
	                        
	                    	<?php else: ?>
	                    	<tr><td colspan="7"  align="center">暂时没有数据！</td></tr>
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
   //删除礼包
	$('.del').click(function(){
		var uindex = layer.load();
		var athis = $(this);
		 var id = $(this).attr('id');
		 if(confirm('确认要删除吗？')){
			 $.ajax({
		          url:"/email/del.html",
		          type:'post',
		          dataType:'json',
		          data:{'uniqid':id},
		          success:function(data){
			          if(data.errorcode==0){
			        	  window.location.reload();
				      }else{
						  layer.msg(data.info, {icon: 1,time:2000});
					  }
		        	 layer.close(uindex);
		          }
		      }) 
		     }
	})
</script>