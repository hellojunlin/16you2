<?php 
use yii\helpers\Url;
?>
<link href="/media/css/common.css" rel="stylesheet">
<div class="page-heading">
    <h3>兑换管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">兑换管理</a>
        </li>
        <li>
            <a href="/exchange/index.html">兑换列表</a>
        </li>
        <li class="active"> 详情 </li>
    </ul> 
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">兑换管理</header>
                <div class="panel-body">
                    <div class="tools pull-left">
                        <center><strong>用户名称 : </strong> <?php echo $model['username']; ?></center>
                        <br /><img src="<?php echo $model['head_url']; ?> " width='150' alt="">
                    </div>
                    <div class="pull-left col-sm-10">
                    <form class="form-horizontal adminex-form" action="#" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-sm-2 control-label"> <strong>商品名称</strong></label>
                             <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="商品名称" data-original-title="商品名称" name="product_name" maxlength="16" value="<?php echo $model['product_name']; ?>" readOnly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"> <strong>兑换所需积分</strong></label>
                             <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="兑换所需积分" data-original-title="兑换所需积分" name="type" maxlength="16" value="<?php echo $model['integral']; ?>" readOnly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><strong>地址</strong></label>
                            <div class="col-sm-5">
                                <textarea id='prdouct_details' style="width:100%; height: 4.17rem;" readOnly><?php echo $model['area']; ?></textarea>     
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><strong>联系电话</strong></label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="联系电话" data-original-title="联系电话" name="type" maxlength="16" value="<?php echo $model['phone']; ?>" readOnly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><strong>创建时间</strong></label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="创建时间" data-original-title="创建时间" value="<?php echo date('Y-m-d H:i:s',$model['createtime']); ?>" readOnly />
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>