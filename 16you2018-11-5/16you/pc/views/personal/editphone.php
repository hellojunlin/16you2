<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']; ?>/pc/css/common.css">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']; ?>/pc/css/bind.css">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/pc/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/pc/js/rem.js"></script>
	<title>更改手机号</title>
</head>
<body>
    <header class="mt_head">
        <span class="mt_goback"><img class="mt-dow" src="<?php echo yii::$app->params['cdn16you']; ?>/images/back.png"></span>
        <h1 class="mt_h">更改手机号</h1>
    </header>
    <!-- 未绑定状态 -->
	<div id="bindbox">
		<div class="tishi">
			<span class="old_tag tag_on">1.原手机号检验</span>
			<span class="new_tag">2.新手机号设置</span>
		</div>
		<!--步骤一-->
		<div id="step1">
			<div class="alipay_accountbox">
				<div class="sale_infor changephone">
					<span>手机号</span>
					<p><input id="old_phonenum" type="text" placeholder="<?php echo $phone; ?>" disabled="disabled"></p>
				</div>
				<div class="sale_infor changephone">
					<span>验证码</span>
					<p><input id="codenum" type="text" placeholder="请输入验证码"></p>
					<input class="getcode" type="button" onclick="settime(this)" value="获取验证码">
				</div>
			</div>
		    <div class="bind-btn n_step">
		    	<a id="nextstep">下一步</a>
		    </div>
		    <p class="alipay-tips warm-tips">
		    	为确保账户安全，修改手机号码前，需进行身份校验
		    </p>
		</div>
		<!--步骤二-->
		<div id="step2" style="display:none">
			<div class="alipay_accountbox">
				<div class="sale_infor changephone">
					<span>手机号</span>
					<p><input id="new_phonenum" type="text" placeholder="请填写新的手机号"></p>
				</div>
				<div class="sale_infor changephone">
					<span>验证码</span>
					<p><input id="newcodenum" type="text" placeholder="请输入验证码"></p>
					<input class="getcode" type="button" onclick="settimeother(this)" value="获取验证码">
				</div>
			</div>
		    <div class="bind-btn c_step">
		    	<a id="check-change">确认修改</a>
		    </div>
		    <p class="alipay-tips warm-tips">
		    	为确保账户安全，修改手机号码前，需进行身份校验
		    </p>
		</div>
	</div>
	<!--modal-->
	<div id="tipsmodal" style="display:none;"></div>
	<script>
	//步骤一获取验证码
	var countdown = 60;
	function settime(val){
		var getnum = $("#old_phonenum").attr('placeholder');
		$.ajax({
			url:'/personal/addsms.html',
			data:{'phone':getnum},
			type:'post',
			dataType:'json',
			success:function(data){
				if(data.errorcode==0){
					$('#tipsmodal').text('请注意查收短信通知').show();
					setTimeout(function(){//自动关闭
						$('#tipsmodal').hide();
						check(val);
					},1500);
				}else{
					$('#tipsmodal').text(data.info).show();
					setTimeout(function(){//自动关闭
						$('#tipsmodal').hide();
					},2000);
				}
			}
		})
	}
	//步骤二获取验证码
	function check(val){
		if(countdown == 0){
			val.removeAttribute('disabled');
			val.value = '获取验证码';
			countdown = 60;
		}else{
			val.setAttribute('disabled',true);
			val.value = countdown +'s后可重发';
			countdown--;
			setTimeout(function(){
				check(val);
			},1000)
		}
	}
	//新手机号设置获取验证码
	function settimeother(val){
		countdown = 60;
		var getcodenum = $('#new_phonenum').val();
		if((/^(13[0-9]|14[0-9]|15[0-9]|18[0-9])\d{8}$/).test(getcodenum)){
			$.ajax({
				url:'/personal/addsms.html',
				data:{'phone':getcodenum},
				type:'post',
				dataType:'json',
				success:function(data){
					if(data.errorcode==0){
						check(val);
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
	//下一步
	$('.n_step').click(function(){
		var codenum = $('#codenum').val();
		if(codenum==''){
			$('#tipsmodal').text('请输入验证码').show();
			setTimeout(function(){//自动关闭
				$('#tipsmodal').hide();
			},2000);
			return false;	
		}else{
			$.ajax({
				url:'/personal/smscode.html',
				data:{'codenum':codenum},
				type:'post',
				dataType:'json',
				success:function(data){
					if(data.errorcode==0){
						$('#step1').hide();
						$('#step2').show();
						$('.old_tag').removeClass('tag_on');
						$('.new_tag').addClass('tag_on');
					}else{
						$('#tipsmodal').text(data.info).show();
						setTimeout(function(){//自动关闭
							$('#tipsmodal').hide();
						},2000);
					}
				}
			})
			
		}
	})
	//确认修改
	$('.c_step').click(function(){
		var n_p = $('#new_phonenum').val();
		var n_c = $('#newcodenum').val();
		if(n_p =='' || n_c ==''){
			$('#tipsmodal').text('请填写完整信息').show();
			setTimeout(function(){//自动关闭
				$('#tipsmodal').hide();
			},2000);
			return false;
		}else if(!(/^(13[0-9]|14[0-9]|15[0-9]|18[0-9])\d{8}$/).test(n_p)){
			$('#tipsmodal').text('请输入正确的手机号码').show();
			setTimeout(function(){//自动关闭
				$('#tipsmodal').hide();
			},2000);
			return false;
		}else{
			$.ajax({
				url:'/personal/createphone.html',
				data:{'num':n_p,'code':n_c},
				type:'post',
				dataType:'json',
				success:function(data){
					if(data.errorcode==0){
						$('#tipsmodal').text('修改成功').show();
						setTimeout(function(){//自动关闭
							location.href="/personal/tophone.html";
						},2000);
					}else{
						$('#tipsmodal').text(data.info).show();
						setTimeout(function(){//自动关闭
							$('#tipsmodal').hide();
						},2000);
					}
				}
			})
			
		}
	})
	$(".mt_goback").click(function(){
		history.go(-1);
	})
	</script>
</body>
</html>