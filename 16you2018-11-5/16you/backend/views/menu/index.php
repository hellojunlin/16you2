<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<div class="page-heading">
    <h3>
   			     菜单管理
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= Url::to(['index']);?>">权限管理</a>
        </li>
        <li class="active"> 菜单管理 </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
	            <header class="panel-heading">
	           		    菜单管理
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
	            <div class="panel-body">
	            	<div class="clearfix">
	            	      <?php if(YII::$app->session['role']!='-1'):?> 
				               <?php foreach (yii::$app->session['mdata'] as $mdata):?>
						          <?php if($mdata['child']=='menu/add'):?>
		                       	   <button class="btn btn-primary" onclick="window.location.href='<?=Url::to(['toadd'])?>'"><i class="fa  fa-plus"></i> 添加菜单 </button>&nbsp;
							     <?php endif;?>
						      <?php endforeach;?>
						  <?php else :?>
					               <button class="btn btn-primary" onclick="window.location.href='<?=Url::to(['toadd'])?>'"><i class="fa  fa-plus"></i> 添加菜单 </button>&nbsp;
						  <?php endif;?>	
							<div class="btn-group pull-right">
		                    	<div class="form-group">
		                    		<form action="<?=Url::to(['index'])?>">
			                            <button class="btn btn-primary" type="submit">搜索</button>
			                            <div class="col-sm-9">
			                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入菜单名" data-original-title="请输入菜单名" name="keyword" maxlength="16" value='<?php echo $search; ?>' />
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
	                            <th>ID</th>	
		                        <th>菜单名称</th>
		                        <th>路由</th>
		                        <th>图标</th>
		                        <th>操作</th>
	                        </tr>
	                        </thead>
	                        <tbody>
	                        <?php if($data['data']): ?>
	                        	<?php foreach ($data['data'] as $k => $list):?> 
								<tr>
		                            <td ><?= $k+1; ?></td>
		                        	<td ><?= Html::encode($list[ 'name']);?></td> 
		                        	<td ><?= Html::encode($list[ 'route']);?></td> 
		                            <td><i class="<?= Html::encode($list[ 'icon']);?>" style="color: black;"></i></td>
		                            <td>
		                             <?php if(YII::$app->session['role']!='-1'):?> 
		                         	   <?php foreach (yii::$app->session['mdata'] as $mdata):?>
						         			 <?php if($mdata['child']=='menu/toedit'):?>
			                                  <a href="<?=Url::to(['toedit','id'=>$list['id']])?>" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> 编辑</a>
			                                 <?php endif;?>
			                                  <?php if($mdata['child']=='menu/del'):?>
			                                  <a href="javascript:void(0);"  class="btn btn-danger btn-xs del" id="<?php echo $list['id'] ?>"><i class="fa fa-trash-o"></i> 删除</a>
											  <?php endif;?>
			                                  <?php if($mdata['child']=='menu/childmenu'):?>
				                                  <?php if(empty($list[ 'route'])):?>
					                              <a href="javascript:void(0);" onclick="childmenu('<?php echo $list['id'] ?>','<?php echo $list['name']?>')" class="btn btn-xs btn-warning"><i class="fa fa-eye"></i> 查看子菜单</a>
					                             <?php endif;?>
				                             <?php endif;?>
			                             <?php endforeach;?>
			                         <?php else:?>
			                               <a href="<?=Url::to(['toedit','id'=>$list['id']])?>" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> 编辑</a>
		                                   <a href="javascript:void(0);"  class="btn btn-danger btn-xs del" id="<?php echo $list['id'] ?>"><i class="fa fa-trash-o"></i> 删除</a>
			                             <?php if(empty($list[ 'route'])):?>
			                               <a href="javascript:void(0);" onclick="childmenu('<?php echo $list['id'] ?>','<?php echo $list['name']?>')" class="btn btn-xs btn-warning"><i class="fa fa-eye"></i> 查看子菜单</a>
			                             <?php endif;?>
			                          <?php endif;?>
		                            </td>
		                        </tr>
	                        	<?php endforeach;?>
	                    	<?php else: ?>
	                    	    <tr ><td colspan ="20" >暂无数据！</td ></tr >         
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
<!-- modal 添加角色 -->
    <div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" style="display:none;background:rgba(0,0,0,0.5);">
       <div class="modal-dialog" style="width:65%;">
           <div class="modal-content">
              <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h4 class="modal-title">添加角色</h4>
                  </div>
                  <div class="modal-body row">
                  <table class="table table-striped table-hover table-bordered" id="sample_editable_2">
		                  <thead>
		                     <tr>
			                     <th>菜单名称</th>
			                     <th>路由</th>
			                     <th>操作</th>
		                      </tr>
		                  </thead>
		                  <tbody class="tbod">
		                  </tbody > 
		            </table> 
                    <div class="col-md-12" style="text-align:center;">
                  </div>
		       </div>
         </div>
       </div>
     </div>
    <!-- modal end-->
