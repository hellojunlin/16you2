<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/css/common.css">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/css/bind.css">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/rem.js"></script>
	<title>实名认证</title>
</head>
<body>
    <!-- 未绑定状态 -->
	<div id="bindbox" style="<?php if($IDcard){echo 'display:none';} ?>">
		<div class="actiontxt">
			<span><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/tip_icon.png"></span>
			根据最新监管要求，进行游戏需要身份验证
		</div>
		<div class="alipay_accountbox">
			<div class="sale_infor zsname">
				<span>真实姓名</span>
				<p><input maxlength="16" id="username" type="text" placeholder="请输入您的真实姓名"></p>
			</div>
			<div class="sale_infor zsname">
				<span>身份证号</span>
				<p><input id="idcard" type="text" placeholder="请输入身份证号码"></p>
			</div>
		</div>
	    <div class="bind-btn">
	    	<a id="bind-mobile">提交认证</a>
	    </div>
	</div>
	<!--已绑定状态-->
	<div id="hasbindbox" style="<?php if(!$IDcard){echo 'display:none';} ?>">
		<div class="actiontxt">
			<span class="successicon">✓</span>
			您已通过实名认证
		</div>
		<div class="alipay_accountbox">
			<div class="sale_infor zsname">
				<span>真实姓名</span>
				<p><input id="getusername" type="text" placeholder="<?php echo $realname; ?>" disabled="disabled"></p>
			</div>
			<div class="sale_infor zsname">
				<span>身份证号</span>
				<p><input id="getidcard" type="text" placeholder="<?php echo $IDcard; ?>" disabled="disabled"></p>
			</div>
		</div>
	</div>
	<!--modal-->
	<div id="tipsmodal" style="display:none;"></div>
	<script>
	//绑定手机号码
	$('.bind-btn').click(function(){
		var username = $('#username').val();
		var idcard = $('#idcard').val();
		if(username=='' || idcard ==''){
			$('#tipsmodal').text('请填写完整信息').show();
			setTimeout(function(){//自动关闭
				$('#tipsmodal').hide();
			},2000);
			return false;	
		}else if(!(/^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/).test(idcard)){
			$('#tipsmodal').text('身份证号码不正确').show();
			setTimeout(function(){//自动关闭
				$('#tipsmodal').hide();
			},2000);
			return false;
		}else{
			$.ajax({
				url:'/personal/realadd.html',
				type:'post',
				dataType:'json',
				data:{'realname':username,'IDcard':idcard},
				success:function(data){
					if(data.errorcode==0){
						$('#tipsmodal').text(data.info).show();
						var u_n = username.substring('0','1');
						var uname = u_n + '**';
						var u_idfront = idcard.substring('0','3');
						var u_idbehind = idcard.substr(-4);
						var uidcard = u_idfront + '***********' + u_idbehind;
						$('#getusername').attr('placeholder',uname);
						$('#getidcard').attr('placeholder',uidcard);
						setTimeout(function(){//自动关闭
							$('#tipsmodal').hide();
							$('#bindbox').hide();
							$('#hasbindbox').show();
						},1500);
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