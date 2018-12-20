<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<style>
th {
    white-space: nowrap;
}
td{
	word-break:break-all;
}
.groupUserImg,.groupUserName {
    display: flex;
    /* width: 100%; */
    justify-content: center;
	margin-top: 10px;
	width: 75%;
}
.groupUserImg span,.groupUserName span {
    margin: 0 auto;
    text-align: center;
}
.gropcl {
    margin: 0 auto;
    text-align: center;
}
.gropbz {
    overflow: hidden;
    display: flex;
    justify-content: center;
	width: 75%;
}
.btngz {
    text-align: center;
    margin: 14px 7%;
}
.slowsi {
    width: 30%;
}
.slowtip {
    width: 46%;
}
</style>
<div class="page-heading">
    <h3>
        用户管理
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">用户管理</a>
        </li>
        <li class="active">用户记录 </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	               	用户管理
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">     
	                    <div class="btn-group pull-right">
	                    	<div class="form-group">
	                    		<form action="<?=Url::to(['index'])?>">
		                            <div class="col-md-10" style="padding:0px;">
		                               <?php if($plate && $managemodel->role!=0): ?>
	                                    <div class="col-md-4" style="padding:0px">
	                                        <select class="form-control m-bot15" name="pid" id="pid">
	                                        	<option value=" ">选择平台</option>
	                                        	<?php foreach ($plate as $_p):?>
	                                            <option value="<?php echo $_p['id']; ?>"><?php echo $_p['pname']; ?></option>
	                                        	<?php endforeach; ?>
	                                        </select>
	                                    </div>
	                                	<?php endif; ?>
		                                <div class="col-md-4" style="padding:0px"><input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入用户ID..." data-original-title="请输入用户ID" name="uniqueid" maxlength="8" value='<?php echo $uniqueid; ?>'/></div>
		                                <div class="col-md-4" style="padding:0px"><input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入用户名..." data-original-title="请输入用户名" name="keyword" maxlength="16" value='<?php echo $value; ?>'/></div>
		                            </div>
		                            <button class="btn btn-primary col-md-2" type="submit">搜索</button>
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
	                            <th width="100px">用户UID</th>
	                            <th>用户ID</th>
	                            <th>用户名</th>
	                            <th>流量主名称</th>
	                            <th>所属平台</th>
	                            <th>头像</th>
	                            <th class="numeric">性别</th>
	                            <th class="numeric">VIP</th>
	                            <th>地址</th>
	                            <th>游币值</th>
	                            <th class="numeric">创建时间</th>
	                            <th>操作 </th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
								<tr>
		                            <td data-title="Code"><?= Html::encode($v['id']) ?></td>
		                            <td data-title="Code"><?= Html::encode($v['Unique_ID']) ?></td>
		                            <td><?= Html::encode($v['username']) ?></td>
		                            <td><?= Html::encode($v['compname']) ?></td>
		                            <td><?= Html::encode($v['name']) ?></td>
		                            <td>
									<?php if($v['head_url']): ?>
		                            	<img src="<?= Html::encode($v['head_url']); ?>" style="width:40px">
									<?php else: ?>
										<img src="/media/images/noimg.jpg" style="width:40px">
									<?php endif; ?>
		                            </td>
		                            <td class="numeric" data-title="Open"><?php echo $v['sex']==1?'男':($v['sex']==2?'女':'未知') ?></td>
		                            <td><?php echo $v['vip'];?>级</td>
		                            <td><?php echo $v['province'].'  '.$v['city'];?></td>
		                            <td><?php echo $v['currencynum']?></td>
		                            <td class="numeric" data-title="High"><?= Html::encode(date('Y-m-d H:i:s',$v['createtime'])) ?></td>
									<td>
		                        	    <button class="btn btn-info btn-xs" onclick="window.location.href='/user/touserorder/<?php echo $v['id']; ?>.html?name=<?php echo $v['username'];?>'"><i class="fa fa-edit"></i>&nbsp;查看订单</button>
		                 <!--        	    <button class="btn btn-warning btn-xs" onclick="pwd('<?php echo $v['id']; ?>')"><i class="fa fa-repeat"></i>&nbsp;重置密码</button> -->
		                        	    <button class="btn btn-success btn-xs" onclick="location.href='/user/detail/<?php echo $v['id']; ?>.html'"><i class="fa fa-plus-circle"></i>&nbsp;查看资料</button>
		                        	    <button class="btn btn-primary btn-xs" id="editcurrency" name="<?php echo $v['id']; ?>" style="display:none;"><i class="fa fa-cny"></i>&nbsp;分发游币</button>
		                        	    <button class="btn  btn-danger btn-xs" id="rebate" name="<?php echo $v['id']; ?>" style="display:none;"><i class="fa fa-money"></i>&nbsp;返利</button>
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
<!-- modal 分发游币 -->
    <div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" style="display:none;background:rgba(0,0,0,0.5);">
       <div class="modal-dialog" style="width:48%;">
           <div class="modal-content">
              <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h4 class="modal-title crtitle">分发游币</h4>
                  </div>
                  <div class="modal-body row">
                    	<div class="form-group">
                             <div class="groupUserImg"><span class="slowsi">头像</span><span class="slowtip"><img alt="" src="" id="modelheadurl" style="width:54px"></span></div>
                             <div class="groupUserName"><span class="slowsi">名称</span><span class="slowtip" id="modelusername"></span></div>
                             <input type="hidden" id="modeluid" value="" name="1">
                            <div class="col-sm-5">
                            </div>
                        </div> 
                         <div class="form-group gropbz">
                            <label class="gropcl slowsi col-sm-2 col-sm-2 control-label currency_rebate">币值</label>
                            <div class="gropcl slowtip col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" id="currencyvalue" maxlength="5" value="" >
                                <span></span>
                            </div>
                        </div> 
                        <div class="form-group">
                            <div class="btngz col-lg-offset-2 col-lg-10">
                                <button class="btn btn-primary" id="save">保 存</button>
                            </div>
                        </div>
                    <div class="col-md-12" style="text-align:center;">
                  </div>
		       </div>
         </div>
       </div>
     </div>
    <!-- modal end-->