<script src="/media/js/layer/layer.js"></script>
<script>
//删除父级菜单
$('body').on('click','.del',function(){
	var athis = $(this);
	 var id = $(this).attr('id');
	 if(confirm('确认要删除主菜单及其子菜单吗？')){
		 $.ajax({
	          url:"/menu/del.html",
	          type:'post',
	          dataType:'json',
	          data:{
		          'id':id,
		          'type':1,
		          },
	          success:function(data){
		          if(data.errorcode=='0'){
		        	  layer.msg(data.info, {icon: 1,time:1300});
		           	  athis.parent().parent('tr').remove();
			      }else if(data.errorcode=='1001'){
			    	  layer.msg(data.info, {icon: 1,time:1300});
				  }else{
					  layer.msg(data.info, {icon: 1,time:1300});
				  }
	        	 
	          }
	      }) 
	     }
})



function del(id){
  if(confirm('确认要删除主菜单及子菜单吗？')){
    $.ajax({
        url:"<?= Url::to(['menu/del'])?>",
        type:'post',
        dataType:'json',
        data:{
             'id':id,
            },
        success:function(data){
            if(data.errorcode==0){
           	  layer.msg(data.info, {icon: 1,time:2000});
          	  window.location.reload();
            }else{
           	  layer.msg(data.info, {icon: 1,time:2000});
            }
        }
    }) 
  }
}

//查看子菜单
function childmenu(id,name){
	    $('#myModal').css('display','block');	
		// $('#right_details').css('display','block');
		$('body').css('overflow','hidden');
	  $.ajax({
	         url:"<?= Url::to(['menu/childmenu'])?>",
	         type:'post',
	         dataType:'json',
	         data:{
		         'id':id,
	        	 },
	         success:function(data){
	             if(data.errorcode==0){
          		  var tbod = $('.tbod');
	               	  var tab_1_2 = $('#tab_1_2');
	               	  $('<span id="rname">').html(name).insertBefore($('#right_details'));
	               	  $.each(data.info,function(k,v){
	     				var tr = $('<tr>').appendTo(tbod);
	     				$('<td>').html(v.name).appendTo(tr);
	     				$('<td>').html(v.route).appendTo(tr);
	     				var td3 = $('<td class="td3">').appendTo(tr);
	     				$('<a class="btn btn-info btn-xs editcmenu"" style="margin:10px;" id="'+v.id+'"><i class="fa fa-edit">编辑</i></a>').attr('onclick','toedit('+v.id+')').appendTo(td3);
	     				$('<a class="btn btn-danger btn-xs delcmenu" href="javascript:void(0);" style="" id="'+v.id+'"><i class="fa fa-trash-o">删除</i></a>').appendTo(td3);
	     			 })
	             }else{
	            	 layer.msg(data.info, {icon: 1,time:1300});
	             }
	         }
	     })
}

//关闭模态框
$('.close').click(function remove(){
	    $('#myModal').css('display','none');
	    $('.tbod').html('');
	    $('#rname').remove();
	    $('body').css('overflow','');
})
	  
//删除子菜单  
$('body').on('click','.delcmenu',function(){
	var athis = $(this);
	 var id = $(this).attr('id');
	 if(confirm('确认要删除吗？')){
	      $.ajax({
	    	  url:"<?= Url::to(['menu/delcmenu'])?>",
	          type:'post',
	          dataType:'json',
	          data:{
	               'id':id
	              },
	          success:function(data){
	              if(data.errorcode==0){
	            	  layer.msg(data.info, {icon: 1,time:1300});
	            	  athis.parent().parent('tr').remove();
	              }else{
	            	  layer.msg(data.info, {icon: 1,time:1300});
	              }
	          }
	      }) 
	     }
})	  
	  
function toedit(id){
	 location.href="toedit.html?id="+id;
}
</script>