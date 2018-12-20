<?php 
use yii\helpers\Url;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<meta name="description" content="">
<meta name="author" content="ThemeBucket">
<link href="/media/css/style.css" rel="stylesheet">
<link href="/media/css/style-responsive.css" rel="stylesheet">
<link href="/media/css/common.css" rel="stylesheet">
</head>
<body class="sticky-header">
    <div class="main-content" style="padding:0px">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <div class="panel-body">
                            <form class="form-horizontal adminex-form" method="post">
                                <?php if($plate): ?>
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">选择平台</label>
                                    <div class="col-sm-5">
                                        <select class="form-control tooltips" tabindex="3" name="pid" id="pid">
                                            <!-- <option value=" ">所有平台</option> -->
                                            <?php foreach ($plate as $v):?>
                                            <?php if(isset($v['id'])): ?>
                                            <option value="<?php echo $v['id'].'@!!'.$v['pname'];?>"><?php echo $v['pname'];?></option>
                                            <?php endif; ?>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span style="color:#e00">*</span>开始时间</label>
                                     <div class="col-sm-5">
                                    <input type="text" onfocus="WdatePicker({ maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}' })" id="logmin" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="开始时间"  name="start_time" required></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"><span style="color:#e00">*</span>最后时间</label>
                                     <div class="col-sm-5">
                                    <input type="text" onfocus="WdatePicker({ minDate:'#F{$dp.$D(\'logmin\')}',maxDate:'%y-%M-%d' })" id="logmax" class="form-control tooltips input-text Wdate" style="width:170px;"placeholder="最后时间" name="end_time" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-5">
                                        <button class="btn btn-warning" id="download"><i class="fa fa-cloud-download"></i> 导出Excel </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
<script src="/media/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="/media/js/My97DatePicker/WdatePicker.js"></script>
<script src="/media/js/layer/layer.js"></script>
<script>
    $("#download").click(function(){
        var start_time = $("input[name='start_time']").val();
        var end_time = $("input[name='end_time']").val();
        var pid = $("select[name='pid']").val();
        if(start_time.length>0&&end_time.length>0){
            var index = layer.load();
            window.location.href="/count/output.html?start_time="+start_time+'&end_time='+end_time+'&pid='+pid;
            //添加数据成功，关闭弹出窗
            setTimeout(function(){
                layer.close(index);
                // var index = parent.layer.getFrameIndex(window.name);
                // parent.layer.close(index);
            }, 10000);
            
        }else{
            layer.msg('请选择时间');
        }
        return false;
    })
</script>