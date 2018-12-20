<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<head>
    <meta charset="UTF-8">
    <title>角色管理</title>
    <link href="/media/css/role-manager.css" rel="stylesheet">
    <script src="/media/js/layer/layer.js"></script>
</head>
<body>
     <div class="page-heading">
         <h3>角色管理</h3>
         <ul class="breadcrumb">
             <li><a href="<?= Url::to(['index'])?>">权限管理</a></li>
             <li class="active">角色管理</li>
          </ul>
     </div>
   <div class="wrapper">
     <div class="row">
     <div class="col-sm-12">
     <section class="panel">
            <header class="panel-heading">
	               	角色管理	                
	               	<span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>
            <div class="panel-body">
                <div class="clearfix">
	                    <div class="btn-group">
	                     <?php if(YII::$app->session['role']!='-1'):?> 
			               <?php foreach (yii::$app->session['mdata'] as $mdata):?>
					          <?php if($mdata['child']=='role/toadd'):?>
	                      		 <button class="btn btn-primary" onclick="window.location.href='<?= url::to(['toadd'])?>'"><i class="fa  fa-plus"></i> 添加角色 </button>&nbsp;
	                          <?php endif;?>
						    <?php endforeach;?>
						  <?php else :?>
					             <button class="btn btn-primary" onclick="window.location.href='<?= url::to(['toadd'])?>'"><i class="fa  fa-plus"></i> 添加角色 </button>&nbsp;
	                       <?php endif;?>
	                    </div>
	                    <div class="btn-group pull-right">
	                    	<div class="form-group">
	                    		<form action="">
		                            <button class="btn btn-primary" type="submit">搜索</button>
		                            <div class="col-sm-8" style="padding:0px 1px">
		                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="请输入角色名称..."  name="name" maxlength="16" value='<?php echo $search;?>' />
		                            </div>
	                            </form>
	                        </div>
	                    </div>
	                </div>
                <section id="unseen">
                   <div class="span6">
                		<div class="dataTables_info" id="hidden-table-info_info" style="margin-bottom:10px">显示 <?= Html::encode($data['start'])?> 到 <?= Html::encode($data['end'])?> ，共 <?= Html::encode($data['count'])?>条</div>
                	</div>
                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                        <tr>
                            <th class="coleid">ID</th>
                            <th class="role-name">角色名称</th>
                            <th class="numeric remarktxt">描述</th>
                            <th class="numeric">创建时间</th>
                            <th class="numeric">更新时间</th>
                            <th class="numeric">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                         <?php if(!empty($data['data'])): ?>
	                        	<?php foreach ($data['data'] as $k => $v):?> 
                        <tr>
                            <td class="coleid"><?= $k+1;?></td>
                            <td class="role-name"><?= Html::encode($v[ 'name']); ?></td>
                            <td class="numeric remarktxt"><?= Html::encode($v['description']); ?></td>
                            <td class="numeric"><?php echo date('Y-m-d H:i:s',$v['created_at']);?></td>
                            <td class="numeric"><?php echo date('Y-m-d H:i:s',$v['updated_at']);?></td>
                            <td class="numeric">
                             <?php if(YII::$app->session['role']!='-1'):?> 
		                        <?php foreach (yii::$app->session['mdata'] as $mdata):?>
					         		<?php if($mdata['child']=='role/toedit'):?>
	                            		<button class="btn btn-info btn-xs" onclick="window.location.href='<?= url::to(['toedit','name'=>$v['name']])?>'"><i class="fa fa-edit"></i> 编 辑</button>
	                            	<?php endif; ?>
	                            	<?php if($mdata['child']=='role/del'):?>
	                            		<button class="btn btn-danger btn-xs del"  id="<?php echo $v['name']?>"><i class="fa fa-trash-o"></i> 删 除</button>
                            		<?php endif; ?>
                             	<?php endforeach;?> 
                            	<?php else:?>
	                            	<button class="btn btn-info btn-xs" onclick="window.location.href='<?= url::to(['toedit','name'=>$v['name']])?>'"><i class="fa fa-edit"></i> 编 辑</button>
	                            	<button class="btn btn-danger btn-xs del"  id="<?php echo $v['name']?>"><i class="fa fa-trash-o"></i> 删 除</button>
                              	<?php endif;?>
                            	
                            	<!-- <button class="btn btn-success btn-xs" type="button"><i class="fa fa-edit"></i>查看权限</button> -->
                            </td>
                        </tr>
                        <?php endforeach;?>
                    	<?php else: ?>
                    	<tr><td colspan="9">暂时没有数据！</td></tr>
                    	<?php endif; ?>
                        </tbody>
                    </table>
                </section>
            </div>
        </section>
      </div>
      </div>
     </div> 
    
    <!-- modal 查看权限 -->
    <div class="modal fade in" id="myModal-1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" style="display:none;background:rgba(0,0,0,0.5);">
       <div class="modal-dialog">
           <div class="modal-content">
              <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h4 class="modal-title">查看权限</h4>
                  </div>
                  <div class="modal-body row">
                    <form class="form-horizontal adminex-form" method="get">
                       <div class="form-group">
                        <div class="col-sm-12">
                            <div>
                                <dl class="permission-list">
									<dt>
										<label>
											<input type="checkbox" value="" name="user-Character-0" id="user-Character-0">
											栏目管理</label>
									</dt>
									<dd>
										<dl class="cl permission-list2">
											<dt>
												<label class="">
													<input type="checkbox" value="" name="user-Character-0-0-0" id="user-Character-0-0-0">
													添加</label>
												<label class="">
													<input type="checkbox" value="" name="user-Character-0-0-0" id="user-Character-0-0-1">
													修改</label>
												<label class="">
													<input type="checkbox" value="" name="user-Character-0-0-0" id="user-Character-0-0-2">
													删除</label>
												<label class="">
											</dt>
										</dl>
									</dd>
				                </dl>
				                 <dl class="permission-list">
									<dt>
										<label>
											<input type="checkbox" value="" name="user-Character-0" id="user-Character-0">
											文章管理</label>
									</dt>
									<dd>
										<dl class="cl permission-list2">
											<dt>
												<label class="">
													<input type="checkbox" value="" name="user-Character-0-0-0" id="user-Character-0-0-0">
													添加</label>
												<label class="">
													<input type="checkbox" value="" name="user-Character-0-0-0" id="user-Character-0-0-1">
													修改</label>
												<label class="">
													<input type="checkbox" value="" name="user-Character-0-0-0" id="user-Character-0-0-2">
													删除</label>
											</dt>
										</dl>
									</dd>
				                </dl>
                            </div>
                        </div>
                       </div>  
                    </form>
                    <div class="col-md-12" style="text-align:center;">
                    <button id="savebtn-1" class="btn btn-success btn-sm" type="button">保存</button>
                  </div>
		       </div>
         </div>
       </div>
     </div>
     </body>
    <!-- modal -->
   <script>  
      $('#add-cole').click(function(){  //添加角色模态框操作
		 $('#myModal').css('display','block');	
       })
       $('#savebtn').click(function(){
    	   $('#myModal').css('display','none');
       })
       $('.close').click(function(){
    	   $('#myModal').css('display','none');
       })
       
      $('.numeric .btn-success').click(function(){	//查看权限模态框操作
          $('#myModal-1').css('display','block');
      })
      $('#savebtn-1').click(function(){
    	   $('#myModal-1').css('display','none');
       })
       $('.close').click(function(){
    	   $('#myModal-1').css('display','none');
       })
       
       $('.numeric .btn-info').click(function(){	//编辑模态框操作
          $('#myModal-2').css('display','block');
          var otr = $(this).parent().parent();
          var oname = otr.children('.role-name').text();
          var oremark = otr.children('.remarktxt').text();
          $('.rolename').val(oname);
          $('.remark').val(oremark);
      })
      $('#savebtn-2').click(function(){
    	   $('#myModal-2').css('display','none');
       })
       $('.close').click(function(){
    	   $('#myModal-2').css('display','none');
       })
       
       	//删除
	$('body').on('click','.del',function(){
		var athis = $(this);
		 var id = $(this).attr('id');
		 console.log(id);
		 if(confirm('确认要删除吗？')){
			 $.ajax({
		          url:"/role/del.html",
		          type:'post',
		          dataType:'json',
		          data:{'name':id},
		          success:function(data){
			          if(data.errorcode=='0'){
			        	  alert(data.info);
			           	  athis.parent().parent('tr').remove();
				      }else if(data.errorcode=='1001'){
	                    alert(data.info);
					  }else{
	                    alert(data.info);
					  }
		        	 
		          }
		      }) 
		     }
	})
   </script>

