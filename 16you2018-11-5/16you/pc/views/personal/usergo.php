<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>用户指引</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="<?php echo yii::$app->params['cdn16you']; ?>/pc/css/instructions.css">
	<script type="text/javascript" src="<?php echo yii::$app->params['cdn16you']; ?>/pc/js/jquery.min.js"></script>
</head>
<body>
    <header class="mt_head">
        <span class="mt_goback"><img class="mt-dow" src="<?php echo yii::$app->params['cdn16you']; ?>/images/back.png"></span>
        <h1 class="mt_h">用户指引</h1>
    </header>
	<div class="user_guide ">
	    <h4>网络游戏用户指引和警示说明</h4>
	    <p>16游-精品游戏（以下称“16游”）在此特别提醒用户注意：</p>
	    <p>本网络游戏仅适合于年满18周岁以上的用户。如果您未满18周岁，不建议您注册并使用本网络游戏服务；如果您坚持使用，请您务必加入本网络游戏防沉迷系统进行实名注册，同时我们将会按照您法定监护人的需求提供您在本网络游戏中的信息。</p>
	    <p>您在使用本网络游戏服务之前已经明确知晓网络游戏可能给您带来的一切潜在威胁，包括长时间游戏可能不利于您的身心健康等。为了您的健康请您合理的使用本网络游戏。</p>
	</div>
</body>
</html>
<script>
//返回-1
$(".mt_goback").click(function(){
	history.go(-1);
})
</script>