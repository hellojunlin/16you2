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
.gropmm {
    display: flex;
    width: 100%;
    justify-content: center;
    margin: 6% 6%;
}
label.gropcl {
    width: 14%;
}
.gropcl.slowtip {
    width: 51%;
}
.btngz {
    text-align: center;
    margin: 0 -4%;
}
</style>
<script type="text/javascript" src="/media/js/My97DatePicker/WdatePicker.js"></script>
<div class="page-heading">
    <h3>
              游币管理
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">游币记录</a>
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
                                <h4>¥ <?php echo isset($allgamecurrency[0]['num'])?$allgamecurrency[0]['num']:0;?>元</h4>
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
                                <span class="state-title">  已使用游币  </span>
                                <h4>¥ <?php echo isset($usegamecurrency[0]['num'])?$usegamecurrency[0]['num']:0;?>元</h4>
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
                                <h4>¥ <?php echo isset($allgamecurrency[0]['num'])?(isset($usegamecurrency[0]['num'])?$allgamecurrency[0]['num']-$usegamecurrency[0]['num']:$allgamecurrency[0]['num']):0?>元</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel panel-primary">
	            <header class="panel-heading">
	               	游币记录
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
		            <div class="clearfix">      
		                    <div class="btn-group col-md-12" style="padding:5px 0px;">
		                    	<div class="form-group">
			                		<form action="">
			                			<label class="pull-left"><input type="text" onfocus="WdatePicker({ maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}' })" id="logmin" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="开始时间" value="<?php echo $starttime; ?>" name="starttime">
										</label><span class="pull-left" style="padding:0px 10px"> ----</span>
			                           	<label class="pull-left"><input type="text" onfocus="WdatePicker({ minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d' })" id="logmax" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="结束时间"value="<?php echo $endtime; ?>" name="endtime"></label>
	    	                            <label class="pull-left" style="padding-left:15px;"><input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入用户名称..." data-original-title="请输入用户名称" name="username" maxlength="16" value="<?php echo $username;?>"></label>
			                            <label style="padding-left:15px;">
			                            <button class="btn btn-primary" type="submit">搜索</button>
			                            </label>
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
	                            <th>头像</th>
	                            <th>用户名称</th>
	                            <th>用户唯一ID</th>
	                            <th>币值</th>
	                            <th>状态</th>
	                            <th>备注</th>
	                            <th>创建时间</th>
	                            <th>审核通过时间</th>
	                            <th>操作</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
		                            <td><?= $k+1;?></td>
		                            <td>
									<?php if($v['head_url']): ?>
		                            	<img src="<?= Html::encode($v['head_url']); ?>" style="width:40px">
									<?php else: ?>
										<img src="/media/images/noimg.jpg" style="width:40px">
									<?php endif; ?>
		                            </td>
		                            <td><?= Html::encode($v['username'])?></td>
		                             <td><?= Html::encode($v['Unique_ID'])?></td>
		                            <td><?= Html::encode($v['currencynum'])?></td>
		                             <?php if($v['state']==0):?>
		                            <td class="statval"><label class="label label-warning">未审核</label></td>
		                            <?php else:?>
		                            <td class="statval"><label class="label label-success">已审核</label></td>
		                            <?php endif;?>
		                            <td><?php if( mb_strlen($v['remark'],"UTF8")<12): echo $v['remark']?>
		                                <?php else:?>
		                                 <a  data-placement="top" data-toggle="tooltip" class="tooltips" href="#" title="<?php echo $v['remark'];?>">
		                                <?php echo  mb_substr($v['remark'],0,6,"UTF-8").'......';?>
		                                 </a>
		                                <?php endif;?>
		                            </td>
		                            <td><?= Html::encode(date('Y-m-d H:i:s',$v['createtime'])) ?></td>
		                            <td><?php echo empty($v['checkcreatetime'])?'':date('Y-m-d H:i:s',$v['checkcreatetime']); ?></td>
		                            <td>
		                           		<?php if($v['state']==0):?>
		                                <button class="btn btn-info btn-xs passbtn" id="<?= Html::encode($v['id'])?>"><i class="fa fa-edit"></i>&nbsp;通过</button>
		                                <button class="btn btn-danger btn-xs delbtn" id="<?= Html::encode($v['id'])?>"><i class="fa fa-edit"></i>&nbsp;删除</button>
			                       		<?php endif;?>
			                        </td>
		                        </tr>
	                        	<?php endforeach;?>
	                        
	                    	<?php else: ?>
	                    	<tr><td colspan="11" align="center">暂时没有数据！</td></tr>
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
<!-- modal 密码 -->
    <div class="modal fade in" id="myModalpassword" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" style="background:rgba(0,0,0,0.5);left:180px">
       <div class="modal-dialog" style="width: 33%;">
           <div class="modal-content" style="overflow: hidden;">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h4 class="modal-title">请输入密码</h4>
                  </div>
                  <div class="">
                        <div class="gropmm">
                            <label class="gropcl">密码:</label>
                            <div class="gropcl slowtip">
                                <input type="password" class="tooltips" id="passwordvalue" title="" value="" data-original-title="" maxlength="16">
                                <div style="color: red;" class="hinttool"></div>
                            </div>
                        </div> 
                        <div class="form-group" style="margin-bottom: 15px;">
                            <div class="btngz col-lg-offset-2">
                                <button class="btn btn-primary" id="savePassword">确认</button>
                            </div>
                        </div>
                    <div class="col-md-12" style="text-align:center;">
                  </div>
		       </div>
         </div>
       </div>
     </div>

    <!-- modal end-->
