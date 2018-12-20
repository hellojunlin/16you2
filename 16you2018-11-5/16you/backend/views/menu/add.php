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
                          添加菜单
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= Url::to(['index']);?>">菜单管理</a>
        </li>
        <li class="active"> 添加菜单 </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                   		添加菜单
                </header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" id="signupok" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">父级菜单</label>
                            <div class="col-sm-5">
                               <select name="fmenu" class="form-control tooltips">
                               <option value='-1'>顶级菜单</option>
	                          <?php foreach ($fmenu as $menu):?>
		                          <?php if($menu['parent']==-1):?>
		                         	 <option value='<?php echo $menu['id'];?>'><?php echo $menu['name'];?></option>
		                          <?php endif;?>
	                          <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">菜单名称</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title=""  name="name" maxlength="50" required />
                                <span></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">路由</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="控制器名称/方法名称        例如:permission/index" data-original-title="控制器名称/方法名称        例如:permission/index" name="route" maxlength="50" />
                                <span></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">小图标</label>
                            <div class="col-sm-5">
                                    <span><input type="radio" name="icon" value="fa fa-home" checked/><i class="fa fa-home" style="color: black;"></i></span>
				                    <span><input type="radio" name="icon" value="fa fa-laptop"/><i class="fa fa-laptop" style="color: black;"></i></span>
				                    <span><input type="radio" name="icon" value="fa fa-book" /><i class="fa fa-book" style="color: black;"></i></span>
				                    <span><input type="radio" name="icon" value="fa fa-cogs"/><i class="fa fa-cogs" style="color: black;"></i></span>
				                    <span><input type="radio" name="icon" value="fa fa-bullhorn"/><i class="fa fa-bullhorn" style="color: black;"></i></span>
				                    <span><input type="radio" name="icon" value="fa fa-envelope"/><i class="fa fa-envelope" style="color: black;"></i></span>
				                    <span><input type="radio" name="icon" value="fa fa-tasks"/><i class="fa fa-tasks" style="color: black;"></i></span>
				                    <span><input type="radio" name="icon" value="fa fa-bar-chart-o"/><i class="fa fa-bar-chart-o" style="color: black;"></i></span>
				                    <span><input type="radio" name="icon" value="fa fa-th-list"/><i class="fa fa-th-list" style="color: black;"></i></span>
				                    <span><input type="radio" name="icon" value="fa fa-map-marker"/><i class="fa fa-map-marker" style="color: black;"></i></span>
				                    <span><input type="radio" name="icon" value="fa fa-file-text"/><i class="fa fa-file-text" style="color: black;"></i></span>
				                    <span><input type="radio" name="icon" value="fa fa-sign-in"/><i class="fa fa-sign-in" style="color: black;"></i></span>
	                                <span><input type="radio" name="icon" value="fa fa-male"/><i class="fa fa-male" style="color: black;"></i></span>
	                                <span><input type="radio" name="icon" value="fa fa-bullseye"/><i class="fa fa-bullseye" style="color: black;"></i></span>
	                                <span><input type="radio" name="icon" value="fa fa-phone"/><i class="fa fa-phone" style="color: black;"></i></span>
                                    <span><input type="radio" name="icon" value="fa fa-road"/><i class="fa fa-road" style="color: black;"></i></span>
                                    <span><input type="radio" name="icon" value="fa fa-road"/><i class="fa fa-user" style="color: black;"></i></span>
                                    <span><input type="radio" name="icon" value="fa fa-road"/><i class="fa fa-shopping-cart" style="color: black;"></i></span>
                                <span></span>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">参数</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" title=""  name="param" maxlength="50" />
                                <span></span>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">权重</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" title="" placeholder="数字越小越靠前" data-original-title="数字越小越靠前"  name="weight" maxlength="50" required/>
                                <span></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                            <input type="button" name="signup" id="submit" class="btn btn-primary" value="保存">
                                <!-- <button class="btn btn-primary" type="submit">保 存</button> -->
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
//异步提交数据
$('#submit').click(function(){
	   $.ajax({		
		type:'post',
		dataType:'json',
		data:$('#signupok').serialize(),
		url:'/menu/add.html',
		success:function(data){
			if(data.errorcode==0){
				 layer.msg(data.info, {icon: 1,time:2000});
				 setTimeout(function (){
					 location.href="/menu/index.html";
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