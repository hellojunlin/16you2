<?php use yii\helpers\Url;
?>
<link rel="stylesheet" type="text/css" href="/media/css/bootstrap-fileupload.min.css" />
<link rel="stylesheet" type="text/css" href="/media/js/jquery-multi-select/css/multi-select.css" />
<script type="text/javascript" src="/media/js/My97DatePicker/WdatePicker.js"></script>
<style>
.must{
    color:#FF0000;
}
</style>
<div class="page-heading">
    <h3>
后台账号管理
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= url::to(['index']);?>">账号记录</a>
        </li>
        <li class="active">编辑后台账号</li>
    </ul>
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">编辑记录</header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" method="post" id='signupok' enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $manage->id;?>"> 
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">账号<span class="must">*</span></label>
                               <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="账号"  name="username" maxlength="100" value="<?php echo $manage->username;?>" required />
                                <span></span>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">密码<span class="must">*</span></label>
                            <div class="col-sm-5">
                               <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="密码"  name="password" maxlength="100" value="" required />
                            
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">角色</label>
                            <div class="col-sm-5">
                               <select class="form-control tooltips" tabindex="3" name="role" id="role">
                                <?php foreach ($role as $r):?>
                                <option value="<?php echo $r->name;?>" <?php echo ($r->name==$manage->role)?'selected':'';?>><?php echo $r->name;?></option>
                                <?php endforeach;?>
                               </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">状态<span class="must">*</span></label>
                            <div class="col-sm-9 icheck ">
                                <div class="square-blue">
                                    <div class="radio ">
                                        <div class="iradio_square-blue" style="position: relative;"><input tabindex="3" type="radio" name="state" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" <?php echo ($manage->state==1)?'checked':'';?> value="1"></div>
                                        <label> 启用 </label>
                                    </div>
                                </div>
                                <div class="square-red">
                                    <div class="radio ">
                                        <div class="iradio_square-red" style="position: relative;"><input tabindex="3" type="radio" name="state" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" value="0" <?php echo ($manage->state==0)?'checked':'';?>></div>
                                        <label> 禁用 </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <input type="button" name="signup" id="submit" class="btn btn-primary" value="保存">
                                <button type="button" name="signup" id="submitload" class="btn btn-primary" style="width:54px;display:none;"><i class="fa fa-spinner fa-pulse"></i></button>
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
<script type="text/javascript" src="/media/js/LocalResizeIMG.js"></script>  
<script type="text/javascript" src="/media/js/mobileBUGFix.mini.js"></script>
<script>    
	$("input[name='username']").change(function(){
	    var username = $(this).val();
	    if(username == ''){
	        $(this).next('span').html('用户名不能为空').css('color','#900');
	     }else{
	         $(this).next('span').html('');
	         $.ajax({
	             url:'/admin/uniqueone.html',
	             type:'post',
	             data:{'username':username},
	             success:function(data){
	                 if(data == 1){
	                   $("input[name='username']").val("");
	                     $("input[name='username']").next('span').html('用户名 '+username+' 已存在!').css('color','#900');
	                 }else{
	                   $("input[name='username']").next('span').html('');
	                 }
	             }
	         });
	    }
	})


    //异步提交数据
    $('#submit').click(function(){
    	var username = $("input[name='username']").val();
    	var password = $("input[name='password']").val();
	    if(username == ''){
	    	$("input[name='username']").next('span').html('用户名不能为空').css('color','#900');
	       layer.msg('用户名不能为空');
	       return false;
	    }else{
	        $(this).next('span').html('');
	   }
        var index_ = layer.load();
         $.ajax({       
                type:'post',
                dataType:'json',
                data:$('#signupok').serialize(),
                url:'/admin/add.html',
                success:function(data){
                    if(data){
                            if(data.errorcode==0){
                                 layer.msg(data.info, {icon: 1,time:2000});
                                 setTimeout(function (){
                                     location.href="/admin/index.html";
                                },1000);
                            }else if(data.errorcode==1001){
                                   layer.msg(data.info, {icon: 1,time:2000});
                            }else{
                                   layer.msg('添加失败', {icon: 1,time:2000});
                            }
                    }
                }
                    
          });
        setTimeout(function (){
            layer.close(index_);
        },2000);
          
    });
</script>