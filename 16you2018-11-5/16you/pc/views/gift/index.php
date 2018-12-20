<!DOCTYPE html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <meta name = "format-detection" content = "telephone=no">
	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<script type="text/javascript" src="http://wx.16you.com/media/js/rem.js"></script>
	<link rel="stylesheet" type="text/css" href="/media/css/common.css">
	<link rel="stylesheet" type="text/css" href="/media/css/game.css">
	<script type="text/javascript" src="http://wx.16you.com/media/js/jquery.min.js"></script>	
	<script type="text/javascript" src="http://wx.16you.com/media/js/jquery.cookie.js"></script>
</head>
<body>
  <header class="mt_head">
    <span class="mt_goback"><img class="mt-dow" src="/media/images/back.png"></span>
    <h1 class="mt_h">我的礼包</h1>
  </header>
<div id="content-list" class="spl_mt">
	<div class="contentbox">
	  	<div id="hotgame" class="gamecontent" name='2'>
		  	<?php if(isset($gift)&&$gift): ?>
		  	<?php foreach ($gift as $k => $v):?>
		  	<div class="game_list_box">
				<ul class="game_ul">
					<li class="game_img">
						<img src="<?php echo yii::$app->params['cdn']; ?>/game/<?php echo $v['game_image']; ?> "/>
					</li>
					<li class="game_describe desc_mt">
						<p>
							<span class="game_name"><?php echo $v['game_name'].':'.$v['gift_name'] ?></span>
						</p>
						<p class="describe"><?php echo $v['content']; ?></p>
						<p class="tips">注意：兑换码每个服可用一次，每个礼包只能领一次</p>
					</li>
					<li class="game_start"><a class="start" name="<?php echo $v['gid'].'%$#'.$v['gift_name'].'%$#'.$v['CDKEY'].'%$#'.$v['content']; ?>">查看</a></li>
				</ul>
			</div>
			<?php endforeach; ?>
			<?php else:?>
			<p class="describeno">暂时没有礼包</p>
			<?php endif; ?>
	  	</div>
	</div>
</div>
<!--弹框-领取礼包-->
<div id="giftmodal" style="display:none;">
	<div class="servebox">
		<div class="get_img"><img src="http://wx.16you.com/media/images/get_img.png"></div>
		<img class="closeimg" src="http://wx.16you.com/media/images/close_gray.png">
		<h5></h5>
		<div class="gifttxt"></div>
		<div class="notice">兑换码每个服可用一次，每个礼包只能领一次</div>
		<div class="active_num">
			<a href="#"></a>
		</div>
		<h2><img src="http://wx.16you.com/media/images/up_icon.png">长按上方复制激活码</h2>
		<div class="ewm_box">
			<a>悬浮提示</a>
		</div>
		<div class="receive_btn">
			<a>开始游戏</a>
		</div>
	</div>
</div>
<span id="span_backend" class="hid"><?php echo yii::$app->params['cdn']; ?></span>
<script>
	//领取礼包弹框
	$('body').on('click','.start',function(){
		var name = $(this).attr('name').split('%$#');
		$('.servebox>h5').html(name['1']);
		$('.servebox>.gifttxt').html(name['3']);
		$('.servebox>.active_num>a').html(name['2']);
		$(".receive_btn>a").attr('href','/start/index/'+name['0']+'.html');
		$('#giftmodal').show();
	})
	//关闭领取礼包弹框
	$('.closeimg,.receive_btn').click(function(){
		$('#giftmodal').hide();
	})

	databool = true;
	$(window).scroll(function(){
		if(checkScrollSlide()&&databool){	//判断页面滚动条往下拖 
	    	databool = false;
	    	if(!$('#_nodata').length && !$('#hotgame>.tmodel').length){
	    		var page = $("#hotgame").attr('name');//礼包的页数
	    		$("#hotgame").append('<p class="tmodel">正在加载...</p>');
				$.ajax({
					url:'/gift/index.html',
					data:{'page':page},
					dataType:'json',
					type:'post',
					success:function(data){
						var info = data.info;
						if(data.errorcode==0){
							$.each(info,function(kg,vg){
								$("#hotgame>.tmodel").before('<div class="game_list_box"><ul class="game_ul"><li class="game_img"><img src="'+$('#span_backend').html()+'/game/'+vg.game_image+'"/></li><li class="game_describe desc_mt"><p><span class="game_name">'+vg.game_name+':<i>'+vg.gift_name+'</i></span></p><p class="describe">'+vg.content+'</p><p class="tips">注意：兑换码每个服可用一次，每个礼包只能领一次</p></li><li class="game_start"><a class="start" name="'+vg.gid+'%$#'+vg.gift_name+'%$#'+vg.CDKEY+'%$#'+vg.content+'">查看</a></li></ul></div>');
							});
							page++; 
							$("#hotgame").attr('name',page);
							databool = true;
						}else{
							$("#hotgame").append('<div class="nodata" id="_nodata" align="center">'+info+'</div>');
						}
						$(".tmodel").remove();
					}
				});
			}
		}
	});
    function checkScrollSlide(){
		var lastBox = $('.game_list_box').last();
		var lastBoxDis = lastBox.offset().top+Math.floor(lastBox.outerHeight(true)/2);	//即当拖到最后一张的一半时加载
		var scrollTop = $(window).scrollTop();	//滚动条滚动高度
		var documentH = $(window).height();	//页面可视区高度
		return (lastBoxDis<scrollTop+documentH)?true:false;
	}

	//返回-
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
		   data.page = 'mygift';
		   data.title =  "我的礼包";
	       window.parent.postMessage(data, '*');
	   }	
}
</script>
</body>
</html>