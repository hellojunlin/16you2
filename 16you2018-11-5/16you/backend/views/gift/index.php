<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="page-heading">
    <h3>礼包管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">礼包记录</a>
        </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	礼包记录
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">
	                    <div class="btn-group">
	                     	<?php if(YII::$app->session['role']!='-1'):?> 
			               	<?php foreach (yii::$app->session['mdata'] as $mdata):?>
					          <?php if($mdata['child']=='gift/toadd'):?>
		                        <button class="btn btn-primary" onclick="window.location.href='<?=Url::to(['toadd'])?>'"><i class="fa  fa-plus"></i> 添加礼包 </button>&nbsp;
		                  	  <?php endif;?>
						    <?php endforeach;?>
						 	<?php else :?>
					            <button class="btn btn-primary" onclick="window.location.href='<?=Url::to(['toadd'])?>'"><i class="fa  fa-plus"></i> 添加礼包</button>&nbsp;
		                 	<?php endif;?>
		                </div>
	                    <div class="btn-group pull-right">
	                    	<div class="form-group">
	                    		<form action="">
                                    <div class="col-lg-4" style="padding:0px">
                                        <select class="form-control m-bot15" name="selectval" id="selectval">
                                         	<option value="game_name" <?php echo ($select=='game_name')?'selected':'';?>>游戏名称</option>
                                            <option value="gift_name" <?php echo ($select=='gift_name')?'selected':'';?>>礼包名称</option>
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
	                            <th>礼包名称</th>
	                            <th>游戏名称</th>
	                            <th>领取方式</th>
	                            <th style='width:35%'>礼包内容</th>
	                            <th>礼包类型</th>
	                            <th>剩余个数</th>
	                            <th>用途</th>
	                            <th>操作</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
		                            <td><?= $k+1;?></td>
		                            <td><?= Html::encode($v['gift_name'])?></td>
		                            <td><?= Html::encode($v['game_name'])?></td>
		                            <td><?= Html::encode($v['payment'])?></td>
		                            <td><?= Html::encode($v['content'])?></td>
		                            <td>
		                            <?php
		                                switch ($v['gifttype']){
											case 0: echo '<label class="label label-primary">新手</label>';break;
											case 1: echo '<label class="label label-info">节日</label>';break;
											case 2: echo '<label class="label label-warning">活动</label>';break;
											case 3: echo '<label class="label label-link">首发</label>';break;
											case 4: echo '<label class="label label-success">入群</label>';break;
		                                }
		                             ?>
		                            </td>
		                            <td><?= Html::encode($v['c_num'])?></td>
		                            <td><?= $v['type']==1?'<span class="label label-success">游戏礼包</span>':'<span class="label label-warning">邮件</span>'?></td>
		                            <td>
		                                <button class="btn btn-info btn-xs" onclick="window.location.href='/gift/toedit/<?php echo $v['id']; ?>.html'"><i class="fa fa-edit"></i>&nbsp;编辑</button>
	                                    <button class="btn btn-danger btn-xs del" id="<?php echo $v['number'];?>"><i class="fa fa-trash-o"></i>&nbsp;删  除</button>
		                            </td>
		                        </tr>
	                        	<?php endforeach;?>
	                        
	                    	<?php else: ?>
	                    	<tr><td colspan="8"  align="center">暂时没有数据！</td></tr>
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
   //删除礼包
	$('.del').click(function(){
		var athis = $(this);
		 var id = $(this).attr('id');
		 if(confirm('确认要删除吗？')){
		 	var indexq = layer.load();
			$.ajax({
		          url:"/gift/del.html",
		          type:'post',
		          dataType:'json',
		          data:{'number':id},
		          success:function(data){
			          if(data.errorcode==0){
			        	  window.location.reload();
				      }else{
						  layer.msg(data.info, {icon: 1,time:2000});
					  }
		        	 layer.close(indexq);
		          }
		      }) 
		     }
	})
	//导入礼包信息
	$("#cdownload").click(function(){
		var display = $("#downloadfile").css('display');
		if(display=='inline-block'){
			$("#downloadfile").css('display','none');
			$("#cdownload").html('<i class="fa fa-cloud-download"></i> 导入excel');
		}else{
			$("#downloadfile").css('display','inline-block');
			$("#cdownload").html('关闭');
		}
	})
	function changetxt(){
		$('.file span').text('文件已选');
	}
</script>