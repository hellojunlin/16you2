var myIntegral = angular.module("Myintegralmall",[]);
myIntegral.controller('LoadDataCtrl',['$scope','$http',function($scope,$http){
	$http({
		method:'GET',
		url:'/integral/getdata.html',
		//url:'/media/js/data.json'
	})
	.success(function(response){
		$scope.notices = response.notice;
 		$scope.integral = response.integral;
		$scope.integraldatas = response.datas;
		$scope.carousaldatas = response.carousel;
		$('.loading').hide();
	})
	.error(function(response){
		console.log("error...");
	});
	//滑动加载数据
	var databool = true;
	var page = 1;
	$(window).scroll(function(){
		if(checkScrollSlide()&&databool){	//判断页面滚动条往下拖 
			//$('.loading').show();
	    	databool = false;
			$http({
				method:'GET',
				url:'/integral/getdata.html?page='+page,
			})
			.success(function(response){
				var items = response.datas;
				for (var i = 0; i < items.length; i++) {
					$scope.integraldatas.push(items[i]);
				}
				if(response.code==0){	 
					 page++;
					 databool = true;
					 var p = $('<p>').css('text-align','center').appendTo($('.mall_page'));
					 $('<img>').css('width','0.4rem').attr('src','/media/images/loading.gif').appendTo(p);
				}else{ 
					$('<p>').css({'text-align':'center','font-size':'0.24rem','padding':'0.1rem'}).append('数据已加载完！').appendTo($('.mall_page'));
				}
				$('.loading').hide();
			})
			.error(function(response){
				console.log("error...");
			});
		}
	}); 
	function checkScrollSlide(){
		var lastBox = $('.mall_box').last();
		var lastBoxDis = lastBox.offset().top+Math.floor(lastBox.outerHeight(true)/2);	//即当拖到最后一张的一半时加载
		var scrollTop = $(window).scrollTop();	//滚动条滚动高度
		var documentH = $(window).height();	//页面可视区高度
		return (lastBoxDis<scrollTop+documentH)?true:false;
	};
	//对应模块点击事件w
	$scope.opendetail=function(a){
		window.location.href="todetail/"+a+".html";
	}
}]);

//changedetail.html
var myChangedetail = angular.module("Myintegraldetail",[]);
myChangedetail.controller('LoadDatadetailCtrl',['$scope','$http',function($scope,$http){
	$http({
		method:'GET',
		url:'/integral/detaildata.html',
	})
	.success(function(response){
		$scope.loaddetail = response.product;
		$scope.user = response.user;
	})
	.error(function(response){
		console.log("error....");
	})
}]);

//过滤标签
myChangedetail.filter('to_trusted', ['$sce', function ($sce) {
    return function (text) {
        return $sce.trustAsHtml(text);
    };
}]);

//积分记录
var myrecord = angular.module("Myrecord",[]);
myrecord.controller('MyRecordCtrl',['$scope','$http',function($scope,$http){
	$http({
		method: 'GET',
		//url: '/media/js/integralrecord.json'
		url:'/integral/getintegraldata.html'
	})
	.success(function(response){
		$scope.head_url = response.head_url;
		$scope.integral = response.integral;
		$scope.records = response.recordlists;
		$('.loading').hide();
	})
	.error(function(response){
		console.log("error....");
	})
	//积分规则
	$scope.jump=function(){
		window.location.href="rules.html";
	}
	//滑动加载数据
	var databool = true;
	var page = 1;
	$(window).scroll(function(){
		if(checkScrollSlide()&&databool){	//判断页面滚动条往下拖 
	    	databool = false;
			$http({
				method:'GET',
				url:'/integral/getintegraldata.html?page='+page,
			})
			.success(function(response){
				var items = response.recordlists;
				for(var i=0;i<items.length;i++){
					$scope.records.push(items[i]);
				}
				if(response.code == 0){
					page++;
					databool = true;
					var p = $('<p>').css('text-align','center').appendTo($('.dynamic'));
					 $('<img>').css('width','0.4rem').attr('src','/media/images/loading.gif').appendTo(p);
				}else{
					$('<p>').css({'text-align':'center','font-size':'0.24rem','padding':'0.1rem'}).append('数据已加载完！').appendTo($('.dynamic'));
				}
			})
			.error(function(response){
				console.log("error...");
			});
		}
	}); 
	function checkScrollSlide(){
		var lastBox = $('.row_dynamic').last();
		var lastBoxDis = lastBox.offset().top+Math.floor(lastBox.outerHeight(true)/2);	//即当拖到最后一张的一半时加载
		var scrollTop = $(window).scrollTop();	//滚动条滚动高度
		var documentH = $(window).height();	//页面可视区高度
		return (lastBoxDis<scrollTop+documentH)?true:false;
	};
}]);
 

//兑换记录
var myrecord = angular.module("Mychangerecord",[]);
myrecord.controller('MychangeRecordCtrl',['$scope','$http',function($scope,$http){
	$http({
		method: 'GET',
		url: '/integral/getchangedata.html' 
	})
	.success(function(response){
		$scope.crecords = response.datas;
		$('.loading').hide();
	})
	.error(function(response){
		console.log("error....");
	})
	//滑动加载数据
	var databool = true;
	var page = 1;
	$(window).scroll(function(){
		if(checkScrollSlide()&&databool){	//判断页面滚动条往下拖 
	    	databool = false;
			$http({
				method:'GET',
				url:'/integral/getchangedata.html?page='+page,
			})
			.success(function(response){
				var item = response.datas;
				for(var i=0;i<item.length;i++){
					$scope.crecords.push(item[i]);
				}
				if(response.code==0){	 
					 var p = $('<p>').css('text-align','center').appendTo($('.dynamic'));
					 $('<img>').css('width','0.4rem').attr('src','/media/images/loading.gif').appendTo(p);
					page++;
					databool = true;
				}else{
					$('<p>').css({'text-align':'center','font-size':'0.24rem','padding':'0.1rem'}).append('数据已加载完！').appendTo($('.dynamic'));
				}
			})
			.error(function(response){
				console.log("error...");
			});
		}
	}); 
	function checkScrollSlide(){
		var lastBox = $('.row_dynamic').last();
		var lastBoxDis = lastBox.offset().top+Math.floor(lastBox.outerHeight(true)/2);	//即当拖到最后一张的一半时加载
		var scrollTop = $(window).scrollTop();	//滚动条滚动高度
		var documentH = $(window).height();	//页面可视区高度
		return (lastBoxDis<scrollTop+documentH)?true:false;
	};
}]);

