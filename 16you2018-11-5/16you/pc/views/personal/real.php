<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>实名认证</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']; ?>/pc/css/Account-security.css">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/pc/js/rem.js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/pc/js/jquery.min.js"></script>
</head>
<body>
    <header class="mt_head">
        <span class="mt_goback"><img class="mt-dow" src="<?php echo yii::$app->params['cdn16you']; ?>/images/back.png"></span>
        <h1 class="mt_h">实名认证</h1>
    </header>
	<div class="validation-sw mar_btm2">
		<div class="bind_top">
			<div class="bind_phone" onclick="location.href='/personal/realedit.html'">
				<span class="binding">实名认证</span>
				<p class="bind_Boolean">
					<a class="c_sw" href="#">
						
					</a>
					<em class="str_q">
						<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/right_btn.png">
					</em>
				</p>
			</div>
		</div>
	</div>
	<div class="validation-sw">
		<div class="bind_bootom">
			<div class="bind_alipay brdBttom-1" onclick="location.href='/personal/usergo.html'">
				<span class="binding">用户指引</span>
				<p class="bind_Boolean">
					<a class="c_sw" href="#">
						
					</a>
					<em class="str_q">
						<img src="<?php echo yii::$app->params['cdn16you']; ?>/images/right_btn.png">
					</em>
				</p>
			</div>
			<div class="bind_qq " onclick="location.href='/personal/dispute.html'">
				<span class="binding ">纠纷处理方式</span>
				<p class="bind_Boolean">
					<a class="c_sw" href="#">
						
					</a>
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
$(".mt_goback").click(function(){
	history.go(-1);
})

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
	
if(browser.versions.android || browser.versions.ios){
      $('.mt_head').css('display','none');
	 //通知app该页面
	   window.onload=function(){
		   var data = {};
		   data.page = 'real';
		   data.title =  "实名认证";
	       window.parent.postMessage(data, '*');
	   }	
}
</script>