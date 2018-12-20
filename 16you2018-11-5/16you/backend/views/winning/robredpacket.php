<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<script type="text/javascript" src="/media/js/My97DatePicker/WdatePicker.js"></script>
<div class="page-heading">
    <h3>整点抢红包管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">中奖记录</a>
        </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	中奖记录
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">
					     <div class="btn-group col-md-12" style="padding:5px 0px;">
	                    	<div class="form-group">
		                		<form action="">
		                		  <div class="col-md-2">
		                		    <select name="type" class="form-control m-bot15">
		                		    <option value="" <?php echo ($type=='')?'selected':'';?>>所有红包</option>
		                		       <option value="1" <?php echo ($type==1)?'selected':'';?>>10点红包</option>
		                		       <option value="2" <?php echo ($type==2)?'selected':'';?>>12点红包</option>
		                		       <option value="3" <?php echo ($type==3)?'selected':'';?>>19点红包</option>
		                		       <option value="4" <?php echo ($type==4)?'selected':'';?>>21点红包</option>
		                		    </select>
		                		   </div>
		                		   <div class="col-md-9">
		                			<label class="pull-left"><input type="text" onfocus="WdatePicker({ maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}' })" id="logmin" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="开始时间" value="<?php echo $starttime; ?>" name="starttime">
									</label><span class="pull-left" style="padding:0px 10px"> ----</span>
		                           	<label class="pull-left"><input type="text" onfocus="WdatePicker({ minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d' })" id="logmax" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="结束时间"value="<?php echo $endtime; ?>" name="endtime"></label>
		                            <label style="padding-left:15px;">
		                            <button class="btn btn-primary" type="submit">搜索</button>
		                            </label>
		                           </div>
		                        </form>
		                    </div>
		                </div>
	                <div><span>金额：</span><span style="color: red;"><?php echo $money?>元</span></div>
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
	                            <th>活动名称</th>
	                            <th>用户名称</th>
	                            <th>中奖内容</th>
	                            <th>金额</th>
	                            <th>创建时间</th>
	                            <th>操作</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
									<td><?= Html::encode($v['id'])?></td>
									<td><?= Html::encode($v['title'])?></td>
		                            <td><?= Html::encode($v['username'])?></td>
		                            <td><?php  switch ($v['type']){
		                            	case 1: echo '10点红包';break;
		                            	case 2: echo '12点红包';break;
		                            	case 3: echo '19点红包';break;
		                            	case 4: echo '21点红包';break;
		                            	default:'';
		                            }?></td>
		                            <td><?= $v['money'];?></td>
		                            <td><?= Html::encode(date('Y-m-d H:i:s',$v['createtime']))?></td>
		                            <td> 
	                                    <button class="btn btn-danger btn-xs del" id="<?php echo $v['id'];?>"><i class="fa fa-trash-o"></i>&nbsp;删  除</button>
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
   //删除中奖
	/* $('.delredpacket').click(function(){
		var uindex = layer.load();
		var athis = $(this);
		var id = $(this).attr('id');
		if(confirm('确认要删除吗？')){
			$.ajax({
	          	url:"/winning/delredpacket.html",
	          	type:'post',
	          	dataType:'json',
	          	data:{'id':id},
	          	success:function(data){
		          	if(data.errorcode==0){
		        	  	window.location.reload();
			      	}else{
					  	layer.msg(data.info, {icon: 1,time:2000});
				  	}
	        	 	layer.close(uindex);
	          	}
		    }) 
		}else{
			layer.close(uindex);
		}
	}) */
	
	
	//删除游戏
	$('body').on('click','.del',function(){
		 var athis = $(this);
		 var id = $(this).attr('id');
		 if(confirm('确认要删除吗？')){
			 $.ajax({
		          url:"/winning/delredpacket.html",
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
</script>