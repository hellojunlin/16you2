<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<title>微信登录提示</title>
    <script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/pc/js/rem.js"></script>
    <link rel="stylesheet" href="<?php echo yii::$app->params['cdn16you']; ?>/pc/css/codelogin.css"/>
</head>
<body>
	<div class="bg_f">
    <div class="relation_txt">微信登录</div>
    <div class="relation_ewm">
        <em><img src="/media/images/code/<?php echo $code; ?>"></em>
        <span>长按保存二维码图片到手机</span>
    </div>
    <div class="relation_img"><img src="<?php echo yii::$app->params['cdn16you']; ?>/pc/images/step1.png"></div>
    <div class="relation_img"><img src="<?php echo yii::$app->params['cdn16you']; ?>/pc/images/step2.png"></div>
    <div class="relation_img"><img src="<?php echo yii::$app->params['cdn16you']; ?>/pc/images/step3.png"></div>
    <div class="relation_img"><img src="<?php echo yii::$app->params['cdn16you']; ?>/pc/images/step4.png"></div>
    <div class="relation_img img_end"><img src="<?php echo yii::$app->params['cdn16you']; ?>/pc/images/step_end.png"></div>
</div>
</body>
</html>
<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/pc/js/jquery.min.js"></script>
<script>
    var verify = "<?php echo $verify; ?>";
    timeID1 = setInterval(function(){
        $.ajax({
            url:'/index/verifyuser.html',
            type:'post',
            dataType:'json',
            data:{'verify':verify},
            success:function(data){
                if(data.errorcode==0){
                    clearInterval(timeID1);//清除定时
                    window.location.href="/personal/index.html";
                }else{
                    console.log(data.info);
                }
            }
        });
    }, 2000);    
</script>