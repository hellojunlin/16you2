<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-sacale=1,user-scalable=no">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/css/eleven/recharge.css">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/rem.js"></script>
	<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<title>充值</title>
	<script>
	wx.config({
		<?php $signPackage = yii::$app->session->get('signPackage');?>
		debug: false, 
	    appId: '<?php echo $signPackage["appId"];?>',
	    timestamp: '<?php echo $signPackage["timestamp"];?>',
	    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
	    signature: '<?php echo $signPackage["signature"];?>',
	    jsApiList: [
			//所有要调用的 API 都要加到这个列表中 
			'onMenuShareAppMessage',
			'hideMenuItems'
	    ]
	  });
	  wx.ready(function () {
	    // 在这里调用 API 
		//分享朋友
		wx.onMenuShareAppMessage({
		    title: '16游双十一活动', // 分享标题
		    desc: '吃了这么多狗粮，是时候用它换点小钱花花了', // 分享描述
		    link: "<?php echo yii::$app->params['frontends'];?>/index/index.html?actpid=<?php echo $uid;?>&openid=<?php echo $openid;?>",// 分享链接
		    imgUrl: "<?php echo yii::$app->params['frontends'];?>/media/images/icon_mean.png", // 分享图标
		    type: 'link', // 分享类型,music、video或link，不填默认为link
		    success: function () { 
		    	$('.sharebox').hide();
		    	$(".chargestate").hide();
		    	$(".tipmodal").hide();
		    	$('.beforesharemodal').show();
		    	//alert('分享成功');
		    },
		    cancel: function () { 
		    }
		}); 
		//隐藏分享QQ
		wx.hideMenuItems({
		 	    menuList: ['menuItem:share:qq','menuItem:share:timeline'] // 要隐藏的菜单项，只能隐藏“传播类”和“保护类”按钮，所有menu项见附录3
		});
	  });
  </script>
</head>
<body>
	<div class="rechargebox">
	    <div class="topimg"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/eleven/topimg.png"></div>
		<div class="havebox">
			<?php if($num): ?>
			<div class="haveimg"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/eleven/rcimg1.png"></div>
			<div class="havetip">您已获得充值狗粮<span class="havenum"><?php echo $num;?></span>袋</div>
			<?php else: ?>
			<!--木有狗粮的状态-->
			<div class="haveimg"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/eleven/rcimg2.png"></div>
			<div class="havetip">您的仓库空空如也</div>
			<?php endif; ?>
		</div>
		<div class="gogame">
			<p class="gotips">点击下面脚掌,即刻去游戏</p>
			<div class="intoimg" onclick="window.location.href='/index/index.html'">
				<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/eleven/rcimg3.png">
				<p class="intotip">去游戏</p>
			</div>
		</div>
	</div>
	<!--分享之后弹框-->
	<div class="beforesharemodal">
		<div class="beforeshare">
			<img class="elebk" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/eleven/elebk.png">
			<p class="sharesucc">分享成功</p>		
		</div>
	</div>
	<script type="text/javascript">
		$('.beforesharemodal').click(function(){
	    	$('.beforesharemodal').hide();
	    })
	</script>
</body>
</html>