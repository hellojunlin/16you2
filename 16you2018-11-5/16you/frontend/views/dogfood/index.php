<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<title>双11活动</title>
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/css/eleven/dogfood.css">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/rem.js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/jquery.min.js"></script>
	<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
</head>
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
<body>
    <div class="topimg"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/eleven/topimg.png"></div>
	<div class="total">
		<div class="Dog_food">
			<img class="dogL" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/eleven/dogLines.png">
			<div class="dogLines">
				<p class="fs_bg">我的狗粮</p>
				<p class="fs"><?php echo $num;?></p> 
			</div>
		</div>
		<div class="lines">
			<img class="linesImg" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/eleven/lines2.png">
			<div class="lin">
				<p class="fs_bg">换狗粮的钱</p>
				<p class="fs"><?php echo $price;?></p>
			</div>
		</div>
	</div>
	<div class="catDog">
		<div class="invite">
			<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/eleven/inviteImg1.png">
			<p>邀请好友</p>
		</div>
		<div class="recharge" onclick="window.location.href='/dogfood/recharge.html'">
			<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/eleven/rechargeImg1.png">
			<p>充值</p>
		</div>
		<div class="perfect" onclick="window.location.href='/personal/information.html'">
			<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/eleven/fectImg1.png">
			<P>完善信息</P>
		</div>
	</div>
	<div class="middle">
		<div class="mitop">
			<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/eleven/dogs.png">
		</div>
		<div class="mibootom">
			<img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/eleven/exc2.png">
			<!-- <p class="exchange">兑换</p> -->
		</div>
	</div>
	<div class="rule">
		<p class="ruleTitle">
			活动规则
		</p>
		<p class="rule_c">
			1、玩家每邀请一个好友，即可获得5碗狗粮（同个用户多次关注只算一次福利），以此类推，多邀多送；
		</p>
		<p class="rule_c">
			2、玩家在个人中心完善自己的个人信息可获得100碗狗粮；
		</p>
		<p class="rule_c">
			3、个人信息完善内容包括姓名，微信号，QQ（方便后续福利推送）；
		</p>
		<p class="rule_c">
			4、玩家在16游平台充值任意金额即可获得10倍数量的狗粮，如1元=10碗狗粮；
		</p>
		<p class="rule_c">
			5、狗粮兑换标准1111碗狗粮=11元,只能兑换整数金额；
		</p>
		<p class="rule_c">
			6、每人每号每天能无限次兑换，隔天清空前天的狗粮
		</p>
		<p class="rule_c">
			7、获得红包将根据您微信号自动发放到账，领取后请在参与活动的微信号中【钱包】查看；
		</p>
		<p class="rule_c">
			8、在活动期间内（2017-11-10到2017-11-21），由于不可抗拒因素、网络、作弊等原因导致活动异常，最终解释权归16游h5游戏平台所有；
		</p>
	</div>
	<div class="tipmodal">
	    <!--分享-->
	    <div class="sharebox">
	    	<div class="footone"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/eleven/sharefoot.png"></div>
	    	<div class="foottwo"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/eleven/sharefoot.png"></div>
	    	<div class="footthreee"><img src="<?php echo yii::$app->params['cdn16yous']; ?>/images/eleven/sharefoot.png"></div>
	    	<p class="sharetip">点击这里，把16游分享出去<br/>邀请更多好友和你一起玩</p>
	    </div>
	    <!--兑换-->
		<div class="chargestate">
		    <div class="zzdh" style="display:none;">正在兑换中...</div>
			<div class="chargesuccess">
				<img class="chargeimg" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/eleven/tips1.png">
				<p class="chargetip">哦~成功变现1111袋狗粮<br/>继续加油！</p>
			</div>
			<!-- 狗粮不足时兑换 -->
			<div class="chargefail">
				<img class="chargeimg" src="<?php echo yii::$app->params['cdn16yous']; ?>/images/eleven/tips2.png">
				<p class="chargetip">ummm....你获得的狗粮还太少<br/>暂时不能兑换</p>
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
	//分享
	$(".invite").click(function(){
		$('.tipmodal').show();
		$(".sharebox").show();
	})
	//兑换
	$(".mibootom").click(function(){
		var dlstate = true;
		if(dlstate){
			dlstate = false;
			$('.zzdh').show();
			$('.tipmodal').show();
			$(".chargestate").show();
			$(".chargefail").hide();
			$(".chargesuccess").hide();
			$.ajax({
				url:'/dogfood/exchange.html',
				type:'post',
				dataType:'json',
				success:function(data){
					$('.zzdh').hide();
					if(data.errorcode==0){
						$(".chargesuccess").show();
						$(".dogLines>.fs").html(data.info.num);
						$(".lin>.fs").html(data.info.price);
						dlstate = true;
					}else{
						$(".chargefail>.chargetip").html(data.info+'<br/>暂时不能兑换');
						$(".chargefail").show();
					}
				}
			})
		}
	})
	//关闭模态框
    $('.tipmodal').click(function(){
    	$('.sharebox').hide();
    	$(".chargestate").hide();
    	$(".tipmodal").hide();
    });
    $('.beforesharemodal').click(function(){
    	$('.beforesharemodal').hide();
    })
	</script>
</body>
</html>