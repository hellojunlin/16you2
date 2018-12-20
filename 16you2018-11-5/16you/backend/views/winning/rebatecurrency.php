<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<script type="text/javascript" src="/media/js/My97DatePicker/WdatePicker.js"></script>
<div class="page-heading">
    <h3>游币返利管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">返利记录</a>
        </li>
    </ul>
</div>
<div class="row states-info">
 <div class="col-md-3">
                <div class="panel red-bg">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-4">
                                <i class="fa fa-money"></i>
                            </div>
                            <div class="col-xs-8">
                                <span class="state-title"> 总游币（已通过审核） </span>
                                <h4>¥ <?php echo isset($allrebatecurrency)?$allrebatecurrency:0;?>币</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel blue-bg">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-4">
                                <i class="fa fa-eye"></i>
                            </div>
                            <div class="col-xs-8">
                                <span class="state-title">  已领取游币  </span>
                                <h4>¥ <?php echo isset($rebatecurrency)?$rebatecurrency:0;?>币</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel green-bg">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-4">
                                <i class="fa fa-gavel"></i>
                            </div>
                            <div class="col-xs-8">
                                <span class="state-title">  剩余游币  </span>
                                <h4>¥ <?php echo isset($allrebatecurrency)?(isset($rebatecurrency)?$allrebatecurrency-$rebatecurrency:$allrebatecurrency):0?>币</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	返利记录<span style="color:red;">(该页面只显示当天数据，如需查看其它数据请按时间搜索)</span>
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
		                		    <select name="aid" class="form-control m-bot15">
		                		    <option value="" <?php echo ($aid=='')?'selected':'';?>>返利来源</option>
		                		       <option value="1" <?php echo ($aid==1)?'selected':'';?> selected>五一活动</option> 
		                		    </select>
		                		   </div>
		                		   <div class="col-md-2" style="padding:0px"><input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入用户ID..." data-original-title="请输入用户ID" name="uniqueid" maxlength="8" value='<?php echo $uniqueid; ?>'/></div>
		                		   <div class="col-md-8">
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
	                <div><span>充值金额：</span><span style="color: red;"><?php echo $price;?>元</span></div>
	                <section id="no-more-tables">
	                	<?php if ($data['data']):$data['data'] ?> 
	                		<div class="span6">
	                			<div class="dataTables_info" id="hidden-table-info_info" style="margin-bottom:10px">显示 <?=Html::encode($data['start'])?> 到 <?= Html::encode($data['end'])?> ，共 <?= Html::encode($data['count'])?>条</div>
	                		</div>
	                	<?php endif; ?>
	                    <table class="table table-bordered table-striped table-condensed cf">
	                        <thead class="cf">
	                        <tr>
	                            <th>编号</th>
	                            <th>活动名称</th>
	                            <th>用户ID</th>
	                            <th>用户头像</th>
	                            <th>用户名称</th>
	                            <th>平台名称</th>
	                            <th>充值金额</th>
	                            <th>返利值</th>
	                            <th>是否领取</th>
	                            <th>创建时间</th>
	                            <th>领取时间</th>
	                            <th style="display:none;">操作</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
								    <td><?php echo $k+1;?></td>
									<td>五一活动</td>
									<td><?= Html::encode($v['Unique_ID'])?></td>
									<td><img src="<?= Html::encode($v['head_url']); ?>" style="width:40px"></td>
		                            <td><?= Html::encode($v['username'])?></td>
		                            <td><?= Html::encode($v['pname'])?></td>
		                            <td><?= Html::encode($v['price'])?></td>
		                            <td><?= Html::encode($v['rebatecurrency']);?></td>
		                            <td><?php echo ($v['isdraw']==0)?'<span class="label label-sm label-danger">否</span>':'<span class="label label-sm label-success">是</span>';?></td>
		                            <td><?= Html::encode(date('Y-m-d H:i:s',$v['createtime']))?></td>
		                            <td><?php echo $v['drawtime']? Html::encode(date('Y-m-d H:i:s',$v['drawtime'])):''?></td>
		                            <td style="display:none;"> 
	                                    <button class="btn btn-danger btn-xs del" id="<?php echo $v['id'];?>"><i class="fa fa-trash-o"></i>&nbsp;删  除</button>
		                            </td>
		                        </tr>
	                        	<?php endforeach;?>
	                        
	                    	<?php else: ?>
	                    	<tr><td colspan="12"  align="center">暂时没有数据！</td></tr>
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