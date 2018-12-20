<?php 
use yii\helpers\Url;
?>
<div class="page-heading">
    <h3>配置管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">配置管理</a>
        </li>
        <li class="active"> 编辑 </li>
    </ul> 
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">配置管理</header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" method="post" action="<?=Url::to(['create'])?>" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $model->id; ?>">
                        <input type="hidden" name="gid" value="<?php echo $model->gid; ?>">
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 所属游戏</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" value="<?php echo $game->name;?> " readOnly/><span></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 支付通知地址</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="支付通知地址" data-original-title="支付通知地址" name="type_url"  value="<?php echo $model->type_url;?> "required/><span></span>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 有效域名</label>
                            <div class="col-sm-5">
                                <input type="url" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="有效域名" data-original-title="有效域名" name="api_url" value="<?php echo $model->api_url;?> "/><span></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 签名密匙</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="签名密匙" data-original-title="签名密匙" name="" value="<?php echo $model->key;?> " readOnly/><span></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 厂商编号</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="厂商编号" data-original-title="厂商编号" name="" value="<?php echo $model->partnerid;?> "readOnly/><span></span>
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