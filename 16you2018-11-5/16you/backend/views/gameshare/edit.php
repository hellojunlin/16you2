<?php 
use yii\helpers\Url;
?>
<link rel="stylesheet" type="text/css" href="/media/css/bootstrap-fileupload.min.css" />
<!-- <link rel="stylesheet" type="text/css" href="/media/js/jquery-multi-select/css/multi-select.css" /> -->
<!-- <link href="/media/css/common.css" rel="stylesheet"> -->
<div class="page-heading">
    <h3>
        游戏分享
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['shareinfo','id'=>yii::$app->session['gname']->id])?>">游戏分享</a>
        </li>
        <li class="active"> 编辑 </li>
    </ul>
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    游戏分享
                </header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" method="post" action="<?=Url::to(['create'])?>" enctype="multipart/form-data">
                        <input type="hidden" name="gid" value="<?php echo yii::$app->session['gname']->id; ?>">
                        <input type="hidden" name="id" value="<?php echo $model->id; ?>">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">游戏名称</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" value="<?php echo yii::$app->session['gname']->name; ?>" readOnly/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">类型</label>
                            <div class="col-sm-5">
                                <select class="form-control m-bot15" name="type">
                                    <option value="<?php echo $model->type; ?> "><?php echo $res; ?></option>
                                    <?php foreach ($result as $k=>$v):?>
                                    <option value="<?php echo $v; ?>"><?php echo $k; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 标题</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="标题" data-original-title="标题" name="title" maxlength="100" value="<?php echo $model->title; ?>" required/><span></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 链接</label>
                            <div class="col-sm-5">
                                <input type="url" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="链接" data-original-title="链接" name="link" value="<?php echo $model->link; ?>"/><span></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">分享图标</label>
                            <div class="col-sm-5">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                        <img src="<?php echo $model->imgUrl; ?>" alt="">
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                    <div>
                                        <span class="btn btn-default btn-file">
                                            <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片 </span>
                                            <span class="fileupload-exists"><i class="fa fa-undo"></i> 重 选</span>
                                            <input type="file" class="default" name="file" />
                                        </span>
                                        <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> 移 除</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">分享描述</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="分享描述" data-original-title="分享描述" name="desc" maxlength="200" value="<?php echo $model->desc; ?>"/>
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