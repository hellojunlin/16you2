<!DOCTYPE html>
<html lang="en" ng-app="Myintegraldetail">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
	<meta name = "format-detection" content = "telephone=no">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<title>积分兑换详情</title>
	<link rel="stylesheet" type="text/css" href="/media/css/change.css">
	<script type="text/javascript" src="/media/js/rem.js"></script>
	<script src="/media/js/angular-1.3.0.js"></script>
	<script src="/media/js/integralmall.js"></script>
</head>
<body>
  <div ng-controller="LoadDatadetailCtrl">
    <!-- <div ng-repeat="loaddetail in loaddetails"> -->
	<div class="change_mall_box">
	  	<em>
	  		<img src="<?php echo yii::$app->params['backends'];?>/media/images/product/{{loaddetail.image_url}}">
	  	</em>
	  	<div class="change_mall_size">
	  		<h1 ng-bind="loaddetail.product_name"></h1>
	  		<span>
				我的积分:
	  			<i class="dream_sunny" ng-bind="user"></i>
	  		</span>
	  		<div class="sl">
	  			<p>剩余数量:<i class="iblur" ng-bind="loaddetail.number"></i></p>
	  			<p id="inte11">兑换积分:<i class="ired dream_sunny" ng-bind="loaddetail.integral"></i></p>
	  		</div>
	  	</div>
	 </div>
	 <div class="produce">
		 <h1>商品详情</h1>
		 <div ng-bind-html-unsafe="expression" ng-bind-html="loaddetail.prdouct_details | to_trusted"></div>
	 </div>
	 <div class="change_nav">
	 	<div class="change_more">
	 		<a class="her-text" href="/personal/index.html">赚取更多积分</a>
	 	</div>
	 	<div class="change_att" ng-if='user < loaddetail.integral'>
	 		<a href="#">立即兑换</a>
	 	</div>
	 	<div class="change_att" style="background:#3879D9" onclick="changeclick()" ng-if='user >= loaddetail.integral'>
	 		<a href="#">立即兑换</a>
	 	</div>
	 </div>
	 <input type="hidden" name="type" value="{{loaddetail.type}}"/>
	 <!-- </div> -->
  </div>
  	 	<!-- //收货地址弹框 -->
	<div class="rank_bg">
		<div class="ptnList">
			<h1>收货地址填写</h1>
			<form action="#" method="post">
				<div class="myselfInfo">
					<ul class="rankDer">
						<li class="pastFontsize">
							<span class="pastRank">手机号码：</span>
							<input type="number"  id="tel" class="addressText" oninput="this.value = /^(13[0-9]|14[0-9]|15[0-9]|18[0-9])\d{8}$/.test(this.value)?this.value:this.value.substr(0,11)" required="required" name="phone" placeholder="输入11位手机号码">
						</li>
					</ul>
				</div> 
				<div class="convertBtn" id="convertBtn">
						<a href="#">立即兑换</a>
				</div>
			</form>
			<div id="deleteBtn">
				<img src="/media/images/close_gray.png">
			</div>
		</div>
	</div>
</body>
</html>
<script type="text/javascript" src="/media/js/jquery.min.js"></script>
<script>
	//收货地址弹框
	function changeclick(){
		ptype = $('input[name="type"]').val();
		if(ptype==1 && !$('input[name="area"]').length){
			$('.rankDer>.pastFontsize').before('<li class="pastFontsize"><span id="pastRank" class="pastRank">收货地址：</span><input type="text" class="addressText" maxlength="100" name="area" placeholder="输入详细收货地址" required="required" ></li>');
		}
		$('.rank_bg').css('display', 'block');
	}
	$('#convertBtn').click(function(){
		var product_name = $.trim($(".change_mall_size>h1").html());
		var integral = $.trim($("#inte11>i").html());
		var area = $.trim($('input[name="area"]').val());
		var phone = $.trim($('input[name="phone"]').val());
		if(ptype==1){
			if(area==''){
				!$('#area_s').length && $('input[name="area"]').after("<br/><span class='error' id='area_s'>请填写收货地址！</span>");
				return;
			}
		}
		if(!(/^(13[0-9]|14[0-9]|15[0-9]|18[0-9])\d{8}$/i).test(phone)){
			!$('#tel_s').length && $('#tel').after("<br/><span class='error' id='tel_s'>手机号码格式不正确！</span>");
    		return;
		}
		$.ajax({
			url:'/integral/toarea.html', 
			type:'post',
			data:{'product_name':product_name,'integral':integral,'area':area,'phone':phone},
			dataType:'json',
			success:function(data){
				if(data.errorcode==0){ 
					window.location.href="/integral/tochangetail.html";
				}else{ 
					alert(data.info);
				}
			}
		});
		return true;
	});

	$('#deleteBtn').click(function() {
		$('.rank_bg').css('display', 'none');
	});
    //返回上一个历史页面

</script>