<?php use yii\helpers\Url;
?>
<div class="page-heading">
    <h3>
       添加微信二维码
    </h3>
    <ul class="breadcrumb">
        <li>微信管理</li>
        <li><a href="<?= url::to(['index']);?>">微信二维码管理</a></li>
        <li class="active"> 添加微信二维码 </li>
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">添加微信二维码</header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" method="post" action=""  enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">微信公众号</label>
                            <div class="col-sm-5">
                               <select class="form-control tooltips" tabindex="3" name="wxappid">
                                <?php foreach ($model as $v):?>
                                <option value="<?php echo $v['appid'];?>"><?php echo $v['name'];?></option>
                                <?php endforeach;?>
                               </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">唯一参数</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="唯一参数" data-original-title="唯一参数" name="tocket" maxlength="16" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button class="btn btn-primary" type="submit" id="submit">生成二维码</button>
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
<script>
$("#submit").click(function(){
    var obj = $("input[name='tocket']").val();
    if(obj){
        layer.load(0,{time:3000});
        $.ajax({
            url:'/tocket/create.html',
            dataType:'json',
            type:'post',
            data:$('form').serialize(),
            success:function(data){
                if(data.errorcode==0){
                    if($("input[name='tocket']").next().length){
                    	$("input[name='tocket']").next().remove();
                    	$("input[name='tocket']").next().remove();
                    }
                    $("input[name='tocket']").after('<br /><img src="'+data.info+'" alt="" width="80%" />');
                }else{
                    layer.msg(data.info,{icon:5,time:2000});
                }
            }
        })
    }
    return false;
})
</script>