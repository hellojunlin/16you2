<?php use yii\helpers\Url;?> 
<link rel="stylesheet" type="text/css" href="/media/css/bootstrap-fileupload.min.css" />
<link rel="stylesheet" type="text/css" href="/media/js/jquery-multi-select/css/multi-select.css" />
    <!--icheck-->
    <link href="/media/js/iCheck/skins/square/square.css" rel="stylesheet">
    <link href="/media/js/iCheck/skins/square/red.css" rel="stylesheet">
    <link href="/media/js/iCheck/skins/square/green.css" rel="stylesheet">
    <link href="/media/js/iCheck/skins/square/blue.css" rel="stylesheet">
    <link href="/media/js/iCheck/skins/square/yellow.css" rel="stylesheet">
    <link href="/media/js/iCheck/skins/square/purple.css" rel="stylesheet">
<div class="page-heading">
    <h3>
                          角色编辑
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= Url::to(['index']);?>">角色管理</a>
        </li>
        <li class="active"> 编辑角色</li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                   		编辑角色
                </header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" id="signupok" enctype="multipart/form-data">
                       <div class="form-group">
                       <label class="col-sm-2 col-sm-2 control-label">角色名称：</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control rolename" name="name" value="<?php echo isset($role->name)?$role->name:'';?>" readonly>
                        </div>
                       </div>
                       
                       <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label ">描述：</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control remark" name="description" value="<?php echo isset($role->description)?$role->description:'';?>">
                        </div>
                       </div> 
                       <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label">角色权限：</label>
                        <div class="col-sm-10">
                            <div>
                               <?php foreach ($menupermiss as $k=>$mp):?>
                                <dl class="permission-list">
									<dt style="background-color: #efefef; padding: 5px 10px;">
										<label class="allinput">
											<input type="checkbox" value="" name="user-Character-0" id="user-Character-0">
											<?php echo $k;?></label>
									</dt>
									<dd>
							 			<?php foreach ($mp as $m):?>
											<label class="selfinput">
												<input type="checkbox"  name="permission[]" id="user-Character-0-0-0" value="<?php echo $m['route'];?>" <?php  foreach ($permission as $per){  if($m['route']==$per->name){echo "checked";}}?>>
												<?php echo $m['description'];?>
											</label>
										<?php endforeach;?>
									</dd>
				                </dl>
				                <?php endforeach;?>
                            </div> 
                    </div>
                    </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                            <input type="button" name="signup" id="submit" class="btn btn-primary" value="保存">
                                <button class="btn btn-default" type="reset">重 写</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
	</div>
</section>
<script type="text/javascript" src="/media/js/bootstrap-fileupload.min.js"></script>
<script src="/media/js/iCheck/jquery.icheck.js"></script>
<script src="/media/js/layer/layer.js"></script>
<script>
//角色权限选择
$('.allinput').click(function(){	//全选或全不选
	var thisinput = $(this).children('input');
	var ddinput = $($(this).parent().parent()).children('dd').children('.selfinput').children('input');
	var check = thisinput.attr('checked');
	if(check){
		thisinput.attr('checked','checked');
		ddinput.attr('checked','checked');
	}else{
		thisinput.removeAttr('checked');
		ddinput.removeAttr('checked');
	}
})
$('.selfinput').click(function(){	//单选
	var chilthisinput = $(this).children('input');
	var dtinput = $($(this).parent().parent()).children('dt').children('.allinput').children('input');
	var chilcheck =chilthisinput.attr('checked');
	if(chilcheck){
		chilthisinput.attr('checked','checked');
		dtinput.attr('checked','checked');
	}else{
		chilthisinput.removeAttr('checked');
	}
})
//异步提交数据
$('#submit').click(function(){
	   $.ajax({		
		type:'post',
		dataType:'json',
		data:$('#signupok').serialize(),
		url:'/role/edit.html',
		success:function(data){
			if(data.errorcode==0){
				 layer.msg(data.info, {icon: 1,time:2000});
				 setTimeout(function (){
					 location.href="/role/index.html";
				},1000);
			}else if(data.errorcode==1001){
				   layer.msg(data.info, {icon: 1,time:2000});
	    		}else{
	    		   layer.msg('添加失败', {icon: 1,time:2000});
	    		}
		}
	    });
});
</script>