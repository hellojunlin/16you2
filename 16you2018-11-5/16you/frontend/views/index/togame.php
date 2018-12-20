<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<link rel="stylesheet" type="text/css" href="/media/css/common.css">
	<script type="text/javascript" src="/media/js/jquery.min.js"></script>
	<script type="text/javascript" src="/media/js/rem.js"></script>
	<title>支付</title>
	<style>
	body{
		background: #fff;
	}
	.paydiv {
	    text-align: center;
	    margin-top: 1rem;
	}
	.paydiv  img{
	    display: block;
	    width: 50%;
	    margin: auto;
	}
	.paydiv a{
	    width: 65%;
	    display: block;
	    padding: 0.15rem 0;
	    border-radius: 6px;
	    margin: 0.5rem auto;
	    font-size: 0.26rem;
	    color: #fff;
	}
	a.nopay{
		background: #f2ca3b;
	}
	a.hadpay{
		background: #ff0000;
	}
	</style>
</head>
<body>
	<div class="paydiv">
        <img src="/media/images/first_img.png">
    	<a id="paybtn" class="nopay">支付</a>
    </div>
    <!--iframe-->
	<div id="gamepage">
	   	<div id="game-iframe-div">
		   	<iframe id="game-frame" name="gameFrame" frameborder="no" border="px" marginwidth="0px" marginheight="0px" scrolling="auto"  src="http://lssionwx.mxse13.com/user/text.html">
		   	</iframe>
	   	</div>
	</div>
	<script src="/media/js/1.js"></script>
	<script>
	var param = '{share:{';
	<?php foreach($share as $v): ?>
		var type = "<?php echo $v['type']; ?>";
		switch(type){
			case '1':
			param+='friend:{';
			break;
			case '2':
			param+='timeline:{';
			break;
		}
		<?php foreach($v as $ke=>$va): ?>
		param+='<?php echo $ke.':"'.$va.'"'; ?>';
		<?php if($va!=end($v)): ?>
		param+=',';
		<?php endif; ?>
		<?php endforeach; ?>
		param+='}';
		<?php if($v!=end($share)): ?> 
		param+=',';
		<?php endif; ?>
	<?php endforeach; ?>
	param+='}}';
	param= eval('('+ param +')');//将string转为object
	TPGAME_SDK.config(param);

	//点击支付
    $('#paybtn').click(function(){
	    $.ajax({
			url:'/index/toorder.html',
			dataType:'json',
			type:'post',
			success:function(data){
				$('#paybtn').removeClass('nopay').addClass('hadpay').text(data.info+',该订单一共 '+data.price+' 元');
			}
		});	
    })
    </script>
</body>
</html>