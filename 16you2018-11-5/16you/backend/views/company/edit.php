<?php 
use yii\helpers\Url;
?>
<link href="/media/css/common.css" rel="stylesheet">
<div class="page-heading">
    <h3>公司管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">公司管理</a>
        </li>
        <li>
            <a href="/company/index.html">公司列表</a>
        </li>
        <li class="active"> 编辑 </li>
    </ul>
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">公司管理</header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" method="post" action="<?=Url::to(['create'])?>" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $model->id; ?>">
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 公司名称</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="公司名称" data-original-title="公司名称" name="compname" maxlength="16" value="<?php echo $model->compname; ?>" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">角色</label>
                            <div class="col-sm-5">
                               <select class="form-control tooltips" tabindex="3" name="role" id="role">
                                    <?php foreach ($roles as $r):?>
                                         <option value="<?php echo $r->name;?>" <?php if(isset($model->role)){if($r->name==$model->role){echo 'selected';}}?>><?php echo $r->name;?></option>
                                   <?php endforeach;?>
                               </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 联系人姓名</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="联系人姓名" data-original-title="联系人姓名" name="linkman" maxlength="16" value="<?php echo $model->linkman; ?>" required /><span></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 手机</label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="输入11位手机号码" data-original-title="输入11位手机号码" name="phone" oninput="if(value.length>11)value=value.slice(0,11)" value="<?php echo $model->phone; ?>" required /><span></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"> 密码</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="密码" data-original-title="密码" name="password" maxlength="16"/><span></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 公司地址</label>
                            <div class="col-sm-5">
                                <!-- 下拉三级联动 -->
                                <select class="form-control-select" id="province" name="province"></select>
                                <select class="form-control-select" id="city" name="city" ></select>
                                <select  class="form-control-select" id="area" name="area"></select>
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="公司详细地址" data-original-title="公司地址" name="address" maxlength="200" value="<?php echo $model->address; ?>" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10 col-sm-offset-2 col-sm-10">
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
<script src="/media/js/layer/layer.js"></script>
<script type="text/javascript" src="/media/js/area.js"></script>
<script type="text/javascript">_init_area();</script>
<script>
var id = <?php echo $model->id; ?>;
//判断添加的手机是否已存在
$("input[name='phone']").change(function(){
var phone = $(this).val();
if(phone == ''){
    $("input[name='phone']").next('span').html('手机号码不能为空').css('color','#900');
}else{
    $.ajax({
        url:'/company/phone.html',
        type:'post',
        data:{'phone':phone,
              'id':id,
            },
        success:function(data){
            if(data == 1){
              $("input[name='phone']").val("");
                $("input[name='phone']").next('span').html('手机 '+phone+' 已存在!').css('color','#900');
            }else{
              $("input[name='phone']").next('span').html('');
            }
        }
    });
}
})

$("button[type='submit']").click(function(){
    var compname = $("input[name='compname']").val().length;
    var phone = $("input[name='phone']").val().length;
    if(compname!=0&&phone!=0){
        layer.msg('保存成功', {icon: 1,time:2000});
    }
})
var province = "<?php echo $model->province; ?>";
var city = "<?php echo $model->city; ?>";
var area = "<?php echo $model->area; ?>";
$("#province option[value='"+province+"']").attr('selected',true);
change(1);
$("#city option[value='"+city+"']").attr('selected',true);
change(2);
$("#area option[value='"+area+"']").attr('selected',true);
</script>