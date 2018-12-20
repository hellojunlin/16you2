<!DOCTYPE html>
<html lang="en" ng-app="Myintegralmall">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<title>积分商城</title>
	<link rel="stylesheet" type="text/css" href="/media/css/store.css">
	<script type="text/javascript" src="/media/js/rem.js"></script>
</head>
<body>
	<div class="mall">
	   <div id="h_header" class="bg_white">
	     <div class="h_top">
	     	 <div id="slideBox" class="slideBox">
	     	 	 <div class="bd swiper-container">
	     	 	 	<ul class="swiper-wrapper" style="list-style:none;">
	     	 	 		<li class="swiper-slide">
	     	 	 			<a href="#" target="_blank">
							     <img src="/media/images/20161227120026qiniu2245_.png" />
						    </a>
	     	 	 		</li>
	     	 	 		<li class="swiper-slide">
	     	 	 			<a href="#" target="_blank">
							     <img src="/media/images/20161227115853qiniu9959_.png" />
						    </a>
	     	 	 		</li>
	     	 	 	</ul>
	     	 	 </div>
     	 	 	<ul class="hd swiper-pagination swiper-pagination-clickable swiper-pagination-bullets">
     	 	 		<li><a class="swiper-pagination-bullet swiper-pagination-bullet-active">1</a></li>
     	 	 		<li><a class="swiper-pagination-bullet">2</a></li>
     	 	 	</ul>
	     	 </div>
	     </div>
	  </div>
	  <div class="storeNav">
	  	<ul class="navList cl">
	  		<li>
	  		   <a href="/integral/torecord.html">
	  			<em>
	  				<img src="/media/images/mall_icon01.png">
	  			</em>
	  			<span>
	  				<i>400</i>
	  				积分
	  			</span>
	  			</a>
	  		</li>
	  		<!-- <li>
	  			<em>
	  				<img src="/media/images/mall_icon02.png">
	  			</em>
	  			<span>幸运抽奖</span>
	  		</li> -->
	  		<li>
	  		 	<a href="/integral/torecord.html">
	  			<em>
	  				<img src="/media/images/mall_icon03.png">
	  			</em>
	  			<span>兑换记录</span>
	  			</a>
	  		</li>
	  	</ul>
	  </div>
	  <div class="mall_page">
	  	<div class="mall_notice">
	  		<em>
	  			<img src="/media/images/notice.png">
	  		</em>
	  		<div class="notice_content">
		  		<p>
		  			<i>浮生若梦</i>
		  			刚刚兑换了100元移动话费劵
		  		</p>
	  		</div>
	  	</div>
	  	<div class="mall_details" ng-controller="LoadDataCtrl">
	  		<div class="mall_box" ng-repeat="integral in integraldatas" ng-click="opendetail()">
	  			<em>
	  				<img ng-src="{{integral.imgsrc}}">
	  			</em>
	  			<div class="mall_size">
	  				<h1>{{integral.h1title}}</h1>
	  				<span>
						剩余:
	  					<i>{{integral.surplus}}</i>
	  				</span>
	  				<b>{{integral.total}}积分</b>
	  			</div>
	  		</div>
	  	</div>
	  </div>
	</div>
</body>
	<!-- 首页滑屏 start-->
<link rel="stylesheet" href="/media/css/swiper.min.css">
<script src="/media/js/swiper.min.js"></script>
<script src="/media/js/angular-1.3.0.js"></script>
	<script src="/media/js/integralmall.js"></script>
		<!-- 首页滑屏 end--> 
 <script type="text/javascript">
        var mySwiper = new Swiper('.swiper-container',{
		autoplay: 3000,
		pagination : '.swiper-pagination',
		paginationClickable :true,

		})	     	 
  </script>
</html>