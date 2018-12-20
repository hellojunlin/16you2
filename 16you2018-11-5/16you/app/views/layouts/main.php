<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name = "format-detection" content = "telephone=no">
	<meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/app/js/rem.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/app/css/common.css?v=1.1">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/app/css/game.css">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/app/js/jquery.min.js"></script>	
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/app/js/jquery.cookie.js"></script>
	<script src="http://pv.sohu.com/cityjson?ie=utf-8"></script> 
</head>
<script>
//判断访问终端
	var browser={
    versions:function(){
	        var u = navigator.userAgent, app = navigator.appVersion;
	        return {
	            trident: u.indexOf('Trident') > -1, //IE内核
	            presto: u.indexOf('Presto') > -1, //opera内核
	            webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
	            gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1,//火狐内核
	            mobile: !!u.match(/AppleWebKit.*Mobile.*/), //是否为移动终端
	            ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
	            android: u.indexOf('Android') > -1 || u.indexOf('Adr') > -1, //android终端
	            iPhone: u.indexOf('iPhone') > -1 , //是否为iPhone或者QQHD浏览器
	            iPad: u.indexOf('iPad') > -1, //是否iPad
	            webApp: u.indexOf('Safari') == -1, //是否web应该程序，没有头部与底部
	            weixin: u.indexOf('MicroMessenger') > -1, //是否微信 （2015-01-22新增）
	            qq: u.match(/\sQQ/i) == " qq" //是否QQ
	        };
	    }(),
	    language:(navigator.browserLanguage || navigator.language).toLowerCase()
	}
	
		if(browser.versions.ios){
			var iosWidth = window.screen.availWidth;
		    // $('body').css('margin','0');
		    $('body').css('max-width',iosWidth);
		}

		
<?php  $user = yii::$app->session['user'];?>
var _uid = "<?php echo $user?$user->id:'';?>";//获取用户ID 
var _username = "<?php echo $user?$user->username:'';?>";//用户名称
var _pid = "<?php echo $user?$user->pid:''?>"; //渠道唯一标识 
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
  <header class="mt_head" id="head" style="display: none">
  	<span class="mt_goback"><img class="mt-dow" src="<?php echo yii::$app->params['cdn16you']; ?>/images/back.png"></span>
    <h1 class="mt_h">16游</h1>
  </header>
  <div id="game-listbox">
 		<?=$content ?>
  </div>	
</body>
<script>
	//腾讯统计
  	var _mtac = {"senseQuery":1};
  	(function() {
  		var mta = document.createElement("script");
  		mta.src = "http://pingjs.qq.com/h5/stats.js?v2.0.4";
  		mta.setAttribute("name", "MTAH5");
  		mta.setAttribute("sid", "500599454");
  		mta.setAttribute("cid", "500599455");
  		var s = document.getElementsByTagName("script")[0];
  		s.parentNode.insertBefore(mta, s);
  	})();
</script>
</html>
<script>
if(_equipment!='other'){
	$(".mt_head").hide();
}
//菜单切换
$('#bottom_menu a').click(function(){
	$(this).addClass('on').siblings().removeClass('on');
})
//pc端的header调整
$(function(){
	if ($("header").is(":hidden")) {
		$("#game-listbox").css("padding-top","0%");
	}else{
		$("#game-listbox").css("padding-top","44px");
	}
	if ($("")) {};
})
//返回-
$(".mt_goback").click(function(){
	history.go(-1);
})
/* var _mtac = {};
(function() {
    var mta = document.createElement("script");
    mta.src = "http://pingjs.qq.com/h5/stats.js?v2.0.2";
    mta.setAttribute("name", "MTAH5");
    mta.setAttribute("sid", "500506326");
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(mta, s);
})(); */
</script>

