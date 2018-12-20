<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/pc/css/common.css">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/pc/css/bind.css">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/pc/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/pc/js/rem.js"></script>
	<title>绑定支付宝账户</title>
</head>
<body>
  <header class="mt_head">
        <span class="mt_goback"><img class="mt-dow" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/back.png"></span>
        <h1 class="mt_h">绑定支付宝账户</h1>
    </header> 
    <!-- 未绑定状态 -->
	<div id="bindbox" style="<?php if($alipayaccount){echo 'display:none';} ?>">
		<div class="actiontxt">
			<span><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/tip_icon.png"></span>
			填写支付宝账户，确认后不可更改
		</div>
		<div class="alipay_accountbox">
			<div class="sale_infor">
				<span>姓名</span>
				<p><input maxlength="16" id="alipay_name" type="text" placeholder="请输入支付宝姓名"></p>
			</div>
			<div class="sale_infor">
				<span>账号</span>
				<p><input id="alipay_account" type="text" placeholder="请输入支付宝账号"></p>
			</div>
		</div>
	    <div class="bind-btn">
	    	<a id="bind-mobile">确认信息</a>
	    </div>
	    <p class="alipay-tips warm-tips">
	    	<span>注意：</span>
	    	请确保支付宝姓名与支付宝账户登记信息一致，否则会导致打款失败，请认真填写
	    </p>
	</div>
	<!--已绑定状态-->
	<div id="hasbindbox" style="<?php if(!$alipayaccount){echo 'display:none';} ?>">
		<div class="actiontxt">
			<span class="successicon">✓</span>
			您已绑定支付宝账户
		</div>
		<div class="alipay_accountbox">
			<div class="sale_infor">
				<span>姓名</span>
				<p><input  id="getalipay_name" type="text" placeholder="<?php echo $alipayname; ?>" disabled="disabled"></p>
			</div>
			<div class="sale_infor">
				<span>账号</span>
				<p><input id="getalipay_account" type="text" placeholder="<?php echo $alipayaccount; ?>" disabled="disabled"></p>
			</div>
		</div>
	</div>
	<!--modal-->
	<div id="tipsmodal" style="display:none;"></div>
	<script>
	//绑定支付宝账号
	$('.bind-btn').click(function(){
		var name = $('#alipay_name').val();
		var account = $('#alipay_account').val();
		var getname = name.substring('0','1');
		var namestr = getname + '**';
		var ac_front = account.substring('0','3');
		var ac_behind = account.substr(-3);
		var accountstr = ac_front + '*****' + ac_behind;
		if(name=='' || account ==''){
			$('#tipsmodal').text('请填写完整信息').show();
			setTimeout(function(){//自动关闭
				$('#tipsmodal').hide();
			},2000);
			return false;	
		}else if(!(/^(13[0-9]|14[0-9]|15[0-9]|18[0-9])\d{8}$/).test(account)){
			if(!(/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/).test(account)){
				console.log('xxx');
				$('#tipsmodal').text('支付宝账号不正确').show();
				setTimeout(function(){//自动关闭
					$('#tipsmodal').hide();
				},2000);
				return false;
			}else{
				toajax(name,account);
			}
		}else{
			toajax(name,account);
		}
	})
	function toajax(name,account){
		$.ajax({
			url:'/personal/alipayadd.html',
			type:'post',
			dataType:'json',
			data:{'alipayname':name,'alipayaccount':account},
			success:function(data){
				if(data.errorcode==0){
					$('#tipsmodal').text('绑定成功').show();
					setTimeout(function(){//自动关闭
						location.href="/personal/alipay.html";
					},2000);
					return false;	
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
	$(".mt_goback").click(function(){
		history.go(-1);
	})
	</script>
</body>
</html>