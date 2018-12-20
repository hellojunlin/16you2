<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/pc/css/common.css">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/pc/css/bind.css">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/pc/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/pc/js/rem.js"></script>
</head>
<body>
   <!-- <header class="mt_head">
        <span class="mt_goback"><img class="mt-dow" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/back.png"></span>
        <h1 class="mt_h">绑定QQ</h1>
    </header> --> 
    <!-- 未绑定状态 -->
	<div id="bindbox">
		<div class="safe_top">
			<em><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/qq_ico.png"></em>
			<span>
				请填写联系QQ<br>
				方便客服与您沟通
			</span>
		</div>
		<div class="fillinput">
			<div class="fillqq">
				<input class="inputinfo" type="tel" placeholder="出售账号必须填写联系QQ" value="<?php echo $qq; ?>">
			</div>
		</div>
	    <div class="bind-btn">
	    	<a id="bind-mobile">立即绑定</a>
	    </div>
	</div>
	<!--modal-->
	<div id="tipsmodal" style="display:none;"></div>
	<script>
	//绑定手机号码
	$('.bind-btn').click(function(){
		var qq = $('.fillqq input').val();
		if(qq==''){
			$('#tipsmodal').text('联系QQ不能为空').show();
			setTimeout(function(){//自动关闭
				$('#tipsmodal').hide();
			},2000);
			return false;	
		}else if(!(/^[1-9][0-9]{4,9}$/).test(qq)){
			$('#tipsmodal').text('QQ号码不正确').show();
			setTimeout(function(){//自动关闭
				$('#tipsmodal').hide();
			},2000);
			return false;	
		}else{
			$.ajax({
				url:'/personal/addqq.html',
				type:'post',
				dataType:'json',
				data:{'qq':qq},
				success:function(data){
					if(data.errorcode==0){
						$('#tipsmodal').text('绑定成功').show();
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
	//返回-
	$(".mt_goback").click(function(){
		history.go(-1);
	})
	</script>
</body>
</html>