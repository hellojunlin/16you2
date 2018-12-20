<?php use yii\helpers\Url;
?>
<link rel="stylesheet" type="text/css" href="/media/css/bootstrap-fileupload.min.css" />
<link rel="stylesheet" type="text/css" href="/media/js/jquery-multi-select/css/multi-select.css" />
<div class="page-heading">
    <h3>
            编辑管理员
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?= Url::to(['index']);?>"><?php echo yii::$app->session['title']; ?></a>
        </li>
        <li class="active">编辑管理员 </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading"> 编辑管理员</header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" method="post" action="/manage/update.html" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $model->id; ?>" />
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">管理员账号</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="管理员账号" data-original-title="管理员账号" name="username" maxlength="16" value="<?php echo $model->username; ?>" required /><span></span>
                            </div>
                        </div>
                        <?php if($model->role!='-1'):?> 
                         <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">角色</label>
                            <div class="col-sm-5">
                               <select class="form-control tooltips" tabindex="3" name="role" id="role">
                                    <?php foreach ($roles as $r):?>
                                        <option value="<?php echo $r->name;?>" <?php if(isset($role->name)){if($r->name==$role->name){echo 'selected';}}?>><?php echo $r->name;?></option>
                                    <?php endforeach;?>
                               </select>
                            </div>
                        </div>
                        <?php else: ?>
                        <input type="hidden" name="role" value="<?php echo yii::$app->session['role']; ?> "/>
                        <?php endif; ?>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">密码</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="密码" data-original-title="密码" name="password" maxlength="16" /><span></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">头像上传</label>
                            <div class="col-md-9">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                        <img src="/media/images/head_img/<?php echo $model->head_img; ?>" alt="">
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                    <div>
                                        <span class="btn btn-default btn-file">
                                            <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片 </span>
                                            <span class="fileupload-exists"><i class="fa fa-undo"></i> 重 选</span>
                                            <input type="file" class="default" name="file">
                                        </span>
                                        <a href="http://www.sucaihuo.com/templates" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> 移 除</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group"<?php if($model->role==-1){echo 'style="display:none"';} ?>>
                            <label class="col-sm-2 col-sm-2 control-label">状态</label>
                            <div class="col-sm-9 icheck ">
                                <div class="square-blue">
                                    <div class="radio ">
                                        <div class="iradio_square-blue" style="position: relative;"><input tabindex="3" type="radio" name="state" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" <?php if($model->state==1)echo 'checked="checked"'; ?> value="1"></div>
                                        <label> 启用 </label>
                                    </div>
                                </div>
                                <div class="square-red">
                                    <div class="radio ">
                                        <div class="iradio_square-red" style="position: relative;"><input tabindex="3" type="radio" name="state" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"<?php if($model->state==0)echo 'checked="checked"'; ?> value="2"></div>
                                        <label> 禁用 </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button class="btn btn-primary" type="submit">保 存</button>
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
<script>
//判断添加的管理员账号是否已存在
$("input[name='username']").change(function(){
    var username = $(this).val();
    var id = $("input[name='id']").val();
    if(username == ''){
        $("input[name='username']").next('span').html('管理员账号不能为空').css('color','#900');
    }else{
        $.ajax({
            url:'/manage/username.html',
            type:'post',
            data:{'username':username,'id':id},
            success:function(data){
                if(data == 1){
                  $("input[name='username']").val("");
                    $("input[name='username']").next('span').html('管理员账号 '+username+' 已存在!').css('color','#900');
                }else{
                  $("input[name='username']").next('span').html('');
                }
            }
        });
    }
})
</script>