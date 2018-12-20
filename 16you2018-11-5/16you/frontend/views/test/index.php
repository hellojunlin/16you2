<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<script type="text/javascript" src="http://wx.16you.com/media/js/jquery.min.js"></script>
	<script src="/media/js/1.js"></script> 
</head>
<body>
	<p><a href="/pay/getdata.html">测试支付</a></p>
	<button onclick="zhifu12()" style="width:500px;background:#f90">支付1234567890</button>
	<div id="divid"></div>
	<script>
	function zhifu12(){
		$.ajax({
			url:'/test/getdata1.html',
			type:'post',
			success:function(data){
				$("#divid").html(data);
			}
		})
	}
	</script>
</body>
</html>