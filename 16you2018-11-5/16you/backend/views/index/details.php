<!DOCTYPE html>
<html lang="en">
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
             <li><a href="http://www.sucaihuo.com/templates">权限管理</a></li>
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
	                        <button class="btn btn-primary" id="add-cole"><i class="fa  fa-plus"></i> 添加角色 </button>&nbsp;
	                    </div>
	                </div>
                <section id="unseen">
                   <div class="span6">
                		<div class="dataTables_info" id="hidden-table-info_info" style="margin-bottom:10px">显示1到 20，共 30条</div>
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
                        <tr>
                            <td class="coleid">1</td>
                            <td class="role-name">管理员1</td>
                            <td class="numeric remarktxt">管理</td>
                            <td class="numeric">2016-07-15 13:52:27</td>
                            <td class="numeric">2016-07-15 15:12:27</td>
                            <td class="numeric">
                            	<button class="btn btn-info btn-xs"><i class="fa fa-edit"></i> 编 辑</button>
                            	<button class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> 删 除</button>
                            	<button class="btn btn-success btn-xs" type="button"><i class="fa fa-edit"></i>查看权限</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="coleid">2</td>
                            <td class="role-name">管理员2</td>
                            <td class="numeric remarktxt">无权限</td>
                            <td class="numeric">2016-07-15 13:52:27</td>
                            <td class="numeric">2016-07-15 15:12:27</td>
                            <td class="numeric">
                            	<button class="btn btn-info btn-xs"><i class="fa fa-edit"></i> 编 辑</button>
                            	<button class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> 删 除</button>
                            	<button class="btn btn-success btn-xs" type="button"><i class="fa fa-edit"></i>查看权限</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="coleid">3</td>
                            <td class="role-name">管理员3</td>
                            <td class="numeric remarktxt">无权限</td>
                            <td class="numeric">2016-07-15 13:52:27</td>
                            <td class="numeric">2016-07-15 15:12:27</td>
                            <td class="numeric">
                            	<button class="btn btn-info btn-xs"><i class="fa fa-edit"></i> 编 辑</button>
                            	<button class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> 删 除</button>
                            	<button class="btn btn-success btn-xs" type="button"><i class="fa fa-edit"></i>查看权限</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </section>
            </div>
        </section>
      </div>
      </div>
     </div> 
    <!-- modal 添加角色 -->
    <div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" style="display:none;background:rgba(0,0,0,0.5);">
       <div class="modal-dialog">
           <div class="modal-content">
              <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h4 class="modal-title">添加角色</h4>
                  </div>
                  <div class="modal-body row">
                    <form class="form-horizontal adminex-form" method="get">
                      <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label ">角色名称：</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control rolename">
                        </div>
                       </div>
                       <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label ">备注：</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control remark">
                        </div>
                       </div> 
                       <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label">角色权限：</label>
                        <div class="col-sm-10">
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
													<input type="checkbox" value="" name="user-Character-0-0-0" id="user-Character-0-0-3">
													查看</label>
												<label class="">
													<input type="checkbox" value="" name="user-Character-0-0-0" id="user-Character-0-0-4">
													审核</label>
												<label class="c-orange"><input type="checkbox" value="" name="user-Character-0-0-0" id="user-Character-0-0-5"> 只能操作自己发布的</label>
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
												<label class="">
													<input type="checkbox" value="" name="user-Character-0-0-0" id="user-Character-0-0-3">
													查看</label>
												<label class="">
													<input type="checkbox" value="" name="user-Character-0-0-0" id="user-Character-0-0-4">
													审核</label>
												<label class="c-orange"><input type="checkbox" value="" name="user-Character-0-0-0" id="user-Character-0-0-5"> 只能操作自己发布的</label>
											</dt>
										</dl>
									</dd>
				                </dl>
                            </div>
                        </div>
                       </div>  
                    </form>
                    <div class="col-md-12" style="text-align:center;">
                    <button id="savebtn" class="btn btn-success btn-sm" type="button">保存</button>
                  </div>
		       </div>
         </div>
       </div>
     </div>
    <!-- modal end-->
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
													<input type="checkbox" value="" name="user-Character-0-0-0" id="user-Character-0-0-3">
													查看</label>
												<label class="">
													<input type="checkbox" value="" name="user-Character-0-0-0" id="user-Character-0-0-4">
													审核</label>
												<label class="c-orange"><input type="checkbox" value="" name="user-Character-0-0-0" id="user-Character-0-0-5"> 只能操作自己发布的</label>
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
												<label class="">
													<input type="checkbox" value="" name="user-Character-0-0-0" id="user-Character-0-0-3">
													查看</label>
												<label class="">
													<input type="checkbox" value="" name="user-Character-0-0-0" id="user-Character-0-0-4">
													审核</label>
												<label class="c-orange"><input type="checkbox" value="" name="user-Character-0-0-0" id="user-Character-0-0-5"> 只能操作自己发布的</label>
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
    <!-- modal -->
    
    <!-- modal 编辑 -->
    <div class="modal fade in" id="myModal-2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" style="display:none;background:rgba(0,0,0,0.5);">
       <div class="modal-dialog">
           <div class="modal-content">
              <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h4 class="modal-title">添加角色</h4>
                  </div>
                  <div class="modal-body row">
                    <form class="form-horizontal adminex-form" method="get">
                      <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label">角色名称：</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control rolename">
                        </div>
                       </div>
                       <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label ">备注：</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control remark">
                        </div>
                       </div> 
                       <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label">角色权限：</label>
                        <div class="col-sm-10">
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
													<input type="checkbox" value="" name="user-Character-0-0-0" id="user-Character-0-0-3">
													查看</label>
												<label class="">
													<input type="checkbox" value="" name="user-Character-0-0-0" id="user-Character-0-0-4">
													审核</label>
												<label class="c-orange"><input type="checkbox" value="" name="user-Character-0-0-0" id="user-Character-0-0-5"> 只能操作自己发布的</label>
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
												<label class="">
													<input type="checkbox" value="" name="user-Character-0-0-0" id="user-Character-0-0-3">
													查看</label>
												<label class="">
													<input type="checkbox" value="" name="user-Character-0-0-0" id="user-Character-0-0-4">
													审核</label>
												<label class="c-orange"><input type="checkbox" value="" name="user-Character-0-0-0" id="user-Character-0-0-5"> 只能操作自己发布的</label>
											</dt>
										</dl>
									</dd>
				                </dl>
                            </div>
                        </div>
                       </div>  
                    </form>
                    <div class="col-md-12" style="text-align:center;">
                    <button id="savebtn-2" class="btn btn-success btn-sm" type="button">保存</button>
                  </div>
		       </div>
         </div>
       </div>
     </div>
    <!-- modal end-->
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
   </script>
</body>
</html>