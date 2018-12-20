<?php 
use yii\helpers\Url;
?>
<link rel="stylesheet" type="text/css" href="/media/css/bootstrap-fileupload.min.css" />
<link rel="stylesheet" type="text/css" href="/media/css/auto.css" />
<div class="page-heading">
    <h3>关注自动回复</h3>
    <ul class="breadcrumb">
        <li><a href="#">微信管理</a></li>
        <li><a href="<?=Url::to(['index'])?>">关注自动回复</a></li>
        <li class="active"> 编辑 </li>
    </ul>
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    关注自动回复
                </header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" method="post" action="<?=Url::to(['create'])?>" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $model->id; ?>"/>
                        <input type="hidden" name="wxappid" value="<?php echo $model->wxappid; ?>"/>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">微信公众号</label>
                            <div class="col-sm-5">
                               <input type="text" class="form-control tooltips" value="<?php echo $wxname; ?>" readOnly/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><span class="l_span">*</span> 回复类型</label>
                            <div class="col-sm-5">
                                <input type="hidden" name='type' value="<?php echo $model->type; ?>"/>
                                <input type="text" class="form-control tooltips" value="<?php echo ($model->type==1)?'文本':($model->type==2?'图文':($model->type==3?'图片':'视频')); ?>" readOnly/><span></span>
                            </div>
                        </div>
                        <?php if($model->type==1): ?>
                        <div class="form-group" id='text'>
                            <label class="col-sm-2 col-sm-2 control-label"><span class="l_span">*</span> 回复的内容</label>
                            <div class="col-sm-5">
                                <textarea name="content"  class="form-control"  placeholder="不要超过200个字" rows="5"><?php echo $model->content; ?></textarea>
                                <span style="color:red">输入链接模板： &lt;a href='http://www.16you.com'&gt;16游&lt;/a&gt;</span>
                            </div>
                        </div>
                        <?php elseif($model->type==2): ?>
                        <div class="form-group" id="news">
                            <?php foreach ($model->content as $v):?>
                            <div class='news_html'>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label"><span class="l_span">*</span> 标题</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="标题" data-original-title="标题：不要超过16个字符" name="title[]" maxlength="16" value="<?php echo $v->title; ?>"/><span></span>
                                    </div>
                                </div>
                                <div class="form-group" style="display:none">
                                    <label class="col-sm-2 col-sm-2 control-label"><span class="l_span">*</span> 描述</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="描述" data-original-title="仅限文字" name="description[]" maxlength="20" value="<?php echo $v->description; ?>"/><span></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">图片上传</label>
                                    <div class="col-sm-5">
                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                            <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                                <img src="<?php echo $v->image; ?>" alt="">
                                            </div>
                                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                            <div>
                                                <span class="btn btn-default btn-file">
                                                    <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片 </span>
                                                    <span class="fileupload-exists"><i class="fa fa-undo"></i> 重 选</span>
                                                    <input type="file" class="default" name="file[]" />
                                                </span>
                                                <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> 移 除</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label"><span class="l_span">*</span> URL</label>
                                    <div class="col-sm-5">
                                        <input type="url" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="要加 http:// 或 fttp" data-original-title="http://www.baidu.com" name="url[]" maxlength="100" value="<?php echo $v->url; ?>"/><span></span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php elseif($model->type==3): ?>
                        <input type="hidden" class="form-control" value="<?php echo $model->content; ?>"/><span></span>
                        <div class="form-group" id="images">
                            <label class="control-label col-sm-2">上传图片</label>
                            <div class="col-sm-5">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                        <img src="<?php echo $model->filename; ?>" alt="图片">
                                    </div>
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                    <div>
                                        <span class="btn btn-default btn-file">
                                            <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片 </span>
                                            <span class="fileupload-exists"><i class="fa fa-undo"></i> 重 选</span>
                                            <input type="file" class="default" name="files" />
                                        </span>
                                        <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> 移 除</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="form-group">
                            <label class="control-label col-sm-2"><span class="l_span">*</span> 上传视频</label>
                            <div class="col-sm-5">
                                <span class="help-inline">视频不能超过20M，超过20M的视频可至腾讯视频上传后添加，也可通过添加视频详情页链接以及公众号文章链接插入视频，视频时长不少于1秒，不多于10小时，支持大部分主流视频格式</span><br />
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div>
                                        <span class="btn btn-default btn-file">
                                            <span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择视频 </span>
                                            <span class="fileupload-exists"><i class="fa fa-undo"></i> 重 选</span>
                                            <input type="file" class="default" name="vfile" />
                                            <br /><input type="hidden" class="m-wrap large" name="vefile" value='<?php echo $model->content->media_id; ?>'/><video width="320" height="240" controls><source src="<?php echo $model->filename; ?>" type="video/mp4"><source src="#" type="video/ogg">您的浏览器不支持 video 标签。</video>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><span class="l_span">*</span> 标题</label>
                            <div class="col-sm-5"> 
                                <input type="text" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="标题" data-original-title="标题：不要超过21个字符" name="vtitle" maxlength="21" value="<?php echo $model->content->vtitle; ?>"/><span></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><span class="l_span">*</span> 描述</label>
                            <div class="col-sm-5">
                                <textarea name="vintroduction"  class="form-control"  placeholder="不要超过200个字"><?php echo $model->content->vintroduction; ?></textarea>
                                <span>仅限文字</span>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><span class="l_span">*</span> 排序</label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control tooltips" data-trigger="hover" data-toggle="tooltip" title="" placeholder="排序" data-original-title="回复多条消息时，按照排序从小到大进行排序" name="sort" maxlength="20" value='<?php echo $model->sort; ?>'/><span>回复多条消息时，按照排序从小到大进行排序</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"><span class="l_span">*</span> 状态</label>
                            <div class="col-sm-9 icheck ">
                                <div class="radio">
                                    <input type="radio" name="state" <?php if($model->state==1): ?>checked="checked"<?php endif; ?> value="1">
                                    <label> 启用 </label>
                                </div>
                                <div class="radio ">
                                    <input type="radio" name="state" value="2"<?php if($model->state==2): ?>checked="checked"<?php endif; ?> >
                                    <label> 禁用 </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button class="btn btn-primary" type="submit">保 存</button>
                                <button class="btn btn-default" type="reset">重 写</button>
                                <button class="btn btn-success" style="display:none"><i class="fa fa-plus"></i> 增加图文</button>
                                <button class="btn btn-danger dan-btn" style="display:none"><i class="fa fa-trash-o"></i> 删除图文</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</section>
<script src="/media/js/bootstrap-fileupload.min.js"></script>
<script src="/media/js/layer/layer.js"></script>
<script src="/media/js/autoresponse.js"></script>
<script>
    i = "<?php echo $count; ?>";
    if(i==10){
        $('.btn-success').hide();
        $('.btn-danger').show();
    }else if(i==1){
        $('.btn-danger').hide();
        $('.btn-success').show();
    }else if(i==0){
        $('.btn-success').hide().next().hide();
    }else{
        $('.btn-success').show().next().show();
    }
</script>