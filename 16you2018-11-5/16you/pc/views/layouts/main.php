<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <meta name = "format-detection" content = "telephone=no">
	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/pc/js/rem.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']; ?>/pc/css/common.css?v=1.412">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']; ?>/pc/css/game.css">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/pc/js/jquery.min.js"></script>	
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/pc/js/jquery.cookie.js"></script>
	<script src="http://pv.sohu.com/cityjson?ie=utf-8"></script> 
</head>
<script>
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
 		<!--底部菜单-->
		<div id="bottom_menu">
		<?php $typemenu = isset(yii::$app->session['typemenu'])?yii::$app->session['typemenu']:1;?>
			<a class="menu_game <?php echo ($typemenu==1)?'on':'';?>" href="/game/list.html">
				<i class="game_icon"></i>
				<em>游戏</em>
			</a>
			<a class="menu_package <?php echo ($typemenu==7)?' on':'';?>" href="/category/leisure.html">
				<i class="leisure_icon"></i>
				<em>休闲游戏</em>
			</a>
			<a class="menu_mall <?php echo ($typemenu==3)?' on':'';?>" href="/integralshop/index.html">
				<i class="mall_icon"></i>
				<em>商城</em>
			</a>  
			<a class="menu_package <?php echo ($typemenu==5)?' on':'';?>" href="/gift/togift.html">
				<i class="package_icon"></i>
				<em>游戏礼包</em>
			</a>
			<a id="menu_personal" class="menu_personal <?php echo ($typemenu==4)?' on':'';?>" href="/personal/index.html">
				 <?php if(isset($this->params['emaillayout'])):if(!empty($this->params['emaillayout'])):?> <span class="redpoint" ></span><?php endif;endif;?>
				<i class="center_icon"></i>
				<em>个人中心</em> 
			</a>
		</div>
  </div>	
</body>
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
var _mtac = {};
(function() {
    var mta = document.createElement("script");
    mta.src = "http://pingjs.qq.com/h5/stats.js?v2.0.2";
    mta.setAttribute("name", "MTAH5");
    mta.setAttribute("sid", "500506326");
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(mta, s);
})();

</script>

