<!DOCTYPE html>
<html lang="en" ng-app="Myrecord">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<title>积分记录</title>
	<link rel="stylesheet" type="text/css" href="/media/css/integral.css">
	<script type="text/javascript" src="/media/js/rem.js"></script>
	<script src="/media/js/angular-1.3.0.js"></script>
	<script src="/media/js/integralmall.js"></script>
</head>
<body>
    <div class="loading" style="position:fixed;top:40%;left:50%;margin-left:-0.4rem;z-index:100;">
    	<img style="width:1rem;" src="/media/images/loading.gif">	
    </div>
	<div class="regulation" ng-controller="MyRecordCtrl">
	  <div >
		<div class="integral_record">
			<div class="record">
				<div class="record_left">
					<em>
						<img ng-cloak ng-src="{{head_url}}">
					</em>
					<span>
						<i ng-bind="integral"></i>积分
					</span>
				</div>
				<div class="record_right" ng-click="jump()">
				<span>积分规则</span>
				</div>
			</div>
		</div>
		<div class="dynamic">
			<ul class="state">
				<li class="row_dynamic" ng-repeat="recordlist in records">
					<ul class="condition" ng-switch="recordlist.type">
						<li class="rowTime" ng-bind="recordlist.createtime*1000 | date:'yyyy-MM-dd'"></li>
						<li ng-switch-when="0">每日首兑</li>
						<li ng-switch-when="1">每日首充</li>
						<li ng-switch-when="2">购买商品</li>
						<li ng-if="recordlist.integral < 0"  class="rowNuber lired" ng-bind="recordlist.integral"></li>
						<li ng-if="recordlist.integral >= 0"  class="rowNuber ligreen" ng-bind="recordlist.integral"></li>
					</ul> 			
				</li>			
			</ul>
		</div>
		</div>
	</div>
</body>
</html>