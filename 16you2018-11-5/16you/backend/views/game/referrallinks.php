<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<link rel="stylesheet" href="/media/css/combo.select.css">
<div class="page-heading">
    <h3>
        游戏推广链接
    </h3>
    <ul class="breadcrumb">
        <li>
            <a href="<?=Url::to(['index'])?>">游戏推广链接</a>
        </li>
        <!-- <li class="active">用户记录 </li> -->
    </ul>
</div>
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
            <section class="panel panel-info">
	            <header class="panel-heading">
	               	游戏推广链接
	                <span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                 </span>
	            </header>	
	            <div class="panel-body">
	            	<form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">商户名称:</label>
                            <div class="col-lg-10">
                                <label class="control-label">广州野人网络科技有限公司</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">平台链接:</label>
                            <div class="col-lg-10">
                                <label class="control-label"><?php echo yii::$app->params['frontend']; ?>/index/index<?php echo $pid1;?>.html</label>
                                <p><button class="btn btn-info btn-sm" type="button"data-clipboard-text="<?php echo yii::$app->params['frontend']; ?>/index/index<?php echo $pid1;?>.html">复制链接</button></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">平台二维码</label>
                            <div class="col-lg-10" id="qrcode" lcode="<?php echo yii::$app->params['frontend']; ?>/index/index<?php echo $pid1;?>.html"></div>
                        </div>
                        <?php if($game): ?>
                        <div class="form-group">
                            <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">选择游戏:</label>
                            <div class="col-lg-3">
                                <select class="form-control tooltips" tabindex="3">
                                	<?php foreach ($game as $g):?>
                                    <option value="<?php echo $g['id']; ?>"><?php echo $g['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">推广链接</label>
                            <div class="col-lg-10">
                                <label class="control-label" id="gamecode1"><?php echo yii::$app->params['frontend']; ?>/start/index/<?php echo $game['0']['id'];?>.html?puid=<?php echo $pid; ?></label>
                                <p><button class="btn btn-info btn-sm" type="button" data-clipboard-text="<?php echo yii::$app->params['frontend']; ?>/start/index/<?php echo $game['0']['id'];?>.html?puid=<?php echo $pid; ?>">复制链接</button></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">游戏二维码</label>
                            <div class="col-lg-10" id="gamecode" lcode="<?php echo yii::$app->params['frontend']; ?>/start/index/<?php echo $game['0']['id'];?>.html?puid=<?php echo $pid; ?>"></div>
                        </div>
                    	<?php endif; ?>
                    </form>
	            </div>
	        </section>
        </div>
	</div>
</section>
<script src="/media/js/qrcode.js"></script>
<script src="/media/js/clipboard.min.js"></script>
<script src="/media/js/jquery.combo.select.js"></script>
<script>  
    $(function() {
        $('.tooltips').comboSelect();
    });      
	window.onload =function(){
		//平台二维码
        var qrcode = new QRCode(document.getElementById("qrcode"));
        qrcode.makeCode($("#qrcode").attr('lcode'));
        //游戏二维码
        var gamecode = new QRCode(document.getElementById("gamecode"));
        gamecode.makeCode($("#gamecode").attr('lcode'));
    }
    //选择游戏
    $(".tooltips").change(function(){
    	var _obj = $(this);
    	var _val = _obj.val();
    	var url = "<?php echo yii::$app->params['frontend']; ?>/start/index/"+_val+".html?puid=<?php echo $pid; ?>";
    	$("#gamecode1").html(url).parents('.col-lg-10').find('.btn-sm').attr('data-clipboard-text',url);
    	$("#gamecode").attr('lcode',url).empty();
    	var qrcode = new QRCode(document.getElementById("gamecode"));
    	qrcode.makeCode(url);
    })
    //复制链接
    var clipboard = new Clipboard('.btn-sm');
	clipboard.on('success', function(e) {
		alert("复制链接成功");
	    e.clearSelection();
	}).on('error', function(e) {
		alert("复制失败");
	});
</script>