<?php 
use yii\helpers\Url;
?>
<link href="/media/css/common.css" rel="stylesheet">
<div class="page-heading">
    <h3>积分管理</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">积分管理</a>
        </li>
        <li>
            <a href="/integral/index.html">积分列表</a>
        </li>
        <li class="active"> 详情 </li>
    </ul> 
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">积分管理</header>
                <div class="panel-body">
                    <div class="tools pull-left col-md-3 col-sm-3">
                        <center><strong>用户名称 : </strong> <?php echo $model['username']; ?></center>
                        <br /><img src="<?php echo $model['head_url']; ?> " width='150' alt="">
                    </div>
                    <div class="pull-left col-md-9 col-sm-9">
                    <form class="form-horizontal adminex-form" action="#" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-sm-2 control-label"> <strong>积分类型</strong></label>
                             <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="积分类型" data-original-title="积分类型" name="type" maxlength="16" value="<?php echo $model['type']; ?>" readOnly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><strong>积分</strong></label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="积分" data-original-title="积分" name="integral" maxlength="16" value="<?php echo $model['integral']; ?>" readOnly />
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