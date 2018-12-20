<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>提现</title>
    <link rel="stylesheet" href="/media/embody/css/init.css">
    <link rel="stylesheet" href="/media/embody/css/html.css">
    <link rel="stylesheet" href="/media/embody/css/index.css">
    <script type="text/javascript" src="/media/js/jquery.min.js"></script>	
	<script type="text/javascript" src="/media/js/jquery.cookie.js"></script>
</head>

<body>
    <div class="page">
        <div class="header">  
            <h4 class="header-title">提现</h4>
        </div>
        <div class="main">
            <p>您的金额：<?php echo $price;?>元</p>
        </div>
        <div class="put-btn"> 
            <button type="button" class="submit-btn">提现</button>
        </div>
        <div class="rq-code">
            <p>关注16游公众号接收消息</p>
            <img src="/media/embody/img/rqcode.jpg" alt="">
        </div>
    </div>
</body>
<script>
var openid = "<?php echo $openid;?>";
  $('.submit-btn').click(function(){
	$.ajax({
		url:'/index/embody.html',
		data:{'openid':openid},
		dataType:'json',
		type:'post',
		success:function(data){
		  if(data.errorcode==0){
			  alert('已提交申请，请耐心等待审核');
			  window.location.href = "/index/index.html";
		  }else{
              alert(data.msg);
		  }
		}
	});
})
</script>
</html>