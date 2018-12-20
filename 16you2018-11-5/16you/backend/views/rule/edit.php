<?php 
use yii\helpers\Url;
?>
<link href="/media/css/common.css" rel="stylesheet">
<script type="text/javascript" charset="utf-8" src="/media/js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/media/js/ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/media/js/ueditor/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript" src="/media/js/My97DatePicker/WdatePicker.js"></script>
<div class="page-heading">
    <h3>规则管理</h3>
    <ul class="breadcrumb">
        <li> 
            <a href="<?=Url::to(['index'])?>">规则管理</a>
        </li>
        <li>
            <a href="/product/index.html">规则列表</a>
        </li>
        <li class="active"> 添加 </li>
    </ul> 
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">规则管理</header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" method="post" action="<?=Url::to(['create'])?>" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $model->id; ?>">
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 规则名称</label>
                            <div class="col-sm-5">
                               <select class="form-control tooltips" tabindex="3" name="type" id="rule">
                                <?php foreach ($rule as $k=>$r):?>
                                <option value="<?php echo $k;?>" <?php if($model->type==$k){echo 'selected';} ?>><?php echo $r;?></option>
                                <?php endforeach;?>
                               </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 规则详情</label>
                            <div class="col-sm-9">
                                <textarea name="content" id="editor" style="height:300px" required><?php echo $model->content; ?></textarea>            
                            </div>
                        </div>
                        <div class="form-group" id="timetype" style="display:none">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 活动时间</label>
                            <div class="col-sm-5">
                                <label><input id="start_time" class="form-control tooltips input-text Wdate" type="text"  value="<?php  echo $model->starttime?date('Y-m-d',$model->starttime):'';?>" name="starttime" style="width: 170px;"
                                            onfocus="WdatePicker({dateFm:'yyyy-MM-dd',minDate:''})" placeholder="开始时间"/>
                                           </label> -- 
                                           <label><input id='end_time' class="form-control tooltips input-text Wdate" type="text"  value="<?php echo $model->endtime?date('Y-m-d',$model->endtime):'';?>" name="endtime" style="width: 170px;"
                                            onfocus="WdatePicker({dateFm:'yyyy-MM-dd',maxDate:''})" placeholder="结束时间"/></label>
                                    <label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">状态</label>
                            <div class="col-sm-9 icheck ">
                                <div class="square-blue">
                                    <div class="radio ">
                                        <div class="iradio_square-blue" style="position: relative;"><input tabindex="3" type="radio" name="state" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);" <?php if($model->state==1){echo 'checked';} ?> value="1"></div>
                                        <label> 启用 </label>
                                    </div>
                                </div>
                                <div class="square-red">
                                    <div class="radio ">
                                        <div class="iradio_square-red" style="position: relative;"><input tabindex="3" type="radio" name="state" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"<?php if($model->state==0){echo 'checked';} ?> value="0"></div>
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
<script>
var ue = UE.getEditor('editor');
var selected = $('#rule').children('option:selected').val();
if(selected==1){
    $("#timetype").css('display','block');
}else{
    $("#timetype").css('display','none');
}
$('#rule').change(function(){
    var selected = $(this).children('option:selected').val();
    if(selected==1){
        $("#timetype").css('display','block');
    }else{
        $("#timetype").css('display','none');
    }
})
</script>