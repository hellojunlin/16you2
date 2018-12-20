<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/pc/css/common.css">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/pc/css/bind.css">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/pc/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/pc/js/rem.js"></script>
	<title>纠纷处理方式</title>
</head>
<body>
	 <header class="mt_head" id="head" onclick="window.history.go(-1)">
        <span class="mt_goback"><img class="mt-dow" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/back.png"></span>
        <h1 class="mt_h">纠纷处理方式</h1>
    </header> 
    <!-- 未绑定状态 -->
	<div id="bindbox">
		<div class="safe_top">
			<em><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/safe.png"></em>
			<span>
				请填写网页登录密码<br>
				（6-16位阿拉伯数字）
			</span>
		</div>
		<div class="fillinput">
			<div class="fillqq">
				<input class="inputinfo" type="text" placeholder="请填写网页登录密码（6-16位阿拉伯数字）" value="<?php echo $pwd; ?>" maxlength='16'>
			</div>
		</div>
	    <div class="bind-btn">
	    	<a id="bind-mobile">确定修改</a>
	    </div>
	</div>
	<!--modal-->
	<div id="tipsmodal" style="display:none;"></div>
	<script>
	//修改密码
	$('.bind-btn').click(function(){
		var pwd = $('.fillqq input').val();
		if(pwd==''){
			$('#tipsmodal').text('登录密码不能为空').show();
			setTimeout(function(){//自动关闭
				$('#tipsmodal').hide();
			},2000);
			return false;	
		}else if(!(/^[1-9][0-9]{4,14}$/).test(pwd)){
			$('#tipsmodal').text('请填写网页登录密码（6-16位阿拉伯数字）').show();
			setTimeout(function(){//自动关闭
				$('#tipsmodal').hide();
			},2000);
			return false;	
		}else{
			$.ajax({
				url:'/personal/addpwd.html',
				type:'post',
				dataType:'json',
				data:{'pwd':pwd},
				success:function(data){
					if(data.errorcode==0){
						$('#tipsmodal').text('修改成功').show();
						setTimeout(function(){//自动关闭
							location.href="/personal/alipay.html";
						},2000);
					}else{
						$('#tipsmodal').text(data.info).show();
						setTimeout(function(){//自动关闭
							$('#tipsmodal').hide();
						},2000);
					}
				}
			})
			return false;	
		}
	})
	</script>
</body>
</html>