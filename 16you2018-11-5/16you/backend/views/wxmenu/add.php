<?php 
use yii\helpers\Url;
?>
<link rel="stylesheet" type="text/css" href="/media/css/auto.css" />
<div class="page-heading">
    <h3>微信菜单</h3>
    <ul class="breadcrumb">
        <li><a href="#">微信管理</a></li>
        <li><a href="<?=Url::to(['tomenu'])?>">微信菜单</a></li>
        <li><a href="<?=Url::to(['index','appid'=>$appid])?>">微信菜单设置</a></li>
        <li class="active"> 添加 </li>
    </ul>
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    微信菜单
                </header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" method="post" action="<?=Url::to(['create'])?>" enctype="multipart/form-data">
                        <input type="hidden" name="findex" value="<?php echo $findex; ?>">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><span class="l_span">*</span> 微信公众号</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" placeholder="微信公众号" data-original-title="微信公众号" value="<?php echo $wxname; ?>" readOnly/>
                            </div>
                        </div>
                        <?php if($name): ?>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><span class="l_span">*</span> 一级菜单</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="要加 http:// 或 fttp" data-original-title="http://www.baidu.com" name="fname" maxlength="100" value="<?php echo $name; ?>" readOnly/>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><span class="l_span">*</span> 菜单名称</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="菜单名称" data-original-title="菜单名称" name="name" maxlength="200" required/><span></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><span class="l_span">*</span> 回复类型</label>
                            <div class="col-sm-5">
                                <select class="form-control tooltips" tabindex="3" name="type">
                                    <option value="view">跳转URL</option>
                                    <option value="click">发送消息</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><span class="l_span">*</span> 链接</label>
                            <div class="col-sm-5">
                                <input type="url" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="要加 http:// 或 fttp" data-original-title="http://www.baidu.com" name="content" maxlength="100"/><span></span>
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
<script>
    var input_content = '<label class="col-sm-2 col-sm-2 control-label"><span class="l_span">*</span> 链接</label><div class="col-sm-5"><input type="url" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="要加 http:// 或 fttp" data-original-title="http://www.baidu.com" name="content" maxlength="100"/><span></span></div>';
    var key_content = '<label class="col-sm-2 col-sm-2 control-label"><span class="l_span">*</span> 关键字</label><div class="col-sm-5"><input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" data-original-title="" name="key" maxlength="100"/></div>';
  $("body").on('change',$("select[name='type']"),function(){
    if($("select[name='type']").val()=="click"){
      $("input[name='content']").parent().parent().html(key_content);
    }else{
      $("input[name='key']").parent().parent().html(input_content);
    }
  })
</script>