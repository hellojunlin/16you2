<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<!DOCTYPE html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <meta name = "format-detection" content = "telephone=no">
	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/rem.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/css/common.css?v=1.1">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/css/game.css?v=1.0">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/jquery.min.js"></script>	
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/js/jquery.cookie.js"></script>
	<script src="http://pv.sohu.com/cityjson?ie=utf-8"></script>
	<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>  
	<style type="text/css">
		/* #bottom_menu a.on i.gamesmall_icon {
		    background: url(/media/images/smallyo_an.png) no-repeat;
		    background-size: 160%;
		    background-position: -4px -10px;
		}
		.biggame{
			background-size: 160%;
		    background-position: -4px -10px;
		} */
	</style>
</head>
<script>
wx.config({
	debug:false,
	<?php $signPackage = yii::$app->session->get('signPackage');?>
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: '<?php echo $signPackage["timestamp"];?>',
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
		//所有要调用的 API 都要加到这个列表中
		'onMenuShareTimeline',
		'onMenuShareAppMessage',
		'hideMenuItems'
    ]
  });
  wx.ready(function () {
    // 在这里调用 API
	  //分享朋友圈	
	  wx.onMenuShareTimeline({//voteinfo
	    title:'16游', // 分享标题
	    link: '',// 分享链接
	    imgUrl:"<?php echo yii::$app->params['frontends'];?>/media/images/icon_mean.png", // 分享图标
	    success: function () { 
	       
	    },
	    cancel: function () { 
	        // 用户取消分享后执行的回调函数
	    	// alert("分享失败");
	    }
	});
	
	//分享朋友
	wx.onMenuShareAppMessage({
	    title: '16游', // 分享标题
	    desc: '游戏精品', // 分享描述
	    link: '',// 分享链接
	    imgUrl: "<?php echo yii::$app->params['frontends'];?>/media/images/icon_mean.png", // 分享图标
	    type: 'link', // 分享类型,music、video或link，不填默认为link
	    success: function () { 
	    },
	    cancel: function () { 
	    }
	}); 
	//隐藏分享QQ
	wx.hideMenuItems({
	 	    menuList: ['menuItem:share:qq'] // 要隐藏的菜单项，只能隐藏“传播类”和“保护类”按钮，所有menu项见附录3
	});
  });


<?php  $user = isset(yii::$app->session['user'])?yii::$app->session['user']:'';?>
var _uid = "<?php echo isset($user)?$user->id:'';?>";//获取用户ID  
var _username = "<?php echo isset($user)?$user->username:'';?>";//用户名称
var _pid = "<?php echo isset($user)?$user->pid:''?>"; // 渠道唯一标识
var _ip = returnCitySN["cip"]+'/'+returnCitySN["cname"]; //ip/地域
var _equipment = "<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
					     echo 'IOS';
				}else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
	   				 echo 'Android';
				}else{
	 			     echo 'other';
				};?>"; //设备
function postmessage() {          //(function(){})是一个闭包的用法，闭包必定会被调用。  
	// var ga = document.createElement('script');   
	// ga.type = 'text/javascript';   
	// ga.charset='gbk';  
	// ga.async = true;//ga.async = true 异步调用外部js文件，即不阻塞浏览器的解析  
	// ga.src = '/media/js/ana.js';    
	// var s = document.getElementsByTagName('script')[0];    //取得第一个tag名为script的元素  
	// s.parentNode.insertBefore(ga, s);             //在s前添加元素ga  
};  
</script>
<body>
  <div id="game-listbox">
 		<?=$content ?>
 		<!--底部菜单-->
		<div id="bottom_menu">
		<?php $typemenu = isset(yii::$app->session['typemenu'])?yii::$app->session['typemenu']:1;?>
			<a class="menu_game <?php echo ($typemenu==1)?'on':'';?>" href="/index/index.html">
				<i class="game_icon"></i>
				<em>游戏</em>
			</a>
			<a class="menu_package <?php echo ($typemenu==7)?' on':'';?>" href="/category/leisure.html">
				<i class="leisure_icon"></i>
				<em>休闲游戏</em>
			</a>
			<!--  <a class="menu_rank <?php echo ($typemenu==2)?' on':'';?>" href="/ranking/index.html">
				<i class="ranking_icon"></i>
				<em>游戏金榜</em>
			</a>--> 
			<a class="menu_mall <?php echo ($typemenu==3)?' on':'';?>" href="/integralshop/index.html">
				<i class="mall_icon"></i>
				<em>商城</em>
			</a>
			<!-- <a class="menu_package <?php echo ($typemenu==8)?' on':'';?>" href="/dogfood/index.html">
				<i class="eleven_icon"></i>
				<em>活动</em>
			</a> -->
			<!-- <a class="menu_package <?php echo ($typemenu==8)?' on':'';?>" href="/lucked/index.html">
				<i class="active_icon"></i>
				<em>活动</em>
			</a> -->
			<a class="menu_package <?php echo ($typemenu==5)?' on':'';?>" href="/gift/togift.html">
				<i class="package_icon"></i>
				<em>游戏礼包</em>
			</a>
			
			<!-- <a class="menu_package <?php //echo ($typemenu==6)?' on':'';?>" href="/sgameindex/index.html">
				<i class="gamesmall_icon biggame"></i>
				<em>小游戏</em>
			</a> -->
			<a class="menu_personal <?php echo ($typemenu==4)?' on':'';?>" href="/personal/index.html">
			   <?php if(yii::$app->session['emailnotice']):?> <span class="redpoint" ></span><?php endif;?>
				<i class="center_icon"></i>
				<em>个人中心</em>
			</a>
		</div>
  </div>	
<script>
  	var _mtac = {"performanceMonitor":1,"senseQuery":1};
  	(function() {
  		var mta = document.createElement("script");
  		mta.src = "//pingjs.qq.com/h5/stats.js?v2.0.4";
  		mta.setAttribute("name", "MTAH5");
  		mta.setAttribute("sid", "500504927");
  		mta.setAttribute("cid", "500504955");
  		var s = document.getElementsByTagName("script")[0];
  		s.parentNode.insertBefore(mta, s);
  	})();
  	//菜单切换
	$('#bottom_menu a').click(function(){
		$(this).addClass('on').siblings().removeClass('on');
	})
</script>
</body>
</html>