<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name = "format-detection" content = "telephone=no">
	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/app/css/common.css">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/app/css/swiper.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16yous']; ?>/app/css/notice.css">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/app/js/rem.js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/app/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16yous']; ?>/app/js/swiper.min.js"></script>
	<title><?php echo $model->label; ?></title>
</head>
<script>
	var data = {};
	data.page = 'consult';
	data.title =  "资讯";
	data.state='start';
	window.parent.postMessage(data, '*');

	
	
	//通知app该页面 
	window.onload=function(){
		   var data = {};
		   data.page = 'consult';
		   data.title =  "资讯";
		   data.state='end';
	       window.parent.postMessage(data, '*');
	}	
</script>
<body>
	<div id="noticebox" style="margin: 0.25rem 0.12rem 1.6rem;">
		<div class="notice-title">
			<div class="n_title"><?php echo $model->title; ?></div>
			<div class="n_time">
				<span class="n_img">
					<em><img src="<?php echo yii::$app->params['cdn16yous']; ?>/app/images/meanself.png"></em>
				</span>
				<span class="spantime"><?php echo date('Y-m-d',$model->createtime); ?></span>
			</div>
		</div> 
		<div class="informationboard">
			<?php echo $model->content; ?>
		</div>
		<?php if($redis): ?>
		<div class="n_box1">
			<div class="title">
				<span></span>其他资讯
			</div>
			<?php foreach ($redis as $v):?>
			<div class="notice_list">
				<div class="game_list_box">
					<ul class="news_ul" onclick="window.location.href='/consult/detail/<?php echo $v['id']; ?>.html'">
						<li class="game_img">
							<a class="ad"><?php echo $v['label']; ?></a>
						</li>
						<li class="game_describe">
							<p class="game_p"><?php echo $v['title']; ?></p>
						</li>
						<li class="game_start"><span class="time"><?php echo date('m-d',$v['createtime']); ?></span></li>
					</ul>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
		<?php if($hotgame): ?>
		<div class="n_box2">
			<div class="title">
				<span></span>近期热门
			</div>
			<div id="tabs_ulcontent" class="game_list clearfloat swiper-container">
				<ul class="listul swiper-wrapper">	
					<?php foreach ($hotgame as $vh):?>
					<li  class="swiper-slide" onclick="window.location.href='/start/index/<?php echo $vh['id']; ?>.html'">
						<img src="<?php echo yii::$app->params['backend'];?>/media/images/game/<?php echo $vh['head_img'];?>" class="recentlyimg">
						<p><?php echo $vh['name'];?></p>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<?php endif; ?>
		<?php if($game): ?>
		<div class="bottom-start"style="bottom: 0px;position: fixed;">
			<div class="game_list_box">
				<ul class="game_ul">
					<li class="game_img">
						<img src="<?php echo yii::$app->params['backend'];?>/media/images/game/<?php echo $game->head_img;?>" class="recentlyimg">
					</li>
					<li class="game_describe">
						<p>
							<span class="game_name"><?php echo $game->name; ?></span>
						</p>
						<p class="describe"><?php echo $game->descript; ?></p>
					</li>
					<li class="game_start"><a class="start" onclick="window.location.href='/start/index/<?php echo $game->id; ?>.html'">开始</a></li>
				</ul>
			</div>
		</div>
		<?php endif; ?>
	</div>
	<script>
		//近期热门滑动
	   var tabsSwiper = new Swiper('#tabs_ulcontent',{
	      speed:500,
	      slidesPerView : 5,
	    })
	   //返回-
		$("#mt_goback").click(function(){
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

   if(browser.versions.ios){
	   var iosWidth = window.screen.availWidth;
	    $('body').css('max-width',iosWidth);
	    $('.bottom-start').css('bottom','45px');
	    $('.bottom-start').css('position','fixed');
   }
	</script>
</body>
</html>