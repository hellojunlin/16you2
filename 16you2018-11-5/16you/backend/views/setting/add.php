<?php 
use yii\helpers\Url;
?>
<script type="text/javascript" src="/media/js/My97DatePicker/WdatePicker.js"></script>
<div class="page-heading">
    <h3>分成比例设置</h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">分成比例设置</a>
        </li>
        <li>
            <a href="/setting/proportion.html">重置分成比例</a>
        </li>
        <li class="active"> 添加 </li>
    </ul> 
</div>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">分成比例设置</header>
                <div class="panel-body">
                    <form class="form-horizontal adminex-form" method="post" action="<?=Url::to(['create'])?>" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">公司名称</label>
                            <div class="col-sm-5">
                               <select class="form-control tooltips" tabindex="3" name="cid" id="role">
                                <?php if($model){foreach ($model as $res):?>
                                <option value="<?php echo $res['id'];?>"><?php echo $res['compname'];?></option>
                                <?php endforeach;}?>
                               </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span style="color:#e00">*</span> 分成比例</label>
                            <div class="col-sm-5">
                                <div class="input-group m-bot15">
                                    <span class="input-group-addon">游戏方</span>
                                    <input type="number" class="form-control" name="game" value="50" >
                                    <span class="input-group-addon">%</span>
                                </div>
                                <div class="input-group m-bot15">
                                    <span class="input-group-addon">平台方</span>
                                    <input type="number" class="form-control" name="plate" value="50">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">有效开始时间</label>
                            <input type="text" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-d'})"style="width:170px;"placeholder="有效开始时间"value="" class="form-control tooltips input-text Wdate" name="effective_time" required>
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
<script src="/media/js/layer/layer.js"></script>
<script>
$("input[name='game']").change(function(){
    var val = $(this).val();
    if(val>100){
        val = val.substr(0,2);
        $(this).val(val);
    }
    $('input[name="plate"]').val(100-val);
});
$("input[name='plate']").change(function(){
    var val = $(this).val();
    if(val>100){
        val = val.substr(0,2);
        $(this).val(val);
    }
    $('input[name="game"]').val(100-val);
});
</script>