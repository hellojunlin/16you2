<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8"> 
	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<link rel="stylesheet" type="text/css" href="/media/css/common.css">
	<link rel="stylesheet" type="text/css" href="/media/css/swiper.min.css">
	<link rel="stylesheet" type="text/css" href="/media/css/notice.css">
	<script type="text/javascript" src="/media/js/rem.js"></script>
	<script type="text/javascript" src="/media/js/jquery.min.js"></script>
	<script type="text/javascript" src="/media/js/swiper.min.js"></script>
	<title><?php echo $model->label; ?></title>
</head>
<body>
	<div id="noticebox">
		<div class="notice-title">
			<div class="n_title"><?php echo $model->title; ?></div>
			<div class="n_time">
				<span class="n_img">
					<em><img src="/media/images/meanself.png"></em>
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
		<div class="bottom-start">
			<div class="game_list_box">
				<ul class="game_ul">
					<li class="game_img">
						<img src="<?php echo yii::$app->params['backend'];?>/media/images/game/<?php echo $game->head_img;?>">
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
	</script>
</body>
</html>