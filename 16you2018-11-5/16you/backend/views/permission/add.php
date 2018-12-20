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
                          添加权限
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= Url::to(['index']);?>">权限管理</a>
        </li>
        <li class="active"> 添加权限</li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                   		添加权限
                </header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" id="signupok" enctype="multipart/form-data">
                     <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">所属菜单</label>
                            <div class="col-sm-5">
                                <select name="fmenu" class="form-control tooltips">
                                <!--   <option value='0'>顶级权限</option> -->
		                          <?php foreach ($fmenu as $menu):?> 
		                            <?php if($menu['parent']!=''):?>
			                         	 <option value='<?php echo $menu['id'];?>'><?php echo $menu['name'];?></option>
		                            <?php endif;?>
		                          <?php endforeach;?>
	                            </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">权限名称</label>
                            <div class="col-sm-5">
                             <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="控制器名称/方法名称        例如:permission/index" placeholder="控制器名称/方法名称        例如:permission/index" name="name" maxlength="50" required />
                             <span></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">描述</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="例如：跳转权限首页" placeholder="例如：跳转权限首页"  name="description" maxlength="50" required />
                                <span></span>
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
//判断添加的权限是否已存在
$("input[name='name']").blur(function(){
	console.log(111);
    var name = $(this).val();
    if(name != ''){
      $.ajax({ 
       url:'/permission/checkname.html',
       type:'post',
       data:{'name':name},
       success:function(data){
            if(data == 1){
              $("input[name='name']").val("");
                $("input[name='name']").next('span').html('权限 '+name+' 已存在!').css('color','#900');
            }else{
              $("input[name='name']").next('span').html('');
            }
        }
      });
  }
}); 

//异步提交数据
$('#submit').click(function(){
	   $.ajax({		
		type:'post',
		dataType:'json',
		data:$('#signupok').serialize(),
		url:'/permission/add.html',
		success:function(data){
			if(data.errorcode==0){
				 layer.msg(data.info, {icon: 1,time:2000});
				 setTimeout(function (){
					 location.href="/permission/index.html";
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