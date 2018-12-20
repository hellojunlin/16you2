<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"> 
	<title>404</title>
	<link rel="stylesheet" type="text/css" href="/media/css/common.css">
	<script type="text/javascript" src="http://wx.16you.com/media/js/rem.js"></script>
	<style type="text/css">
		.errorimg{
			text-align: center;
			margin: 0rem 0rem -1.1rem;
		}
        .errortips{
        	text-align: center;
        }
        .errortips a{
		    text-decoration: none;
		    display: inline-block;
		    color: #fff;
		    background: #ff5722;
		    padding: 0.083rem 0.17rem;
		    border-radius: 6px;
		    font-size: 0.2rem;
		    margin: 0.5rem 0.17rem 0rem 0rem;
        }
        .errortips p{
        	font-size: 0.26rem;
        }
        .subtip{
        	font-size: 0.22rem !important;
        	color:#999;
        	padding-top: 0.1rem;
        }
	</style>
</head>
<body>
	<div id="error">
		<div class="errorimg"><img src="<?php echo yii::$app->params['cdn16you']; ?>/media/images/error1.png"></div>
		<div class="errortips">
<!-- 			<p>哎呀！！！您访问的页面不见了</p>
			<p class="subtip">您访问的页被移除或者网络不佳，请重试。。。</p> -->
			<a href="javascript:void(0)" onclick='window.history.back()'>返回上一页</a>
		</div>
	</div>
</body>
</html>