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
<style>
#clipArea {
    height: 200px;
}
#file,
#clipBtn {
    margin: 20px;
}
#clipBtn {
    background: #2b7dbc;
    color: #fff;
    padding: 5px;
}
</style>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">公司管理</header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" action="" enctype="multipart/form-data" action="" id="formdata">
                    	<input type="hidden" name="id" value="<?php echo $model->id; ?>">
                        <input type="hidden" name="oldimage" value="<?php echo $model->image; ?>">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><span style="color:#e00">*</span> 轮播类型</label>
                            <div class="col-sm-5">
                                <select class="form-control tooltips" tabindex="3" name="state" id="state">
                                    <option value="1" <?php if($model->state==1){echo 'selected';} ?>>首页轮播</option>
                                    <option value="2"<?php if($model->state==2){echo 'selected';} ?>>商城轮播</option>
                                    <option value="3"<?php if($model->state==3){echo 'selected';} ?>>弃用</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 轮播图片</label>
                            <div class="col-sm-5" style="margin-top:7px">
                                <input type="file" data-trigger="hover" data-toggle="tooltip" title="" placeholder="轮播图片" data-original-title="轮播图片" name="image" class="default" accept="image/*" required/>
                                <p><img src="/media/images/carousel/<?php echo $model->image; ?>" style="width:414px;height:158px;margin-top:5px"/></p>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 轮播图片</label>
                            <div class="col-sm-5">
                                <input type="hidden" value="<?php //echo $model->image; ?>" name="image"/>
								<div id="clipArea"></div>
								<input type="file" id="file" style="display: inline-block;">
								<span id="clipBtn">点击截取</span>
								<div id="view" style="height:105px;background-image:url(/media/images/carousel/<?php echo $model->image; ?>)"></div>
                            </div>
                        </div> -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 轮播链接</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="轮播链接" data-original-title="轮播链接" name="url" maxlength="200" value="<?php echo $model->url; ?>" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 排序</label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="写数字,数字越大,轮播越靠前显示" data-original-title="写数字,数字越大,轮播越靠前显示" name="sort" oninput="if(value.length>10)value=value.slice(0,10)" value="<?php echo $model->sort; ?>" required /><span></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 备注</label>
                            <div class="col-sm-5">
                                <input type="url" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="备注" data-original-title="备注" name="remark" maxlength="200" value="<?php echo $model->remark; ?>"/><span>可以为空</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10 col-sm-offset-2 col-sm-10">
                                <button class="btn btn-primary" id='submit'>保 存</button>
                                <button class="btn btn-default" type="reset"  onclick="window.location.reload()">重 写</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</section>
<script src="/media/js/layer/layer.js"></script><!--下面的js有用到-->
<script>
$("input[name='image']").change(function(){
    var this_l = $(this);
    var url = window.URL.createObjectURL(this_l['0'].files['0']);
    this_l.next('p').html('<img src="'+url+'" style="width:414px;height:158px;margin-top:5px"/>');
})

/*---提交表单---*/
$("#submit").click(function(){
    layer.load(0, {time:1000}); //0代表加载的风格，支持0-2
    var this_l = $(this);
    var fd = new FormData(document.getElementById('formdata'));
    var xmlobj = new XMLHttpRequest();
    xmlobj.open('post','/carousel/create.html');
    xmlobj.send(fd);
    xmlobj.onload = function(){
        var data = JSON.parse(xmlobj.responseText);
        if(data[0]){
            window.location.href ='/carousel/index.html';
        }else{
            layer.msg(data[1],{icon:2,time:2000});
        }    
    }
    return false;
})
</script>