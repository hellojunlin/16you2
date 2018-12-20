<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<meta name="description" content="16游游戏——一路游戏一路游，无需下载即点即玩！">
    <meta name="keywords" content ="16游,16游官方下载,16游App,16youAPP">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']?>/pc/css/download.css?v=1.1">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']?>/pc/css/swiper-3.3.1.min.css">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']?>/pc/js/swiper(jspacker).js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']?>/pc/js/rem.js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']?>/pc/js/jquery.min.js"></script>
	<title>下载页</title>
	<style type="text/css">

	</style>
</head>
<body>
	<div class="content">
		<div class="header">
			<div class="logo">
				<img src="<?php echo yii::$app->params['cdn16you']?>/pc/images/downlogo.png">
			</div>
			<div class="gameName">
				<h2>16游</h2>
			</div>
			<div class="gameBrief">
				<div class="brief">
					<i>2.6M</i>|游戏盒子
				</div>
			</div>
		</div>
		<div class="middle">
			<P class="ml">多款游戏任你挑选</P>
			<div class="detailbox">
				<div id="tabs-container" class="swiper-container swiper-container-horizontal">
					<div class="swiper-wrapper detail-swiper">
					   	<div class="swiper-slide swiper-slide-active" style="width: 118.333px; margin-right: 10px;">
							<img class="detialimg" src="<?php echo yii::$app->params['cdn16you']?>/pc/images/download1.jpg">
						</div>
						<div class="swiper-slide swiper-slide-next" style="width: 118.333px; margin-right: 10px;">
							<img class="detialimg" src="<?php echo yii::$app->params['cdn16you']?>/pc/images/download2.jpg">
						</div>
						<div class="swiper-slide" style="width: 118.333px; margin-right: 10px;">
							<img class="detialimg" src="<?php echo yii::$app->params['cdn16you']?>/pc/images/download3.jpg">
						</div>
						<div class="swiper-slide" style="width: 118.333px; margin-right: 10px;">
							<img class="detialimg" src="<?php echo yii::$app->params['cdn16you']?>/pc/images/download4.jpg">
						</div>
						<div class="swiper-slide" style="width: 118.333px; margin-right: 10px;">
							<img class="detialimg" src="<?php echo yii::$app->params['cdn16you']?>/pc/images/download5.jpg">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="footer">
			<h4>应用描述</h4>
			<p>16游汇集精品H5游戏服务，热门游戏、精挑细选，好玩游戏无需下载，点击即玩。是你玩游戏的必备应用，拥有它你就拥有了丰富多彩的游戏世界。这里聚集的众多游戏玩家，让你玩游戏从此不寂寞，是你手游伴侣！还不快快下载一个试试！！</p>
		    <p>16游是16游平台开发的移动互联网H5游戏平台，16游H5游戏致力于广大移动用户提供单机游戏、手机网游、社交游戏及游戏互动社区等全方位的移动娱乐，让移动用户免费、简单、快捷的享受快乐的移动娱乐生活。16游想你所想，为你提供专属游戏世界。</p>
		</div>
		<div class="download">
			<div class="dl">
				<div class="down">
					<a href="#" id="download">下载</a>
				</div>
			</div>
		</div>
	</div>
	<!-- 遮罩层 -->
	<div id="pc-downloadList" style="display:none">
		<span class="wxtip-icon"></span>
		<p class="wxtip-txt">点击右上角<br>选择在浏览器中打开</p>
	</div>
</body>
<script type="text/javascript">
		//判断是否是微信浏览器的函数
	function isWeiXin(){
	
	}
	isWeiXin();
	
	//大图介绍滑动
	   var tabsSwiper = new Swiper('#tabs-container',{
	      speed:500,
	      slidesPerView : 3,
	      spaceBetween : 10,
	    })

	    $('.down').click(function(){
	    	  //window.navigator.userAgent属性包含了浏览器类型、版本、操作系统类型、浏览器引擎类型等信息，这个属性可以用来判断浏览器类型
		  	  var ua = window.navigator.userAgent.toLowerCase();
		  	  //通过正则表达式匹配ua中是否含有MicroMessenger字符串
		  	  if(ua.match(/MicroMessenger/i) == 'micromessenger'){
		  		  // alert('请复制链接,前往浏览器下载');
		  		  $('#pc-downloadList').css('display','block');
		  	      return true;
		  	  }else{
			  		window.location.href ="/download/download.html";
		  	  }
	    	
	   });  
	    $('#pc-downloadList').click(function(){
	    	 $('#pc-downloadList').hide();
	    })
</script>
</html>