<script src="/media/js/clipboard.min.js"></script>
<script>
	var ispass = "<?php echo $ispass;?>";
	$('body').on('click','.passbtn',function(){
		if(ispass){
			 $('#myModalpassword').css('display','block');
			 return false;
		 }
		var _this = $(this);
		var id = _this.attr('id');
		if(confirm('确认通过审核吗？')){
			$.ajax({
		        url:"/currency/passcurrency.html",
		        type:'post',
		        dataType:'json',
		        data:{'id':id},
		        success:function(data){
		            if(data.errorcode==0){
		            	_this.parent().parent().children().eq(5).html('<label class="label label-success">已审核</label>');
		            	_this.parent().parent().children().eq(7).html(data.checkcreatetime);
		            	_this.parent().html('');
		            	layer.msg('审核成功', {icon: 1,time:1300});
		            }else if(data.errorcode=='1002'){
		            	$('#myModalpassword').css('display','block');
		            	layer.msg(data.msg, {icon: 1,time:1300});
			        }else{
		            	alert(data.msg);
		            }
		        }
		    }) 
		}
	})
	
	$('body').on('click','.close',function(){
		 $('#myModalpassword').css('display','none');
  	});
	
	$('body').on('click','#savePassword',function(){
		 var passwordvalue = $('#passwordvalue').val();
		 if(passwordvalue==''){
			$('.hinttool').html('密码不能为空');
			return false;
		 }else{
			 $('.hinttool').html('');
		 }
		 $.ajax({
		        url:"/currency/logincurrency.html",
		        type:'post',
		        dataType:'json',
		        data:{'passwordvalue':passwordvalue},
		        success:function(data){
		            if(data.errorcode==0){
		            	ispass = false;
		            	$('#myModalpassword').css('display','none');
		            	layer.msg('登录成功', {icon: 1,time:1300});
		            }else{
		            	$('.hinttool').html(data.msg);
		            }
		        }
		    })
    });



	$('body').on('click','.delbtn',function(){
		var _this = $(this);
		var id = _this.attr('id');
		if(confirm('确认删除吗？')){
			$.ajax({
		        url:"/currency/del.html",
		        type:'post',
		        dataType:'json',
		        data:{'id':id},
		        success:function(data){
		            if(data.errorcode==0){
		            	_this.parent().parent('tr').remove();
		            	layer.msg('删除成功', {icon: 1,time:1300});
		            }else if(data.errorcode=='1002'){
		            	layer.msg(data.msg, {icon: 1,time:1300});
			        }else{
		            	alert(data.msg);
		            }
		        }
		    }) 
		}
	})
</script>