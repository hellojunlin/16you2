<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>账户安全</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/css/Account-security.css">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/rem.js"></script>
</head>
<body>
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
						<img src="/media/images/right_btn.png">
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
						<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/right_btn.png">
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
						<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/right_btn.png">
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
						<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/right_btn.png">
					</em>
				</p>
			</div>
		</div>
	</div>
</body>
</html>