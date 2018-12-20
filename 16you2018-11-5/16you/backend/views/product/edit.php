<?php 
use yii\helpers\Url;
?>
<link href="/media/css/common.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/media/css/bootstrap-fileupload.min.css" />
<script charset="utf-8" src="/media/js/kindedit/kindeditor.js"></script>
<script charset="utf-8" src="/media/js/kindedit/lang/zh_CN.js"></script>
<script>
    var editor;
    KindEditor.ready(function(K) {
        KindEditor.options.filterMode = false;// 关闭过滤模式，保留所有标签
        editor = K.create('textarea[name="prdouct_details"]', {
            urlType:'domain',
            afterBlur: function(){this.sync();} , //同步数据到textarea
        height:'200px'});
    });
</script> 
<div class="page-heading">
    <h3>商品管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">商品管理</a>
        </li>
        <li>
            <a href="/product/index.html">商品列表</a>
        </li>
        <li class="active"> 编辑 </li>
    </ul> 
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">商品管理</header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" method="post" action="<?=Url::to(['create'])?>" enctype="multipart/form-data">
                        <input type="hidden" name="id"  value="<?php echo $model->id; ?>"/>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 商品名称</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="商品名称" data-original-title="商品名称" name="product_name" maxlength="16" value="<?php echo $model->product_name; ?>" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 商品详情</label>
                            <div class="col-sm-5">
                                <textarea id='prdouct_details' style="width:100%; height: 4.17rem;" required name="prdouct_details"><?php echo $model->prdouct_details;?></textarea> 
                                <span></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 商品图片</label>
                            <div class="col-sm-5">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                        <img src="/media/images/product/<?php echo $model->image_url; ?>" alt="">
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                    <div>
                                        <span class="btn btn-default btn-file">
                                            <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片 </span>
                                            <span class="fileupload-exists"><i class="fa fa-undo"></i> 重 选</span>
                                            <input type="file" class="default" name="file"/>
                                        </span>
                                        <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> 移 除</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 商品剩余数量</label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="商品剩余数量" data-original-title="商品剩余数量" name="number" maxlength="16" value="<?php echo $model->number; ?>" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 兑换所需积分</label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="兑换所需积分" data-original-title="兑换所需积分" name="integral" value="<?php echo $model->integral; ?>" maxlength="16" required />
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-2 control-label">排序</label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="排序" data-original-title="排序" name="sort" maxlength="10" required />
                            </div>
                            <span style="color:#e00">（数值越高越靠前）</span>
                        </div>
                        
                        
                         <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">商品属性</label>
                            <div class="col-sm-9 icheck ">
                                <div class="square-blue">
                                    <div class="radio ">
                                        <div class="iradio_square-blue" style="position: relative;"><input tabindex="3" type="radio" name="type" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" <?php echo ($model->type==0)?'checked':'';?> value="0"></div>
                                        <label> 虚拟物品 </label>
                                    </div>
                                </div>
                                <div class="square-red">
                                    <div class="radio ">
                                        <div class="iradio_square-red" style="position: relative;"><input tabindex="3" type="radio" name="type" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" <?php echo ($model->type==1)?'checked':'';?> value="1"></div>
                                        <label> 实物 </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                       <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">状态</label>
                            <div class="col-sm-9 icheck ">
                                <div class="square-blue">
                                    <div class="radio ">
                                        <div class="iradio_square-blue" style="position: relative;"><input tabindex="3" type="radio" name="state" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" <?php echo ($model->state==1)?'checked':'';?> value="1"></div>
                                        <label> 启用 </label>
                                    </div>
                                </div>
                                <div class="square-red">
                                    <div class="radio ">
                                        <div class="iradio_square-red" style="position: relative;"><input tabindex="3" type="radio" name="state" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" <?php echo ($model->state==0)?'checked':'';?> value="0"></div>
                                        <label> 禁用 </label>
                                    </div>
                                </div>
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
<script src="/media/js/bootstrap-fileupload.min.js"></script>
<script>
//判断添加的商品名称是否已存在
$("input[name='product_name']").change(function(){
    var product_name = $(this).val();
    if(product_name != ''){
        $.ajax({
            url:'/product/productname.html',
            type:'post',
            data:{'product_name':product_name},
            success:function(data){
                if(data == 1){
                  $("input[name='product_name']").val("");
                    $("input[name='product_name']").next('span').html('用户手机 '+product_name+' 已存在!').css('color','#900');
                }else{
                  $("input[name='product_name']").next('span').html('');
                }
            }
        });
    }
})
</script>