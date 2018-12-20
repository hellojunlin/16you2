<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/css/common.css">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/css/bind.css">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/rem.js"></script>
	<title>绑定手机号</title>
</head>
<body>
    <!-- 未绑定状态 -->
	<div id="bindbox" style="<?php if($phone){echo 'display:none';} ?>">
		<div class="safe_top">
			<em><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/safe.png"></em>
			<span>
				为了您的账户安全<br>
				请及时绑定手机
			</span>
		</div>
		<div class="fillinput">
			<div class="filltel">
				<input class="inputinfo phonenum" type="tel" placeholder="请输入手机号码">
			</div>
			<div class="fillcode">
				<input class="inputinfo phonecode" type="tel" placeholder="请输入验证码">
				<input class="getcode" type="button" onclick="settime(this)" value="获取验证码">
			</div>
		</div>
	    <div class="bind-btn">
	    	<a id="bind-mobile">立即绑定</a>
	    </div>
	    <p class="phone-tips warm-tips">
	        <span>温馨提示：</span>
	    	输入手机号，点击获取语音验证码后，会以电话的形式为您播放验证码，请留意接听来电。
	    </p>
	</div>
	<!-- 已绑定状态 -->
	<div id="hasbindbox" style="<?php if(!$phone){echo 'display:none';} ?>">
		<div class="b_t">
			<em><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/bind.png"></em>
			<p>您已绑定手机</p>
		</div>
		<div class="change-mobile">
			手机号：<span class="hadbindphone"><?php echo $phone; ?></span><a href="/personal/editphone.html">更换</a>
		</div>
	</div>
	<!--modal-->
	<div id="tipsmodal" style="display:none;"></div>
	<script>
	//绑定手机号码
	$('.bind-btn').click(function(){
		var num = $('.filltel input').val();
		var code = $('.fillcode input').val();
		if(num=='' || code ==''){
			$('#tipsmodal').text('请填写完整信息').show();
			setTimeout(function(){//自动关闭
				$('#tipsmodal').hide();
			},2000);
			return false;	
		}else if(!(/^(13[0-9]|14[0-9]|15[0-9]|18[0-9])\d{8}$/).test(num)){
			$('#tipsmodal').text('请输入正确的手机号码').show();
			setTimeout(function(){//自动关闭
				$('#tipsmodal').hide();
			},2000);
			return false;
		}else{
			$.ajax({
				url:'/personal/createphone.html',
				data:{'num':num,'code':code},
				type:'post',
				dataType:'json',
				success:function(data){
					if(data.errorcode==0){
						$('#tipsmodal').text('绑定成功').show();
						$('.hadbindphone').text(num);
						setTimeout(function(){//自动关闭
							$('#tipsmodal').hide();
							$('#bindbox').hide();
							$('#hasbindbox').show();
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
	//获取验证码
	var countdown = 60;
	function ef(val){
		if(countdown ==0){
			val.removeAttribute("disabled");
			val.value = '获取验证码';
			countdown = 60;
		}else{
			val.setAttribute("disabled",true);
			val.value = countdown +'s后可重发';
			countdown--;
			setTimeout(function(){
				ef(val)
			},1000)
		}
	}
	function settime(val){
		var getnum = $('.phonenum').val();
		if((/^(13[0-9]|14[0-9]|15[0-9]|18[0-9])\d{8}$/).test(getnum)){
			$.ajax({
				url:'/personal/addsms.html',
				data:{'phone':getnum},
				type:'post',
				dataType:'json',
				success:function(data){
					if(data.errorcode==0){
						ef(val);
					}else{
						$('#tipsmodal').text(data.info).show();
						setTimeout(function(){//自动关闭
							$('#tipsmodal').hide();
						},2000);
					}
				}
			})
		}else{
			$('#tipsmodal').text('手机号码不正确').show();
			setTimeout(function(){//自动关闭
				$('#tipsmodal').hide();
			},2000);
		}
	}	
	</script>
</body>
</html>