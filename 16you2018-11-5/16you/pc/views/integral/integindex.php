<!DOCTYPE html>
<html lang="en" ng-app="Myintegralmall">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<title>积分商城</title>
	<link rel="stylesheet" type="text/css" href="/media/css/store.css">
	<script type="text/javascript" src="/media/js/rem.js"></script>
</head>
<body class="cube">
    <div class="loading" style="position:fixed;top:40%;left:50%;margin-left:-0.4rem;z-index:100;">
    	<img style="width:1rem;" src="/media/images/loading.gif">	
    </div>
	<div class="mall" ng-controller="LoadDataCtrl">		
	   <div id="h_header" class="bg_white">
	     <div class="h_top">
	     	 <div id="slideBox" class="slideBox">
	     	 	 <div class="bd swiper-container">
	     	 	 	<ul class="swiper-wrapper" style="list-style:none;" >
	     	 	 		<li class="swiper-slide" ng-repeat="carousal in carousaldatas">
	     	 	 			<a ng-cloak href="{{carousal.url}}">
							     <img ng-src="<?php echo yii::$app->params['backend'];?>/images/carousel/{{carousal.image}}"  />
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
	  	<ul class="navList cl" >
	  		<li>
	  		   <a href="/integral/torecord.html">
	  			<em>
	  				<i class="jfimg jficon01"></i>
	  			</em>
	  			<span>
	  				<i ng-bind="integral"></i>
	  				积分
	  			</span>
	  			</a>
	  		</li>
	  		<!-- <li>
	  			<em>
	  				<i class="jfimg jficon02"></i>
	  			</em>
	  			<span>幸运抽奖</span>
	  		</li> -->
	  		<li>
	  		 	<a href="/integral/tochangetail.html">
	  			<em>
	  				<i class="jfimg jficon03"></i>
	  			</em>
	  			<span>兑换记录</span>
	  			</a>
	  		</li>
	  	</ul>
	  </div>
	  <div class="mall_page">
	    <div class="mt_fly"></div>
	  	<div class="mall_notice" id="mall_notice">
	  		<em>
	  			<img src="/media/images/notice.png">
	  		</em>
	  		<div class="notice_content" id="notice_content">
		  		<ul>
		  			<li ng-repeat="n in notices">  
		  				<i class="mar_right_sm2" ng-bind="n.username"></i><a ng-bind="n.product_name"></a>
		  			</li>
		  		</ul>
	  		</div>
	  		<div id="orgin"></div>
	  	</div>
	  	<div class="mall_details" >
	  		<div class="mall_box" ng-repeat="integral in integraldatas" ng-cloak ng-click='opendetail(integral.id)'>
	  			<em>
	  				<img ng-src="<?php echo yii::$app->params['backend'];?>/media/images/product/{{integral.image_url}}" >
	  			</em>
	  			<div class="mall_size">
	  				<h1 ng-bind="integral.product_name"></h1>
	  				<span>
						剩余:
	  					<i ng-bind="integral.number"></i>
	  				</span>
	  				<b ng-bind="integral.integral">积分</b>
	  			</div>
	  		</div>
	  	</div>
	  </div>
	</div>
</body>
	<!-- 首页滑屏 start-->
<link rel="stylesheet" href="/media/css/swiper.min.css">
<script type="text/javascript" src="/media/js/swiper(jspacker).js"></script>
<script type="text/javascript" src="/media/js/angular-1.3.0.js"></script>
<script type="text/javascript" src="/media/js/integralmall.js"></script>
<script type="text/javascript" src="/media/js/jquery.min.js"></script>
		<!-- 首页滑屏 end--> 
 <script type="text/javascript">
       var backend = "<?php echo yii::$app->params['backend'];?>";
        var mySwiper = new Swiper('.swiper-container',{
		autoplay: 3000,
		pagination : '.swiper-pagination',
		paginationClickable :true,
		observer:true,
		observeParents:true
		})	     	

// 		公告栏 公告栏  
		var speed=160;
        var FGDemo=document.getElementById('mall_notice');
        var FGDemo1=document.getElementById('notice_content');
        var FGDemo2=document.getElementById('orgin');
        FGDemo2.innerHTML=FGDemo1.innerHTML;
        function Marquee1(){
            if(FGDemo2.offsetHeight-FGDemo.scrollTop<=0){
                FGDemo.scrollTop-=FGDemo1.offsetHeight
            }   
            else{
                FGDemo.scrollTop++
            }
        }
        var MyMar1=setInterval(Marquee1,speed) 
        //size
        FGDemo.onmouseover=function() {
            clearInterval(MyMar1)
        }
        FGDemo.onmouseout=function() {
            MyMar1=setInterval(Marquee1,speed)
        }
  </script>
</html>