<script>
	function pwd(id){
		$.ajax({ 
			url:'/user/pwd.html',
			type:'post',
			data:{'id':id},
			success:function(data){
				if(data==1){
					layer.msg('重置密码为 123456 ',{icon:6,time:2000});
				}else{
					layer.msg('修改失败',{icon:5,time:2000});
				}
			}
		});
	}

	/*分发游币 start*/
    $('body').on('click','#editcurrency',function(){
         var username = $(this).parent().parent().children().eq(2).html();
         var headurl = $(this).parent().parent().children().eq(5).children().attr('src');
         $('.currency_rebate').html('金额');
         var id = $(this).attr('name');
         $('#modelheadurl').attr('src',headurl);
         $('#modelusername').html(username);
         $('#modeluid').val(id);
		 $('#myModal').css('display','block');
    });

    $('body').on('click','.close',function(){
		 $('#myModal').css('display','none');
  	});

    /* 返利*/
  	$('body').on('click','#rebate',function(){
  	  	$('.crtitle').html('返利');
        var username = $(this).parent().parent().children().eq(2).html();
        var headurl = $(this).parent().parent().children().eq(5).children().attr('src');
        var id = $(this).attr('name');
        $('#modelheadurl').attr('src',headurl);
        $('#modelusername').html(username);
        $('#modeluid').val(id);
        $('#modeluid').attr('name','2');
		$('#myModal').css('display','block');
   	});

  $('body').on('click','.close',function(){
		 $('#myModal').css('display','none');
  });

  $('body').on('click','#save',function(){
	    var name = $('#modeluid').attr('name');
        var id  =  $('#modeluid').val();
        var currencyvalue = $('#currencyvalue').val();
        if(name==1){
              var url = "/user/addgamecurrency.html";
        }else{
        	  var url = "/user/addrebate.html";
        }
    	if(confirm('确认提交审核吗？')){
    	      $.ajax({
    	    	  url:url,
    	          type:'post',
    	          dataType:'json',
    	          data:{
    	               'uid':id,
    	               'currencyvalue':currencyvalue,
    	          },
    	          success:function(data){
    	              if(data.errorcode==0){
    	            	  $('#modeluid').val('');
    	            	  $('#currencyvalue').val('');
    	            	  $('#myModal').css('display','none');
    	            	  layer.msg('提交成功，请通知负责人审核', {icon: 1,time:1300});
    	              }else{
    	            	  layer.msg(data.msg, {icon: 1,time:1300});
    	              }
    	          }
    	      }) 
    	     }
    });
</script>