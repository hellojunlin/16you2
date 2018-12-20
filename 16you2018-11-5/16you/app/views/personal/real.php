<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>实名认证</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']; ?>/pc/css/Account-security.css">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/pc/js/rem.js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/pc/js/jquery.min.js"></script>
</head>
<script>
var data = {};
data.page = 'real';
data.title =  "实名认证";
data.state = 'start';
window.parent.postMessage(data, '*');
</script>
<body>
	<div class="validation-sw mar_btm2" style="margin-top:0px;" >
		<div class="bind_top">
			<div class="bind_phone" onclick="location.href='/personal/realedit.html'">
				<span class="binding">实名认证</span>
				<p class="bind_Boolean">
					<a class="c_sw" href="#">
						
					</a>
					<em class="str_q">
						<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/right_btn.png">
					</em>
				</p>
			</div>
		</div>
	</div>
	<div class="validation-sw">
		<div class="bind_bootom">
			<div class="bind_alipay brdBttom-1" onclick="location.href='/personal/usergo.html'">
				<span class="binding">用户指引</span>
				<p class="bind_Boolean">
					<a class="c_sw" href="#">
						
					</a>
					<em class="str_q">
						<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/right_btn.png">
					</em>
				</p>
			</div>
			<div class="bind_qq " onclick="location.href='/personal/dispute.html'">
				<span class="binding ">纠纷处理方式</span>
				<p class="bind_Boolean">
					<a class="c_sw" href="#">
						
					</a>
					<em class="str_q">
						<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/right_btn.png">
					</em>
				</p>
			</div>
		</div>
	</div>
</body>
</html>
<script>
$(".mt_goback").click(function(){
	history.go(-1);
})

	 //通知app该页面
	   window.onload=function(){
		   var data = {};
		   data.page = 'real';
		   data.title =  "实名认证";
		   data.state = 'end';
	       window.parent.postMessage(data, '*');
	   }	
</script>