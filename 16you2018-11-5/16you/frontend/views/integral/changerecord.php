<!DOCTYPE html>
<html lang="en" ng-app="Mychangerecord">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<title>兑换记录</title>
	<link rel="stylesheet" type="text/css" href="/media/css/integral.css">
	<script type="text/javascript" src="/media/js/rem.js"></script>
	<script src="/media/js/angular-1.3.0.js"></script>
	<script src="/media/js/integralmall.js"></script>
</head>
<body>
	<div class="loading" style="position:fixed;top:40%;left:50%;margin-left:-0.4rem;z-index:100;">
    	<img style="width:1rem;" src="/media/images/loading.gif">	
    </div>
	<div class="regulation" ng-controller="MychangeRecordCtrl">
	  <div>		
		<div class="exchangeList">
			<div class="change_header">
				<div class="change_left">
					<p>
						兑换实物商品和更多大礼
					</p>
					<p>请添加QQ群</p>
				</div>
				<div class="change_right">
					<div class="qq">
						446494138
					</div>
					<p>长按复制QQ群号</p>
				</div>
			</div>
		</div>
		<div class="dynamic">
			<ul class="state">
				<li class="row_dynamic" ng-repeat="crecordlist in crecords">
					<ul class="condition">	
						<li class="rowTime" ng-bind="crecordlist.createtime*1000 |date:'yyyy-MM-dd'"></li>
						<li ng-bind="crecordlist.product_name"></li>
						<li ng-if="crecordlist.integral < 0"  class="rowNuber lired" ng-bind="crecordlist.integral"></li>
						<li ng-if="crecordlist.integral >= 0"  class="rowNuber ligreen" ng-bind="crecordlist.integral"></li>
					</ul>		
				</li>				
			</ul>
		</div>
		</div>
	</div>
</body>
</html>