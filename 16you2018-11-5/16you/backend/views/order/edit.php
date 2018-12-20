<?php 
use yii\helpers\Url;
?>
<link href="/media/css/common.css" rel="stylesheet">
<div class="page-heading">
    <h3>订单管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">订单管理</a>
        </li>
        <li>
            <a href="/order/index.html">订单列表</a>
        </li>
        <li class="active"> 详情 </li>
    </ul> 
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">订单管理</header>
                <div class="panel-body">
                    <div class="tools pull-left col-md-3 col-sm-3">
                        <center><strong>用户名称 : </strong> <?php echo base64_decode($model['username']); ?></center>
                        <br /><img src="<?php echo $model['head_url']; ?> " width='200' alt="">
                    </div>
                    <div class="pull-left col-md-9 col-sm-9">
                    <form class="form-horizontal adminex-form" method="post" action="<?=Url::to(['create'])?>" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-sm-2 control-label"> <strong>道具名称</strong></label>
                             <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="道具名称" data-original-title="道具名称" name="propname" maxlength="16" value="<?php echo $model['propname']; ?>" readOnly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><strong>游戏名称</strong></label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="游戏名称" data-original-title="游戏名称" name="name" maxlength="16" value="<?php echo $model['name']; ?>" readOnly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><strong>总价格</strong></label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="总价格" data-original-title="总价格" name="price" maxlength="16" value="<?php echo $model['price']; ?>" readOnly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><strong>数量</strong></label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="数量" data-original-title="数量" name="num" maxlength="16" value="<?php echo $model['num']; ?>" readOnly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><strong>订单状态</strong></label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="订单状态" data-original-title="订单状态" value="<?php echo $model['state']; ?>" readOnly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><strong>厂商订单编号</strong></label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="订单编号" data-original-title="订单编号" value="<?php echo $model['orderID']; ?>" readOnly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><strong>交易订单编号</strong></label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="订单编号" data-original-title="交易订单编号" value="<?php echo $model['transaction_id']; ?>" readOnly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><strong>下单时间</strong></label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="下单时间" data-original-title="下单时间" value="<?php echo date('Y-m-d H:i:s',$model['createtime']); ?>" readOnly />
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>