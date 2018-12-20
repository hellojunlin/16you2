<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>账户安全</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']; ?>/pc/css/Account-security.css">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/pc/js/rem.js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/pc/js/jquery.min.js"></script>
</head>
<body>
    <header class="mt_head">
        <span class="mt_goback"><img class="mt-dow" src="<?php echo yii::$app->params['cdn16you']; ?>/images/back.png"></span>
        <h1 class="mt_h">账户安全</h1>
    </header>
	<div class="validation-sw mar_btm1">
		<div class="bind_top">
			<!-- <div class="brdBttom-1" onclick="location.href='/personal/tophone.html'">
				<span class="binding">绑定手机号码</span>
				<p class="bind_Boolean">
					<?php if(!$phone): ?>
					<a class="c_sw" href="#">未绑定</a>
					<?php else: ?>
					<a class="c_sw" style="color:#999"><?php echo $phone; ?></a>
					<?php endif; ?>
					<em class="str_q">
						<img src="http://wx.16you.com/media/images/right_btn.png">
					</em>
				</p>
			</div> -->
			<div class="bind_alipay " onclick="location.href='/personal/alipayedit.html'">
				<span class="binding">绑定支付宝账户</span>
				<p class="bind_Boolean">
					<?php if(!$alipayaccount): ?>
					<a class="c_sw" href="#">未绑定</a>
					<?php else: ?>
					<a class="c_sw" style="color:#999"><?php echo $alipayaccount; ?></a>
					<?php endif; ?>
					<em class="str_q">
						<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/right_btn.png">
					</em>
				</p>
			</div>
		</div>
	</div>
	<div class="validation-sw">
		<div class="bind_bootom" onclick="location.href='/personal/toqq.html'">
			<div class="bind_qq ">
				<span class="binding">联系QQ号</span>
				<p class="bind_Boolean">
					<?php if(!$qq): ?>
					<a class="c_sw" href="#">未绑定</a>
					<?php else: ?>
					<a class="c_sw" style="color:#999"><?php echo $qq; ?></a>
					<?php endif; ?>
					<em class="str_q">
						<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/right_btn.png">
					</em>
				</p>
			</div>
		</div>
	</div>
	<div class="validation-sw">
		<div class="bind_bootom" onclick="location.href='/personal/topwd.html'">
			<div class="bind_pwd">
				<span class="binding">网页端登录密码</span>
				<p class="bind_Boolean">
					<a class="c_sw" href="#" style="color:#999"><?php echo $password; ?></a>
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
//返回-
$(".mt_goback").click(function(){
	history.go(-1);
})

</script>